<?php
require_once './Models/CompromisoModel.php';
require_once './includes/router.php';
require_once './classes/JWT.php';
class CompromisoController
{
    public static function crear(Router $router)
    {
        $compromiso = new CompromisoModel;
        $alertas = [];
        $message = '';

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = isset($_SESSION['token']) ? $_SESSION['token'] : null;

        if ($token) {
            $jwt = new JWT();
            $payload = $jwt->decode($token);

            if ($payload) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $_SESSION['form_data'] = $_POST;
                    try {
                        $compromiso->sincronizar($_POST);
                        $alertas = $compromiso->validarNuevaCuenta();
                        if (empty($alertas['error'])) {
                            $resultado = $compromiso->guardar();
                            if ($resultado) {
                                $message = 'Successfully added into your database!';
                                unset($_SESSION['form_data']); // Limpiamos el form data si se guardó correctamente.
                            } else {
                                $alertas['error'][] = 'Error occurred while saving.';
                            }
                        }
                    } catch (\Throwable $th) {
                        $alertas['error'][] = $th->getMessage();
                    }
                }

                $form_data = $_SESSION['form_data'] ?? [];

                $router->render('/forms/compromiso', [
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

    public static function obtenerCompromisos()
    {
        $compromiso = new CompromisoModel;
        $compromisosArray = [];

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
                        $compromisosArray = CompromisoModel::obtenerTodosCompromisos();
                    } catch (\Throwable $th) {
                        $compromiso::setAlerta('error', $th->getMessage());
                    }
                }
                echo json_encode($compromisosArray);
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

    public static function actualizarCompromiso()
    {
        header('Content-Type: application/json');
        $alertas = [];
        $compromiso = new CompromisoModel;

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

                    $encontrado = CompromisoModel::find($datos['id']);

                    if ($encontrado) {
                        $compromiso->sincronizar($datos);
                        $alertas = $compromiso->validarNuevaCuenta();

                        if (!empty($alertas['error'])) {
                            echo json_encode(['error' => $alertas['error']]);
                            return;
                        }
                        $compromiso->guardar();
                        echo json_encode(['success' => 'Compromiso actualizado exitosamente!']);
                    } else {
                        echo json_encode(['error' => 'No se encontró el compromiso con el ID proporcionado']);
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
