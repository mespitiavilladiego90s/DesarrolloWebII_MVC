<?php
require_once './Models/VendedorModel.php';
require_once './includes/ActiveRecord.php';


class VendedorController{
    public static function obtenerVendedorPorId($id)
    {
        $vendedor = VendedorModel::find($id);

        try {
            // Verificamos si se encontró el vendedor mediante la consulta
            if ($vendedor) {
                echo json_encode($vendedor);
            } else {
                // Si no se encontró el vendedor, retornar un JSON con el mensaje 'No se encontró por ID'
                echo json_encode(['mensaje' => 'No se encontro por ID']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }

    public static function crearVendedor()
    {
        // Obtenemos los datos enviados en el cuerpo de la solicitud POST
        $datos = json_decode(file_get_contents("php://input"), true);

        try {
            // Validamos los datos del vendedor utilizando el método validarDatos del modelo VendedorModel
            $alertas = VendedorModel::validarDatos($datos);

            if (!empty($alertas['error'])) {
                // Si hay errores, retornar un JSON con los mensajes de error
                echo json_encode(['error' => $alertas['error']]);
                return;
            }

            // Creamos un nuevo objeto VendedorModel con los datos proporcionados
            $nuevoVendedor = new VendedorModel($datos);

            // Intentamos guardar el nuevo vendedor en la base de datos
            $resultado = $nuevoVendedor->guardar();

            // Verificamos si la operación de guardado fue exitosa
            if ($resultado['resultado']) {
                // Si el vendedor se creó exitosamente, retornar un JSON con el ID del nuevo vendedor
                echo json_encode(['Creado' => $resultado['id']]);
            } else {
                // Si ocurrió un error al crear el vendedor, retornar un JSON con un mensaje de error específico
                echo json_encode(['error' => $resultado['mensaje']]);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }

    public static function actualizarVendedorPorId($id)
    {
        // Obtenemos los datos enviados en el cuerpo de la solicitud PUT
        $datos = json_decode(file_get_contents("php://input"), true);

        try {
            // Verificamos si se recibieron datos para actualizar
            if (empty($datos)) {
                // Si no se recibieron datos para actualizar, retornar un JSON con un mensaje de error
                echo json_encode(['error' => 'No se recibieron datos para actualizar']);
                return;
            }

            // Encontramos el vendedor por ID, no hace falta instanciarlo, así que lo llamamos como estático
            $vendedor = VendedorModel::find($id);

            // Verificamos si se encontró el vendedor por su ID
            if ($vendedor) {
                // Validamos los datos del vendedor PARAMETRIZADO utilizando el método checkDatos del modelo VendedorModel
                $alertas = VendedorModel::checkDatos($datos);

                if (!empty($alertas['error'])) {
                    // Si hay errores, retornar un JSON con los mensajes de error
                    echo json_encode(['error' => $alertas['error']]);
                    return;
                }

                // Actualizamos los campos del vendedor con los datos proporcionados
                foreach ($datos as $campo => $valor) {
                    // Verificamos si el campo existe en el modelo y actualizar su valor
                    if (property_exists($vendedor, $campo)) {
                        $vendedor->{$campo} = $valor;
                    }
                }

                // Intentamos guardar los cambios en el vendedor
                $resultado = $vendedor->guardar();

                // Verificamos si la operación de actualización fue exitosa
                if ($resultado['resultado']) {
                    // Si la actualización fue exitosa, retornar un JSON con un mensaje de éxito
                    echo json_encode(['mensaje' => 'Vendedor actualizado correctamente']);
                } else {
                    // Si ocurrió un error al actualizar el vendedor, retornar un JSON con un mensaje de error específico
                    echo json_encode(['error' => $resultado['mensaje']]);
                }
            } else {
                // Si no se encontró el vendedor por su ID, retornar un JSON con un mensaje de error
                echo json_encode(['error' => 'No se encontro el vendedor con el ID proporcionado']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }


    public static function eliminarVendedorPorId($id)
    {
        // Creamos un objeto VendedorModel para el vendedor a eliminar
        $vendedor = VendedorModel::find($id);

        try {
            //Verificamos si se encontró el vendedor por su ID
            if ($vendedor) {
                // Intentamos eliminar el vendedor
                $resultado = $vendedor->eliminar();

                // Verificamos si la operación de eliminación fue exitosa
                if ($resultado) {
                    // Si la eliminación fue exitosa, retornar un JSON con un mensaje de éxito
                    echo json_encode(['mensaje' => 'Vendedor eliminado correctamente']);
                } else {
                    // Si ocurrió un error al eliminar el vendedor, retornar un JSON con un mensaje de error específico
                    echo json_encode(['error' => 'Error al eliminar el vendedor']);
                }
            } else {
                // Si no se encontró el vendedor por su ID, retornar un JSON con un mensaje de error
                echo json_encode(['error' => 'No se encontro el vendedor con el ID proporcionado']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }
}