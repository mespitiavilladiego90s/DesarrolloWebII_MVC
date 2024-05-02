<?php
require_once './Models/FacturaModel.php';
require_once './includes/ActiveRecord.php';

class FacturaController
{
    public static function obtenerFacturaPorId($id)
    {
        $factura = FacturaModel::find($id);

        try {
            // Verificamos si se encontró la orden mediante la consulta
            if ($factura) {
                echo json_encode($factura);
            } else {
                // Si no se encontró la orden, retornar un JSON con el mensaje 'No se encontró por ID'
                echo json_encode(['mensaje' => 'No se encontro por ID']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }

    public static function crearFactura()
    {
        // Obtenemos los datos enviados en el cuerpo de la solicitud POST
        $datos = json_decode(file_get_contents("php://input"), true);

        try {
            // Validamos los datos de la orden utilizando el método validarDatos del modelo FacturalModel
            $alertas = FacturaModel::validarDatos($datos);

            if (!empty($alertas['error'])) {
                // Si hay errores, retornar un JSON con los mensajes de error
                echo json_encode(['error' => $alertas['error']]);
                return;
            }

            // Creamos un modelo de cada uno de los datos que vienen desde el POST para luego intentar buscar por ID si existen o no, esto con el objetivo de agregar datos que ÚNICAMENTE existan en las tablas.
            $cliente = ClienteModel::find($datos['id_cliente']);
            $vendedor = VendedorModel::find($datos['id_vendedor']);
            $orden = OrdenModel::find($datos['id_ordengasolina']);

            // Si de manera exitosa obtenemos que existe un cliente/vendedor/orden con esas IDs, procedemos con el resto del código.
            if ($cliente && $vendedor && $orden) {
                // Creamos un nuevo objeto FacturaModel con los datos proporcionados
                $nuevaFactura = new FacturaModel($datos);

                // Intentamos guardar la nueva factura en la base de datos
                $resultado = $nuevaFactura->guardar();

                // Verificamos si la operación de guardado fue exitosa
                if ($resultado['resultado']) {
                    // Si la factura se creó exitosamente, retornar un JSON con el ID de la nueva factura
                    echo json_encode(['Creado' => $resultado['id']]);
                } else {
                    // Si ocurrió un error al crear la factura, retornar un JSON con un mensaje de error específico
                    echo json_encode(['error' => $resultado['mensaje']]);
                }
            } else {
                echo json_encode(['error' => 'Alguno de los parametros ingresados no concuerda con las IDs en la tabla']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }

    public static function actualizarFacturaPorId($id)
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

            // Encontramos la factura por ID, no hace falta instanciarlo, así que lo llamamos como estático
            $factura = FacturaModel::find($id);

            // Verificamos si se encontró la factura por su ID y también los demás
            if ($factura) {
                // Validamos los datos de la orden PARAMETRIZADO utilizando el método checkDatos del modelo FacturaModel
                $alertas = FacturaModel::checkDatos($datos);

                if (!empty($alertas['error'])) {
                    // Si hay errores, retornar un JSON con los mensajes de error
                    echo json_encode(['error' => $alertas['error']]);
                    return;
                }

                // Actualizamos los campos de la factura con los datos proporcionados
                foreach ($datos as $campo => $valor) {
                    // Verificamos si el campo existe en el modelo y actualizar su valor
                    if (property_exists($factura, $campo)) {
                        $factura->{$campo} = $valor;
                    }
                }

                // Intentamos guardar los cambios en la factura
                $resultado = $factura->guardar();

                // Verificamos si la operación de actualización fue exitosa
                if ($resultado['resultado']) {
                    // Si la actualización fue exitosa, retornar un JSON con un mensaje de éxito
                    echo json_encode(['mensaje' => 'Factura actualizada correctamente']);
                } else {
                    // Si ocurrió un error al actualizar la factura, retornar un JSON con un mensaje de error específico
                    echo json_encode(['error' => $resultado['mensaje']]);
                }
            } else {
                // Si no se encontró la factura, cliente, vendedor u orden por su ID, retornar un JSON con un mensaje de error
                echo json_encode(['error' => 'El ID de factura proporcionado NO es correcto.']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }


    public static function eliminarFacturaPorId($id)
    {
        // Creamos un objeto FacturaModel para la factura a eliminar
        $factura = FacturaModel::find($id);

        try {
            // Verificamos si se encontró la factura por su ID
            if ($factura) {
                // Intentamos eliminar la factura
                $resultado = $factura->eliminar();

                // Verificamos si la operación de eliminación fue exitosa
                if ($resultado) {
                    // Si la eliminación fue exitosa, retornar un JSON con un mensaje de éxito
                    echo json_encode(['mensaje' => 'Factura eliminada correctamente']);
                } else {
                    // Si ocurrió un error al eliminar la factura, retornar un JSON con un mensaje de error específico
                    echo json_encode(['error' => 'Error al eliminar la factura']);
                }
            } else {
                // Si no se encontró la factura por su ID, retornar un JSON con un mensaje de error
                echo json_encode(['error' => 'No se encontro la factura con el ID proporcionado']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }
}
