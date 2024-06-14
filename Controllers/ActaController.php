<?php
require_once './Models/ActaModel.php';
require_once './includes/router.php';
require_once './classes/JWT.php';
class ActaController
{
    public static function crear(Router $router)
    {
        $acta = new ActaModel;
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
                        $acta->sincronizar($_POST);
                        $alertas = $acta->validarNuevaCuenta();
                        if (empty($alertas['error'])) {
                            $resultado = $acta->guardar();
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

                $router->render('/forms/acta', [
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

    public static function obtenerActas()
    {
        $acta = new ActaModel;
        $actasArray = [];

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
                        $actasArray = ActaModel::obtenerTodasActas();
                    } catch (\Throwable $th) {
                        $acta::setAlerta('error', $th->getMessage());
                    }
                }
                echo json_encode($actasArray);
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

    public static function actualizarActa()
    {
        header('Content-Type: application/json');
        $alertas = [];
        $acta = new ActaModel;

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

                    $encontrado = ActaModel::find($datos['id']);

                    if ($encontrado) {
                        $acta->sincronizar($datos);
                        $alertas = $acta->validarNuevaCuenta();

                        if (!empty($alertas['error'])) {
                            echo json_encode(['error' => $alertas['error']]);
                            return;
                        }
                        $acta->guardar();
                        echo json_encode(['success' => 'Acta actualizada exitosamente!']);
                    } else {
                        echo json_encode(['error' => 'No se encontró el acta con el ID proporcionado']);
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
