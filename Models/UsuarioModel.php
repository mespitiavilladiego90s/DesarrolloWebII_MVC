<?php
require_once 'includes/ActiveRecord.php';
require_once './Models/ActaModel.php';
require_once './Models/AsistenteModel.php';
require_once './Models/CompromisoModel.php';
require_once './Models/ReunionModel.php';
class UsuarioModel extends ActiveRecord
{


    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'rol', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $rol;

    public $confirmado;
    public $token;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->rol = $args['rol'] ?? 'Default';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }


    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio';
        }
        if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email es Obligatorio o no contiene formato válido.';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }



        return self::$alertas;
    }

    public function validarLogin()
    {
        if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        return self::$alertas;
    }

    public function validarEmail()
    {
        if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public static function existeUsuario($email)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE email = :email";
        $resultado = self::$db->prepare($query);
        $resultado->execute([':email' => $email]);
        $usuario = $resultado->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return new UsuarioModel($usuario);
        } else {
            return false;
        }
    }

    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken()
    {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password)
    {
        $resultado = password_verify($password, $this->password);

        if (!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }

    public static function obtenerTodosUsuarios()
    {
        $personas = self::all();
        return array_map(function ($personas) {
            return [
                'id' => $personas->id,
                'nombre' => $personas->nombre,
                'apellido' => $personas->apellido,
                'email' => $personas->email,
                'telefono' => $personas->telefono,
                'rol' => $personas->rol
            ];
        }, $personas);
    }

    public static function obtenerTodaInfoReunion()
{
    $query = "SELECT 
                r.id AS reunion_id,
                r.asunto,
                r.fecha,
                r.hora_inicio,
                r.hora_fin,
                r.lugar,
                a.id AS asistente_id,
                u.nombre AS asistente_nombre,
                u.apellido AS asistente_apellido,
                act.id AS acta_id,
                act.contenido AS acta_contenido,
                c.id AS compromiso_id,
                c.descripcion AS compromiso_descripcion,
                c.fecha_entrega AS compromiso_fecha_entrega,
                c.estado AS compromiso_estado,
                resp.nombre AS compromiso_responsable_nombre,
                resp.apellido AS compromiso_responsable_apellido
            FROM 
                REUNION r
            LEFT JOIN 
                ASISTENTES a ON r.id = a.reunion_id
            LEFT JOIN 
                USUARIOS u ON a.usuario_id = u.id
            LEFT JOIN 
                ACTAS act ON r.id = act.reunion_id
            LEFT JOIN 
                COMPROMISOS c ON act.id = c.acta_id
            LEFT JOIN 
                USUARIOS resp ON c.responsable_id = resp.id
            ORDER BY 
                r.id, a.id, act.id, c.id";
    
    $resultados = self::$db->query($query);
    $reuniones = [];
    
    while ($fila = $resultados->fetch(PDO::FETCH_ASSOC)) {
        $reunionId = $fila['reunion_id'];
        
        if (!isset($reuniones[$reunionId])) {
            $reuniones[$reunionId] = [
                'asunto' => $fila['asunto'],
                'fecha' => $fila['fecha'],
                'hora_inicio' => $fila['hora_inicio'],
                'hora_fin' => $fila['hora_fin'],
                'lugar' => $fila['lugar'],
                'asistentes' => [],
                'actas' => []
            ];
        }

        // Add asistentes if not already added
        if ($fila['asistente_id']) {
            $asistenteEncontrado = false;
            foreach ($reuniones[$reunionId]['asistentes'] as $asistente) {
                if ($asistente['id'] == $fila['asistente_id']) {
                    $asistenteEncontrado = true;
                    break;
                }
            }
            if (!$asistenteEncontrado) {
                $reuniones[$reunionId]['asistentes'][] = [
                    'id' => $fila['asistente_id'],
                    'nombre' => $fila['asistente_nombre'],
                    'apellido' => $fila['asistente_apellido']
                ];
            }
        }
        
        // Add actas if not already added
        if ($fila['acta_id']) {
            if (!isset($reuniones[$reunionId]['actas'][$fila['acta_id']])) {
                $reuniones[$reunionId]['actas'][$fila['acta_id']] = [
                    'id' => $fila['acta_id'],
                    'contenido' => $fila['acta_contenido'],
                    'compromisos' => []
                ];
            }

            // Add compromisos if not already added
            if ($fila['compromiso_id']) {
                $compromisoEncontrado = false;
                foreach ($reuniones[$reunionId]['actas'][$fila['acta_id']]['compromisos'] as $compromiso) {
                    if ($compromiso['id'] == $fila['compromiso_id']) {
                        $compromisoEncontrado = true;
                        break;
                    }
                }
                if (!$compromisoEncontrado) {
                    $reuniones[$reunionId]['actas'][$fila['acta_id']]['compromisos'][] = [
                        'id' => $fila['compromiso_id'],
                        'descripcion' => $fila['compromiso_descripcion'],
                        'fecha_entrega' => $fila['compromiso_fecha_entrega'],
                        'estado' => $fila['compromiso_estado'],
                        'responsable' => [
                            'nombre' => $fila['compromiso_responsable_nombre'],
                            'apellido' => $fila['compromiso_responsable_apellido']
                        ]
                    ];
                }
            }
        }
    }
    
    return array_values($reuniones);
}


}
