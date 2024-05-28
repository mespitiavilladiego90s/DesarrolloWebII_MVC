<?php
require_once './Models/ReunionModel.php';
require_once './includes/router.php';
class ReunionController
{
    public static function crear(Router $router)
    {
        session_start();
        checkPerm();
        $reunion = new ReunionModel;
        $alertas = [];
        $message = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $_SESSION['form_data'] = $_POST;
            try {
                $reunion->sincronizar($_POST);
                $alertas = $reunion->validarNuevaCuenta();
                if (empty($alertas['error'])) {
                    $resultado = $reunion->guardar();
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

        $router->render('/forms/reunion', [
            'alertas' => $alertas,
            'message' => $message,
            'form_data' => $form_data
        ]);
    }

    public static function obtenerReuniones()
    {
        $reunion = new ReunionModel;
        $reunionesArray = [];

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            try {
                $reunionesArray = ReunionModel::obtenerTodasReuniones();
            } catch (\Throwable $th) {
                $reunion::setAlerta('error', $th->getMessage());
            }
        }

        echo json_encode($reunionesArray);
    }

    public static function actualizarReunion()
    {
        header('Content-Type: application/json');

        $alertas = [];
        $reunion = new ReunionModel;
        $datos = json_decode(file_get_contents("php://input"), true);

        try {
            if (empty($datos)) {
                echo json_encode(['error' => 'No se recibieron datos para actualizar']);
                return;
            }

            $encontrado = ReunionModel::find($datos['id']);

            if ($encontrado) {
                $reunion->sincronizar($datos);
                $alertas = $reunion->validarNuevaCuenta();

                if (!empty($alertas['error'])) {
                    echo json_encode(['error' => $alertas['error']]);
                    return;
                }
                $reunion->guardar();
                echo json_encode(['success' => 'Reunión actualizada exitosamente!']);
            } else {
                echo json_encode(['error' => 'No se encontró la reunión con el ID proporcionado']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }
}
