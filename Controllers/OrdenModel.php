<?php
require_once './Models/OrdenModel.php';
require_once './includes/ActiveRecord.php';

class OrdenController
{
    public static function obtenerOrdenPorId($id)
    {
        $orden = OrdenModel::find($id);

        try {
            // Verificamos si se encontró la orden mediante la consulta
            if ($orden) {
                echo json_encode($orden);
            } else {
                // Si no se encontró la orden, retornar un JSON con el mensaje 'No se encontró por ID'
                echo json_encode(['mensaje' => 'No se encontro por ID']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }

    public static function crearOrden()
    {
        // Obtenemos los datos enviados en el cuerpo de la solicitud POST
        $datos = json_decode(file_get_contents("php://input"), true);

        try {
            // Validamos los datos de la orden utilizando el método validarDatos del modelo OrdenModel
            $alertas = OrdenModel::validarDatos($datos);

            if (!empty($alertas['error'])) {
                // Si hay errores, retornar un JSON con los mensajes de error
                echo json_encode(['error' => $alertas['error']]);
                return;
            }

            // Creamos un nuevo objeto OrdenModel con los datos proporcionados
            $nuevaOrden = new OrdenModel($datos);

            // Intentamos guardar el nuevo vendedor en la base de datos
            $resultado = $nuevaOrden->guardar();

            // Verificamos si la operación de guardado fue exitosa
            if ($resultado['resultado']) {
                // Si la orden se creó exitosamente, retornar un JSON con el ID de la nueva orden
                echo json_encode(['Creado' => $resultado['id']]);
            } else {
                // Si ocurrió un error al crear la orden, retornar un JSON con un mensaje de error específico
                echo json_encode(['error' => $resultado['mensaje']]);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }

    public static function actualizarOrdenPorId($id)
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

            // Encontramos la orden por ID, no hace falta instanciarlo, así que lo llamamos como estático
            $orden = OrdenModel::find($id);

            // Verificamos si se encontró la orden por su ID
            if ($orden) {
                // Validamos los datos de la orden PARAMETRIZADO utilizando el método checkDatos del modelo OrdenModel
                $alertas = OrdenModel::checkDatos($datos);

                if (!empty($alertas['error'])) {
                    // Si hay errores, retornar un JSON con los mensajes de error
                    echo json_encode(['error' => $alertas['error']]);
                    return;
                }

                // Actualizamos los campos de la orden con los datos proporcionados
                foreach ($datos as $campo => $valor) {
                    // Verificamos si el campo existe en el modelo y actualizar su valor
                    if (property_exists($orden, $campo)) {
                        $orden->{$campo} = $valor;
                    }
                }

                // Intentamos guardar los cambios en la orden
                $resultado = $orden->guardar();

                // Verificamos si la operación de actualización fue exitosa
                if ($resultado['resultado']) {
                    // Si la actualización fue exitosa, retornar un JSON con un mensaje de éxito
                    echo json_encode(['mensaje' => 'Orden actualizada correctamente']);
                } else {
                    // Si ocurrió un error al actualizar la orden, retornar un JSON con un mensaje de error específico
                    echo json_encode(['error' => $resultado['mensaje']]);
                }
            } else {
                // Si no se encontró la orden por su ID, retornar un JSON con un mensaje de error
                echo json_encode(['error' => 'No se encontro la orden con el ID proporcionado']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }


    public static function eliminarOrdenPorId($id)
    {
        // Creamos un objeto OrdenModel para la orden a eliminar
        $orden = OrdenModel::find($id);

        try {
            // Verificamos si se encontró la orden por su ID
            if ($orden) {
                // Intentamos eliminar la orden
                $resultado = $orden->eliminar();

                // Verificamos si la operación de eliminación fue exitosa
                if ($resultado) {
                    // Si la eliminación fue exitosa, retornar un JSON con un mensaje de éxito
                    echo json_encode(['mensaje' => 'Orden eliminada correctamente']);
                } else {
                    // Si ocurrió un error al eliminar la orden, retornar un JSON con un mensaje de error específico
                    echo json_encode(['error' => 'Error al eliminar la orden']);
                }
            } else {
                // Si no se encontró la orden por su ID, retornar un JSON con un mensaje de error
                echo json_encode(['error' => 'No se encontro la orden con el ID proporcionado']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }
}
