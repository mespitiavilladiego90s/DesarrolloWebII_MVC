<?php
require_once './Models/ActaModel.php';
require_once './includes/ActiveRecord.php';
require_once './includes/router.php';

class ActaController
{
    public static function crear(Router $router)
    {
        header('Content-Type: application/json'); // Establece el encabezado de respuesta como JSON
        
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new ActaModel($_POST);
            $resultado = $auth->guardar();


        }

        // Si no es una solicitud POST, devuelve un error
        echo json_encode(['error' => 'Solicitud no válida']);
    }

   
}

