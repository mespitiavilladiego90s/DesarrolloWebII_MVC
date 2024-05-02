<?php

require_once 'includes/ActiveRecord.php';

class ClienteModel extends ActiveRecord {

    protected static $tabla = 'Cliente';
    protected static $columnasDB = ['id', 'nombre', 'email', 'direccion', 'telefono', 'ciudad', 'fecha_registro'];

    public $id;
    public $nombre;
    public $email;
    public $direccion;
    public $telefono;
    public $ciudad;
    public $fecha_registro;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->ciudad = $args['ciudad'] ?? '';
        $this->fecha_registro = $args['fecha_registro'] ?? '';
    }

    public static function validarDatos($datos) {
        $alertas = [];

        // Validamos los tipos de datos de los campos y sus longitudes máximas
        if (!is_string($datos['nombre']) || strlen($datos['nombre']) > 100) {
            $alertas['error'][] = 'El nombre debe ser una cadena con maximo 100 caracteres.';
        }
    
        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL) || strlen($datos['email']) > 100) {
            $alertas['error'][] = 'El email debe ser una direccion de correo electronico valida con maximo 100 caracteres.';
        }
    
        if (!is_string($datos['telefono']) || strlen($datos['telefono']) > 20 || !ctype_digit($datos['telefono'])) {
            $alertas['error'][] = 'El telefono debe ser una cadena con maximo 20 caracteres.';
        }
    
        if (!is_string($datos['direccion']) || strlen($datos['direccion']) > 200) {
            $alertas['error'][] = 'La direccion debe ser una cadena de maximo 200 caracteres.';
        }
    
        if (!is_string($datos['ciudad']) || strlen($datos['ciudad']) > 100) {
            $alertas['error'][] = 'La ciudad debe ser una cadena con maximo 100 caracteres.';
        }
    
        if (!DateTime::createFromFormat('Y-m-d', $datos['fecha_registro']) || !strtotime($datos['fecha_registro'])) {
            $alertas['error'][] = 'La fecha de registro debe tener el formato YYYY-MM-DD.';
        }

        return $alertas;
    }

    public static function checkDatos($datos) {
        $alertas = [];
        
        // Listamos los campos permitidos y sus reglas de validación
        $camposValidos = [
            'nombre' => ['tipo' => 'string', 'longitud_maxima' => 100],
            'email' => ['tipo' => 'email', 'longitud_maxima' => 100],
            'telefono' => ['tipo' => 'string_value', 'longitud_maxima' => 20],
            'direccion' => ['tipo' => 'string', 'longitud_maxima' => 200],
            'ciudad' => ['tipo' => 'string', 'longitud_maxima' => 100],
            'fecha_registro' => ['tipo' => 'fecha']
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
                        case 'string_value':
                            if (!is_string($valor) || strlen($valor) > $camposValidos[$campo]['longitud_maxima'] || !ctype_digit($valor)) {
                                $alertas['error'][] = "El campo '$campo' debe ser una cadena numérica con maximo {$camposValidos[$campo]['longitud_maxima']} caracteres.";
                            }
                            break;
                    case 'email':
                        if (!filter_var($valor, FILTER_VALIDATE_EMAIL) || strlen($valor) > $camposValidos[$campo]['longitud_maxima']) {
                            $alertas['error'][] = "El campo '$campo' debe ser una direccion de correo electronico valida con maximo {$camposValidos[$campo]['longitud_maxima']} caracteres.";
                        }
                        break;
                    case 'fecha':
                        if (!DateTime::createFromFormat('Y-m-d', $valor) || !strtotime($valor)) {
                            $alertas['error'][] = "El campo '$campo' debe tener el formato YYYY-MM-DD.";
                        }
                        break;
                    default:
                        // Manejamos otros tipos de campos si es necesario
                        break;
                }
            } else {
                $alertas['error'][] = "El campo '$campo' no es valido.";
            }
        }
        
        return $alertas;
    }

}
