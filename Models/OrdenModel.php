<?php

require_once 'includes/ActiveRecord.php';

class OrdenModel extends ActiveRecord {

    protected static $tabla = 'Ordengasolina';
    protected static $columnasDB = ['id', 'nombre', 'precio', 'hora_orden', 'tiempo_llenado'];

    public $id;
    public $nombre;
    public $precio;
    public $hora_orden;
    public $tiempo_llenado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->hora_orden = $args['hora_orden'] ?? '';
        $this->tiempo_llenado = $args['tiempo_llenado'] ?? '';
    }

    public static function validarDatos($datos) {
        $alertas = [];
    
        // Validamos los tipos de datos de los campos y sus restricciones
        if (!is_string($datos['nombre']) || strlen($datos['nombre']) > 100) {
            $alertas['error'][] = 'El nombre debe ser una cadena con maximo 100 caracteres.';
        }
    
        if (!is_float($datos['precio']) || $datos['precio'] <= 0) {
            $alertas['error'][] = 'El precio debe ser un valor numerico mayor que cero.';
        }
    
        if (!DateTime::createFromFormat('Y-m-d H:i:s', $datos['hora_orden']) || !strtotime($datos['hora_orden'])) {
            $alertas['error'][] = 'La hora de orden debe tener el formato YYYY-MM-DD HH:MM:SS.';
        }
    
        if (!is_float($datos['tiempo_llenado']) || $datos['tiempo_llenado'] <= 0) {
            $alertas['error'][] = 'El tiempo de llenado debe ser un valor numerico mayor que cero.';
        }
    
        return $alertas;
    }
    

    public static function checkDatos($datos) {
        $alertas = [];
        
        // Listamos los campos permitidos y sus reglas de validación para la tabla OrdenGasolina
        $camposValidos = [
            'nombre' => ['tipo' => 'string', 'longitud_maxima' => 100],
            'precio' => ['tipo' => 'float', 'valor_minimo' => 0],
            'hora_orden' => ['tipo' => 'fecha'],
            'tiempo_llenado' => ['tipo' => 'float', 'valor_minimo' => 0]
        ];
        
        // Verificamos la existencia y validamos cada campo presente en los datos
        foreach ($datos as $campo => $valor) {
            if (isset($camposValidos[$campo])) {
                switch ($camposValidos[$campo]['tipo']) {
                    case 'string':
                        if (!is_string($valor) || strlen($valor) > $camposValidos[$campo]['longitud_maxima']) {
                            $alertas['error'][] = "El campo '$campo' debe ser una cadena con maximo {$camposValidos[$campo]['longitud_maxima']} caracteres.";
                        }
                        break;
                    case 'float':
                        if (!is_float($valor) || $valor <= $camposValidos[$campo]['valor_minimo']) {
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
                $alertas['error'][] = "El campo '$campo' no es valido para la tabla OrdenGasolina.";
            }
        }
        
        return $alertas;
    }
    
    
}
