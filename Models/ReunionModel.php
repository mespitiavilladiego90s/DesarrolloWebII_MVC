<?php
require_once './includes/ActiveRecord.php';
require_once './Models/UsuarioModel.php';

class ReunionModel extends ActiveRecord
{

    // Base de datos
    protected static $tabla = 'reunion';
    protected static $columnasDB = ['id', 'id_usuario','fecha', 'hora_inicio', 'hora_fin', 'lugar', 'asunto', 'estado'];

    public $id;
    public $id_usuario;
    public $fecha;
    public $hora_inicio;
    public $hora_fin;
    public $lugar;

    public $asunto;
    public $estado;

    public static $alertas = [
        'error' => [],
        'success' => []
    ];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->hora_inicio = $args['hora_inicio'] ?? '';
        $this->hora_fin = $args['hora_fin'] ?? '';
        $this->lugar = $args['lugar'] ?? '';
        $this->asunto = $args['asunto'] ?? '';
        $this->estado = $args['estado'] ?? 'privada';
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = ['error' => [], 'success' => []];

        // Validación de fecha
        if (empty($this->fecha)) {
            self::$alertas['error'][] = 'La fecha es obligatoria';
        } elseif (strtotime($this->fecha) < strtotime(date('Y-m-d'))) {
            self::$alertas['error'][] = 'La fecha no puede ser anterior al día actual';
        }

        // Validación de usuario
        if (empty($this->id_usuario) || !is_numeric($this->id_usuario) || !UsuarioModel::where('id', $this->id_usuario) || $this->id_usuario <= 0) {
            self::$alertas['error'][] = 'El ID del usuario debe ser una ID válida';
        }

        // Validación de hora de inicio y fin
        if (empty($this->hora_inicio)) {
            self::$alertas['error'][] = 'La hora de inicio es obligatoria';
        } elseif (!DateTime::createFromFormat('H:i:s', $this->hora_inicio) && !DateTime::createFromFormat('H:i', $this->hora_inicio)) {
            self::$alertas['error'][] = 'La hora de inicio no es válida';
        }

        if (empty($this->hora_fin)) {
            self::$alertas['error'][] = 'La hora de fin es obligatoria';
        } elseif (!DateTime::createFromFormat('H:i:s', $this->hora_fin) && !DateTime::createFromFormat('H:i', $this->hora_fin)) {
            self::$alertas['error'][] = 'La hora de fin no es válida';
        }

        // Validación de que la hora de inicio y fin no sean iguales
        if ($this->hora_inicio === $this->hora_fin) {
            self::$alertas['error'][] = 'La hora de inicio y fin no pueden ser iguales';
        }

        // Validación de que la hora de fin sea posterior a la hora de inicio
        if (strtotime($this->hora_fin) <= strtotime($this->hora_inicio)) {
            self::$alertas['error'][] = 'La hora de fin debe ser posterior a la hora de inicio';
        }

        // Validación de que la hora de inicio no sea anterior a la hora actual
        if (strtotime($this->hora_inicio) <= strtotime(date('H:i:s'))) {
            self::$alertas['error'][] = 'La hora de inicio no debe ser anterior a la hora actual';
        }

        // Validación de que la hora de inicio no sea igual a la hora actual
        if ($this->hora_inicio === date('H:i:s')) {
            self::$alertas['error'][] = 'La hora de inicio y la actual no pueden ser iguales';
        }

        // Validación del lugar
        if (empty($this->lugar) || !is_string($this->lugar)) {
            self::$alertas['error'][] = 'El lugar de la reunión es obligatorio';
        }

        // Validación del asunto
        if (empty($this->asunto) || !is_string($this->asunto)) {
            self::$alertas['error'][] = 'El asunto de la reunión es obligatorio';
        }

        // Validación del estado
        if (empty($this->estado) || !is_string($this->estado) || ($this->estado != 'pública' && $this->estado != 'privada')) {
            self::$alertas['error'][] = 'El estado de la reunión es obligatorio';
        }

        return self::$alertas;
    }
    public static function obtenerTodasReuniones()
    {
        $reuniones = self::all();
        return array_map(function ($reunion) {
            return [
                'id' => $reunion->id,
                'id_usuario' => $reunion->id_usuario,
                'fecha' => $reunion->fecha,
                'hora_inicio' => $reunion->hora_inicio,
                'hora_fin' => $reunion->hora_fin,
                'lugar' => $reunion->lugar,
                'asunto' => $reunion->asunto,
                'estado' => $reunion->estado
            ];
        }, $reuniones);
    }
}
