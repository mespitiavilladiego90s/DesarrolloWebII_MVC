<?php
require_once './includes/ActiveRecord.php';

class APIController
{
    // --------------------------- OTRAS CONSULTAS -----------------------------
    public static function obtenerClienteVendedorOrdenPorIdFactura($id)
    {
        $consulta = ActiveRecord::obtenerClienteVendedorOrdenPorIdFactura($id);

        try {
            // Verificamos si se encontró la orden mediante la consulta
            if ($consulta) {
                echo json_encode($consulta);
            } else {
                // Si no se encontró la orden, retornar un JSON con el mensaje 'No se encontró por ID'
                echo json_encode(['mensaje' => 'No se encontro por ID']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }

    public static function obtenerFacturasPorOrdenPorIdOrden($id)
    {
        $consulta = ActiveRecord::obtenerFacturasPorOrdenPorIdOrden($id);

        try {
            // Verificamos si se encontró la orden mediante la consulta
            if ($consulta) {
                echo json_encode($consulta);
            } else {
                // Si no se encontró la orden, retornar un JSON con el mensaje 'No se encontró por ID'
                echo json_encode(['mensaje' => 'No se encontro por ID']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
    }
}
