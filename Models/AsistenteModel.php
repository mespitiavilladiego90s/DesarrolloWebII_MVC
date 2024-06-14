<?php
require_once './includes/ActiveRecord.php';
require_once './Models/UsuarioModel.php';
require_once './Models/ReunionModel.php';

class AsistenteModel extends ActiveRecord
{

    // Base de datos
    protected static $tabla = 'asistentes';
    protected static $columnasDB = ['id', 'reunion_id','usuario_id'];

    public $id;
    public $reunion_id;
    public $usuario_id;

    public static $alertas = [
        'error' => [],
        'success' => []
    ];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->reunion_id = $args['reunion_id'] ?? '';
        $this->usuario_id = $args['usuario_id'] ?? '';
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = ['error' => [], 'success' => []];


        // Validación de reunión
        if (empty($this->reunion_id) || !is_numeric($this->reunion_id) || !ReunionModel::where('id', $this->reunion_id) || $this->reunion_id <= 0) {
            self::$alertas['error'][] = 'El ID de la reunión debe ser una ID válida';
        }

        // Validación de usuario
        if (empty($this->usuario_id) || !is_numeric($this->usuario_id) || !UsuarioModel::where('id', $this->usuario_id) || $this->usuario_id <= 0) {
            self::$alertas['error'][] = 'El ID del usuario debe ser una ID válida';
        }

        if(AsistenteModel::getCampoPorId($this->id, 'usuario_id') === $this->usuario_id ){
            self::$alertas['error'][] = 'No puedes registrar el mismo usuario más de una vez como asistente por reunión';
        }

        return self::$alertas;
    }
    public static function obtenerTodosAsistentes()
    {
        $asistentes = self::all();
        return array_map(function ($asistente) {
            return [
                'id' => $asistente->id,
                'reunion_id' => $asistente->reunion_id,
                'usuario_id' => $asistente->usuario_id
            ];
        }, $asistentes);
    }
}
