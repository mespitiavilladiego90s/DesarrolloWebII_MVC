<?php
require_once './Models/UsuarioModel.php';
require_once './includes/router.php';
require_once './classes/Email.php';
require_once './classes/JWT.php';

class LoginController {
    public static function login(Router $router) {
        $alertas = [];
        $token = '';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new UsuarioModel($_POST);
            $alertas = $auth->validarLogin();
    
            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = UsuarioModel::existeUsuario($auth->email);
    
                if ($usuario) {
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Crear JWT
                        $jwt = new JWT();
                        $token = $jwt->encode([
                            'id' => $usuario->id,
                            'nombre' => $usuario->nombre,
                            'apellido' => $usuario->apellido,
                            'email' => $usuario->email,
                            'rol' => $usuario->rol,
                            'exp' => time() + (60 * 60) 
                        ]);

                        debuguear($token);
    
                        // Verificar si es admin
                        if ($usuario->rol === 'Admin') {
                            // Redireccionar a la página de index
                            header('Location: /index');
                            exit;
                        } else {
                            // Redireccionar a la página de login
                            header('Location: /login');
                            exit;
                        }
                    }
                } else {
                    UsuarioModel::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
    
        $alertas = UsuarioModel::getAlertas();

        
    
        $router->render('auth/login', [
            'alertas' => $alertas,
            'token' => $token
        ]);
    }
    

    public static function logout() {
        // El logout simplemente invalida el token en el frontend.
        echo json_encode(['message' => 'Logout successful']);
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new UsuarioModel($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = UsuarioModel::existeUsuario($auth->email);

                if ($usuario && $usuario->confirmado === 1) {
                    // Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de éxito
                    UsuarioModel::setAlerta('exito', 'Revisa tu email');
                } else {
                    UsuarioModel::setAlerta('error', 'El Usuario no existe o no está confirmado');
                }
            }
        }

        $alertas = UsuarioModel::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = UsuarioModel::where('token', $token);

        if (empty($usuario)) {
            UsuarioModel::setAlerta('error', 'Token No Válido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo

            $password = new UsuarioModel(s($_POST));
            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if ($resultado) {
                    header('Location: /login');
                }
            }
        }

        $alertas = UsuarioModel::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {
        $usuario = new UsuarioModel;

        // Alertas vacías
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alerta esté vacío
            if (empty($alertas)) {
                // Verificar que el usuario no esté registrado
                $resultado = UsuarioModel::existeUsuario(s($_POST['email']));

                if ($resultado) {
                    $alertas = UsuarioModel::getAlertas();
                } else {
                    // Hashear el Password
                    $usuario->hashPassword();

                    // Generar un Token único
                    $usuario->crearToken();

                    // Enviar el Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        

        // Verificar si se proporciona un token en la URL
        if (isset($_GET['token'])) {
            $token = s($_GET['token']);

            // Buscar usuario por su token
            $usuario = UsuarioModel::where('token', $token);

            if (empty($usuario)) {
                // Mostrar mensaje de error
                UsuarioModel::setAlerta('error', 'Token No Válido');
            } else {
                // Modificar a usuario confirmado
                $usuario->confirmado = 1;
                $usuario->token = null;
                $usuario->guardar();
                UsuarioModel::setAlerta('exito', 'Cuenta Comprobada Correctamente');
            }
        } else {
            // Si no se proporciona un token en la URL, mostrar mensaje de error
            UsuarioModel::setAlerta('error', 'Token no proporcionado');
        }

        // Obtener alertas
        $alertas = UsuarioModel::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }

    public static function index(Router $router){
        session_start();
        checkPerm();
        $router->render('index', [

        ]);
    }
}
