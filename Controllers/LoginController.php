<?php
require_once './Models/UsuarioModel.php';
require_once './includes/router.php';
require_once './classes/Email.php';
require_once './classes/JWT.php';

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];
        $token = '';

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['token']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new UsuarioModel($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                $usuario = UsuarioModel::existeUsuario($auth->email);

                if ($usuario && $usuario->comprobarPasswordAndVerificado($auth->password)) {
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

                    // Guardar el token en la sesión
                    $_SESSION['token'] = $token;
                    $_SESSION['id'] = $usuario->id;
                    // Redireccionar según el rol del usuario
                    header('Location: /index');
                    exit;
                } else {
                    UsuarioModel::setAlerta('error', 'Usuario no encontrado o contraseña incorrecta');
                }
            }
        }

        $alertas = UsuarioModel::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'token' => $token
        ]);
    }

    public static function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['token']);
        header('Location: /login');
        exit;
    }

    public static function olvide(Router $router)
    {
        $alertas = [];

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['token']);

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

    public static function recuperar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['token']);

        $usuario = new UsuarioModel;
        $encontrado = UsuarioModel::where('token', $token);

        if (empty($encontrado)) {
            $usuario::setAlerta('error', 'Token No Válido');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                $encontrado->password = $usuario->password;
                $encontrado->hashPassword();
                $encontrado->token = null;
                $resultado = $encontrado->guardar();

                if ($resultado) {
                    header('Location: /login');
                }
            }
        }

        $alertas = $usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas
        ]);
    }

    public static function crear(Router $router)
    {
        $usuario = new UsuarioModel;
        $alertas = [];

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['token']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                $resultado = UsuarioModel::existeUsuario(s($_POST['email']));

                if ($resultado) {
                    $usuario::setAlerta('error', 'Usuario ya está registrado.');
                } else {
                    $usuario->hashPassword();
                    $usuario->crearToken();
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    } else {
                        $usuario::setAlerta('error', 'Ocurrió un error inesperado al intentar crear el usuario');
                    }
                }
            }
        }

        $alertas = $usuario::getAlertas();
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['token']);
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['token']);

        if (isset($_GET['token'])) {
            $token = s($_GET['token']);
            $usuario = UsuarioModel::where('token', $token);

            if (empty($usuario)) {
                UsuarioModel::setAlerta('error', 'Token No Válido');
            } else {
                $usuario->confirmado = 1;
                $usuario->token = null;
                $usuario->guardar();
                UsuarioModel::setAlerta('exito', 'Cuenta Comprobada Correctamente');
            }
        } else {
            UsuarioModel::setAlerta('error', 'Token no proporcionado');
        }

        $alertas = UsuarioModel::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }

    public static function index(Router $router)
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = isset($_SESSION['token']) ? $_SESSION['token'] : null;

        if ($token) {
            $jwt = new JWT();
            $payload = $jwt->decode($token);

            if ($payload) {
                $router->render('index', ['payload' => $payload]);
            } else {
                unset($_SESSION['token']);
                header('Location: /login');
                exit;
            }
        } else {
            unset($_SESSION['token']);
            header('Location: /login');
            exit;
        }
    }

    public static function obtenerReuniones(Router $router)
    {
        $reunion = new ReunionModel;
        $reunionesArray = [];

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = isset($_SESSION['token']) ? $_SESSION['token'] : null;

        if ($token) {
            $jwt = new JWT();
            $payload = $jwt->decode($token);

            if ($payload) {
                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    try {
                        $reunionesArray = UsuarioModel::obtenerTodaInfoReunion();
                    } catch (\Throwable $th) {
                        $reunion::setAlerta('error', $th->getMessage());
                    }
                }
                echo json_encode($reunionesArray);
            } else {
                unset($_SESSION['token']);
                header('Location: /login');
                exit;
            }
        } else {
            unset($_SESSION['token']);
            header('Location: /login');
            exit;
        }
    }


}
