<?php

require_once 'includes/ActiveRecord.php';

class ActaModel extends ActiveRecord
{


    // Base de datos
    protected static $tabla = 'actas';
    protected static $columnasDB = ['id', 'asunto', 'fecha_inicio' , 'id_participantes', 'encuentro', 'id_compromisos', 'fecha_finalizacion'];



    public $id;
    public $asunto;
    public $id_participantes;
    public $encuentro;
    public $id_compromisos;

    public $fecha_inicio;
    public $fecha_finalizacion;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->asunto = $args['asunto'] ?? '';
        $this->id_participantes = $args['id_participantes'] ?? '1';
        $this->encuentro = $args['encuentro'] ?? 'presencial';
        $this->id_compromisos = $args['id_compromisos'] ?? '1';
        $this->fecha_inicio = $args['fecha_inicio'] ?? '17-05-24 09:00:00';
        $this->fecha_finalizacion = $args['fecha_finalizacion'] ?? '17-05-24 09:00:00';
    }


    // Mensajes de validación para la creación de una cuenta
    public function validar()
    {
        if (!$this->asunto) {
            self::$alertas['error'][] = 'El Asunto es Obligatorio';
        }
        if (!$this->id_participantes) {
            self::$alertas['error'][] = 'El ID a la tabla de participantes es obligatorio.';
        }
        if (!$this->encuentro) {
            self::$alertas['error'][] = 'El lugar de encuentro es Obligatorio';
        }
        if (!$this->id_compromisos) {
            self::$alertas['error'][] = 'El ID a la tabla de compromisos es obligatorio.';
        }
        if (!$this->fecha_inicio) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if (!$this->fecha_finalizacion) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }



        return self::$alertas;
    }


    
}
