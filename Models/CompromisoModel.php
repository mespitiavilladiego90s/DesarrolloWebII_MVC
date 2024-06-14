<?php
require_once './includes/ActiveRecord.php';
require_once './Models/UsuarioModel.php';
require_once './Models/ActaModel.php';
require_once './Models/AsistenteModel.php';
require_once './Models/ReunionModel.php';

class CompromisoModel extends ActiveRecord
{

    // Base de datos
    protected static $tabla = 'compromisos';
    protected static $columnasDB = ['id', 'acta_id', 'descripcion', 'responsable_id', 'fecha_entrega', 'estado'];

    public $id;
    public $acta_id;
    public $descripcion;
    public $responsable_id;
    public $fecha_entrega;
    public $estado;



    public static $alertas = [
        'error' => [],
        'success' => []
    ];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->acta_id = $args['acta_id'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->responsable_id = $args['responsable_id'] ?? '';
        $this->fecha_entrega = $args['fecha_entrega'] ?? '';
        $this->estado = $args['estado'] ?? 'asignado';
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = ['error' => [], 'success' => []];


        // Validación de acta
        if (empty($this->acta_id) || !is_numeric($this->acta_id) || !ActaModel::where('id', $this->acta_id) || $this->acta_id <= 0) {
            self::$alertas['error'][] = 'El ID del acta asociado debe ser una ID válida';
        }

        // Validación de usuario
        if (empty($this->responsable_id) || !is_numeric($this->responsable_id) || !UsuarioModel::where('id', $this->responsable_id) || $this->responsable_id <= 0) {
            self::$alertas['error'][] = 'El ID del usuario debe ser una ID válida';
        }


        // Validación usuario como asistente a reunión
        if (!ActiveRecord::verificarUsuarioAsistente($this->acta_id, $this->responsable_id)) {
            self::$alertas['error'][] = 'El usuario no es asistente de la reunión asociada al acta.';
        }

        // Validación de descripción
        if (empty($this->descripcion)) {
            self::$alertas['error'][] = 'La descripción debe ser válida.';
        }

        // Validación del estado
        if (empty($this->estado) || !is_string($this->estado) || ($this->estado != 'creado' && $this->estado != 'asignado')) {
            self::$alertas['error'][] = 'El estado del compromiso es obligatorio';
        }



        // Validación de fecha
        if (empty($this->fecha_entrega)) {
            self::$alertas['error'][] = 'La fecha es obligatoria';
        }
        if (strtotime($this->fecha_entrega) < strtotime(date('Y-m-d'))) {
            self::$alertas['error'][] = 'La fecha no puede ser anterior al día actual';
        }
        if (strtotime($this->fecha_entrega) < strtotime(ReunionModel::getCampoPorId(ActaModel::getCampoPorId($this->acta_id, 'reunion_id'), 'fecha'))) {
            self::$alertas['error'][] = 'La fecha del compromiso no puede ser anterior a la fecha de la reunión';
        }

        return self::$alertas;
    }
    public static function obtenerTodosCompromisos()
    {
        $compromisos = self::all();
        return array_map(function ($compromiso) {
            return [
                'id' => $compromiso->id,
                'acta_id' => $compromiso->acta_id,
                'descripcion' => $compromiso->descripcion,
                'responsable_id' => $compromiso->responsable_id,
                'fecha_entrega' => $compromiso->fecha_entrega,
                'estado' => $compromiso->estado

            ];
        }, $compromisos);
    }
}
