<?php
require_once './includes/ActiveRecord.php';
require_once './Models/ReunionModel.php';

class ActaModel extends ActiveRecord
{

    // Base de datos
    protected static $tabla = 'actas';
    protected static $columnasDB = ['id', 'reunion_id','contenido'];

    public $id;
    public $reunion_id;
    public $contenido;

    public static $alertas = [
        'error' => [],
        'success' => []
    ];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->reunion_id = $args['reunion_id'] ?? '';
        $this->contenido = $args['contenido'] ?? '';
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = ['error' => [], 'success' => []];


        // Validación de reunión
        if (empty($this->reunion_id) || !is_numeric($this->reunion_id) || !ReunionModel::where('id', $this->reunion_id) || $this->reunion_id <= 0) {
            self::$alertas['error'][] = 'El ID de la reunión debe ser una ID válida';
        }

        // Validación de Contenido
        if (empty($this->contenido) || !is_string($this->contenido)) {
            self::$alertas['error'][] = 'El contenido a ingresar debe existir y ser válido';
        }


        return self::$alertas;
    }
    public static function obtenerTodasActas()
    {
        $actas = self::all();
        return array_map(function ($acta) {
            return [
                'id' => $acta->id,
                'reunion_id' => $acta->reunion_id,
                'contenido' => $acta->contenido
            ];
        }, $actas);
    }
}
