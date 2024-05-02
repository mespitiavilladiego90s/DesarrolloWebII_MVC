<?php

require_once 'includes/ActiveRecord.php';

class FacturaModel extends ActiveRecord {

    protected static $tabla = 'Factura';
    protected static $columnasDB = ['id', 'id_cliente', 'id_vendedor', 'id_ordengasolina', 'fecha_compra'];

    public $id;
    public $id_cliente;
    public $id_vendedor;
    public $id_ordengasolina;
    public $fecha_compra;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_cliente = $args['id_cliente'] ?? '';
        $this->id_vendedor = $args['id_vendedor'] ?? '';
        $this->id_ordengasolina = $args['id_ordengasolina'] ?? '';
        $this->fecha_compra = $args['fecha_compra'] ?? '';
    }

    public static function validarDatos($datos) {
        $alertas = [];
    
        // Validamos los tipos de datos de los campos y sus restricciones
        if (!is_numeric($datos['id_cliente']) || $datos['id_cliente'] <= 0) {
            $alertas['error'][] = 'El id del cliente debe ser un valor numerico mayor que cero.';
        }
    
        if (!is_numeric($datos['id_vendedor']) || $datos['id_vendedor'] <= 0) {
            $alertas['error'][] = 'El id del vendedor debe ser un valor numerico mayor que cero.';
        }

        if (!is_numeric($datos['id_ordengasolina']) || $datos['id_ordengasolina'] <= 0) {
            $alertas['error'][] = 'El id de la orden de gasolina debe ser un valor numerico mayor que cero.';
        }
    
        if (!DateTime::createFromFormat('Y-m-d H:i:s', $datos['fecha_compra']) || !strtotime($datos['fecha_compra'])) {
            $alertas['error'][] = 'La hora de la factura debe tener el formato YYYY-MM-DD HH:MM:SS.';
        }
    
        
    
        return $alertas;
    }
    

    public static function checkDatos($datos) {
        $alertas = [];
        
        // Realizar consultas a través de los modelos si los datos están presentes
        if (isset($datos['id_cliente'])) {
            $cliente = ClienteModel::find($datos['id_cliente']);
            if (!$cliente) {
                $alertas['error'][] = "El cliente con ID {$datos['id_cliente']} no existe.";
            }
        }
    
        if (isset($datos['id_vendedor'])) {
            $vendedor = VendedorModel::find($datos['id_vendedor']);
            if (!$vendedor) {
                $alertas['error'][] = "El vendedor con ID {$datos['id_vendedor']} no existe.";
            }
        }
    
        if (isset($datos['id_ordengasolina'])) {
            $orden = OrdenModel::find($datos['id_ordengasolina']);
            if (!$orden) {
                $alertas['error'][] = "La orden de gasolina con ID {$datos['id_ordengasolina']} no existe.";
            }
        }
        
        // Listamos los campos permitidos y sus reglas de validación para la tabla factura
        $camposValidos = [
            'id_cliente' => ['tipo' => 'numeric', 'valor_minimo' => 0],
            'id_vendedor' => ['tipo' => 'numeric', 'valor_minimo' => 0],
            'id_ordengasolina' => ['tipo' => 'numeric', 'valor_minimo' => 0],
            'fecha_compra' => ['tipo' => 'fecha']
        ];
        
        // Verificamos la existencia y validamos cada campo presente en los datos
        foreach ($datos as $campo => $valor) {
            if (isset($camposValidos[$campo])) {
                switch ($camposValidos[$campo]['tipo']) {
                    case 'numeric':
                        if (!is_numeric($valor) || $valor <= $camposValidos[$campo]['valor_minimo']) {
                            $alertas['error'][] = "El campo '$campo' debe ser un valor numerico mayor que {$camposValidos[$campo]['valor_minimo']}.";
                        }
                        break;
                    case 'fecha':
                        if (!DateTime::createFromFormat('Y-m-d H:i:s', $valor) || !strtotime($valor)) {
                            $alertas['error'][] = "El campo '$campo' debe tener el formato YYYY-MM-DD HH:MM:SS.";
                        }
                        break;
                    default:
                        // Manejamos otros tipos de campos si es necesario
                        break;
                }
            } else {
                $alertas['error'][] = "El campo '$campo' no es valido para la tabla Factura.";
            }
        }
        
        return $alertas;
    }
    
    
    
}
