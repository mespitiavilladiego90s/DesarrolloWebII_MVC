<?php
require_once './Models/UsuarioModel.php';
require_once './includes/router.php';
require_once './classes/JWT.php';
require_once './classes/Email.php';
class PersonaController
{
    public static function crear(Router $router)
    {
        $persona = new UsuarioModel;
        $alertas = [];
        $message = '';

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = isset($_SESSION['token']) ? s($_SESSION['token']) : null;

        if ($token) {
            $jwt = new JWT();
            $payload = $jwt->decode($token);

            if ($payload) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $_SESSION['form_data'] = $_POST;
                    try {
                        $usuario = UsuarioModel::existeUsuario(s($_POST['email']));

                        if ($usuario) {
                            $alertas['error'][] = 'La persona ya está registrada con ese email.';
                        } else {
                            $persona->sincronizar($_POST);
                            $alertas = $persona->validarNuevaCuenta();
                            if (empty($alertas['error'])) {
                                $pass = $persona->password;
                                $persona->hashPassword();
                                $persona->crearToken();
                                $email = new Email($persona->nombre, $persona->email, $persona->token);
                                $email->enviarNuevaPersona($pass);
                                $resultado = $persona->guardar();
                                if ($resultado) {
                                    $message = 'Successfully added into your database!';
                                    unset($_SESSION['form_data']); // Limpiamos el form data si se guardó correctamente.
                                } else {
                                    $alertas['error'][] = 'Error occurred while saving.';
                                }
                            }
                        }
                    } catch (\Throwable $th) {
                        $alertas['error'][] = $th->getMessage();
                    }
                }

                $form_data = $_SESSION['form_data'] ?? [];

                $router->render('/forms/persona', [
                    'alertas' => $alertas,
                    'message' => $message,
                    'form_data' => $form_data,
                    'payload' => $payload
                ]);
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

    public static function obtenerPersonas()
    {
        $persona = new UsuarioModel;
        $personasArray = [];

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
                        $personasArray = UsuarioModel::obtenerTodosUsuarios();
                    } catch (\Throwable $th) {
                        $persona::setAlerta('error', $th->getMessage());
                    }
                }
                echo json_encode($personasArray);
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

    public static function actualizarPersona()
    {
        header('Content-Type: application/json');
        $alertas = [];
        $persona = new UsuarioModel;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = isset($_SESSION['token']) ? $_SESSION['token'] : null;

        if ($token) {
            $jwt = new JWT();
            $payload = $jwt->decode($token);

            if ($payload) {

                $datos = json_decode(file_get_contents("php://input"), true);

                try {
                    if (empty($datos)) {
                        echo json_encode(['error' => 'No se recibieron datos para actualizar']);
                        return;
                    }

                    $encontrado = UsuarioModel::find($datos['id']);

                    if ($encontrado) {
                        $persona->sincronizar($datos);
                        $alertas = $persona->validarNuevaCuenta();

                        if (!empty($alertas['error'])) {
                            echo json_encode(['error' => $alertas['error']]);
                            return;
                        }
                        $persona->hashPassword();
                        $persona->guardar();
                        echo json_encode(['success' => 'Persona actualizada exitosamente!']);
                    } else {
                        echo json_encode(['error' => 'No se encontró la persona con el ID proporcionado']);
                    }
                } catch (\Throwable $th) {
                    echo json_encode(['error' => $th->getMessage()]);
                }
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
