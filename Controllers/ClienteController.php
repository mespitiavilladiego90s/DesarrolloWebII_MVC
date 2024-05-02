<?php
require_once './Models/ClienteModel.php';
require_once './includes/ActiveRecord.php';

class ClientController{
    public static function obtenerClientePorId($id)
    {
        $cliente = ClienteModel::find($id);

        try {
            // Verificamos si se encontró el cliente mediante la consulta
            if ($cliente) {
                echo json_encode($cliente);
            } else {
                // Si no se encontró el cliente, retornar un JSON con el mensaje 'No se encontró por ID'
                echo json_encode(['mensaje' => 'No se encontro por ID']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }

    public static function crearCliente()
    {
        // Obtenemos los datos enviados en el cuerpo de la solicitud POST
        $datos = json_decode(file_get_contents("php://input"), true);

        try {
            // Validamos los datos del cliente utilizando el método validarDatos del modelo ClienteModel
            $alertas = ClienteModel::validarDatos($datos);

            if (!empty($alertas['error'])) {
                // Si hay errores, retornar un JSON con los mensajes de error
                echo json_encode(['error' => $alertas['error']]);
                return;
            }

            // Creamos un nuevo objeto VendedorModel con los datos proporcionados
            $nuevoCliente = new ClienteModel($datos);

            // Intentamos guardar el nuevo vendedor en la base de datos
            $resultado = $nuevoCliente->guardar();

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

    public static function actualizarClientePorId($id)
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

            // Encontramos el cliente por ID, no hace falta instanciarlo, así que lo llamamos como estático
            $cliente = ClienteModel::find($id);

            // Verificamos si se encontró el cliente por su ID
            if ($cliente) {
                // Validamos los datos del cliente PARAMETRIZADO utilizando el método checkDatos del modelo ClienteModel
                $alertas = ClienteModel::checkDatos($datos);

                if (!empty($alertas['error'])) {
                    // Si hay errores, retornar un JSON con los mensajes de error
                    echo json_encode(['error' => $alertas['error']]);
                    return;
                }

                // Actualizamos los campos del cliente con los datos proporcionados
                foreach ($datos as $campo => $valor) {
                    // Verificamos si el campo existe en el modelo y actualizar su valor
                    if (property_exists($cliente, $campo)) {
                        $cliente->{$campo} = $valor;
                    }
                }

                // Intentamos guardar los cambios en el cliente
                $resultado = $cliente->guardar();

                // Verificamos si la operación de actualización fue exitosa
                if ($resultado['resultado']) {
                    // Si la actualización fue exitosa, retornar un JSON con un mensaje de éxito
                    echo json_encode(['mensaje' => 'Cliente actualizado correctamente']);
                } else {
                    // Si ocurrió un error al actualizar el Cliente, retornar un JSON con un mensaje de error específico
                    echo json_encode(['error' => $resultado['mensaje']]);
                }
            } else {
                // Si no se encontró el cliente por su ID, retornar un JSON con un mensaje de error
                echo json_encode(['error' => 'No se encontro el cliente con el ID proporcionado']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }


    public static function eliminarClientePorId($id)
    {
        // Creamos un objeto ClienteModel para el cliente a eliminar
        $cliente = ClienteModel::find($id);

        try {
            // Verificamos si se encontró el cliente por su ID
            if ($cliente) {
                // Intentamos eliminar el cliente
                $resultado = $cliente->eliminar();

                // Verificamos si la operación de eliminación fue exitosa
                if ($resultado) {
                    // Si la eliminación fue exitosa, retornar un JSON con un mensaje de éxito
                    echo json_encode(['mensaje' => 'Cliente eliminado correctamente']);
                } else {
                    // Si ocurrió un error al eliminar el cliente, retornar un JSON con un mensaje de error específico
                    echo json_encode(['error' => 'Error al eliminar el cliente']);
                }
            } else {
                // Si no se encontró el cliente por su ID, retornar un JSON con un mensaje de error
                echo json_encode(['error' => 'No se encontro el vendedor con el ID proporcionado']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }
}