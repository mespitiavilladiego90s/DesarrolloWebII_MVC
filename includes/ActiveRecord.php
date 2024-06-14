<?php

require_once 'includes/db.php';

class ActiveRecord
{
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];
    protected $id;

    protected static $alertas = [];

    public static function setAlerta($tipo, $mensaje)
    {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Validación
    public static function getAlertas()
    {
        return static::$alertas;
    }

    public static function setDB($database)
    {
        self::$db = $database;
    }

    public static function consultarSQL($query)
    {
        $resultado = self::$db->query($query);
        $array = [];
        while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $array[] = static::crearObjeto($registro);
        }
        return $array;
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->quote($value);
        }
        return $sanitizado;
    }

    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    public function guardar()
    {
        $resultado = null;
        if (!is_null($this->id)) {
            $resultado = $this->actualizar();
        } else {
            $resultado = $this->crear();
        }
        return $resultado;
    }

    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public function crear()
    {
        $atributos = $this->sanitizarAtributos();
        $columnas = implode(', ', array_keys($atributos));
        $valores = implode(', ', array_values($atributos));
        $query = "INSERT INTO " . static::$tabla . " ($columnas) VALUES ($valores)";
        $resultado = self::$db->query($query);
        return [
            'resultado' => $resultado,
            'id' => self::$db->lastInsertId(),
            'mensaje' => $resultado ? '' : 'Error al crear el registro'
        ];
    }

    // Busca un registro por un parámetro
    public static function where($columna, $valor)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE $columna = :valor LIMIT 1";
        // Preparar la consulta
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(':valor', $valor);
        // Ejecutar la consulta
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra un registro, crear una instancia de la clase heredada
        if ($resultado) {
            $obj = new static;
            $obj->sincronizar($resultado);
            return $obj;
        }

        return null;
    }

    public static function allWhere($columna, $valor)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE $columna = :valor";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($registro) {
            return static::crearObjeto($registro);
        }, $resultados);
    }
    
    public static function getCampoPorId($id, $campo)
    {
        // Validar que el campo solicitado existe en la tabla para evitar inyección SQL
        $query = "SELECT COLUMN_NAME 
              FROM INFORMATION_SCHEMA.COLUMNS 
              WHERE TABLE_NAME = :tabla AND COLUMN_NAME = :campo";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(':tabla', static::$tabla);
        $stmt->bindParam(':campo', $campo);
        $stmt->execute();
        $columnaValida = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$columnaValida) {
            throw new Exception("El campo especificado no existe en la tabla.");
        }

        // Preparar la consulta para obtener el valor del campo específico
        $query = "SELECT $campo FROM " . static::$tabla . " WHERE id = :id LIMIT 1";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(':id', $id);

        // Ejecutar la consulta
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra un registro, retornar el valor del campo solicitado
        if ($resultado) {
            return $resultado[$campo];
        }

        return null;
    }

    // Método para verificar si el usuario es asistente de la reunión
    public static function verificarUsuarioAsistente($acta_id, $responsable_id)
    {
        // Obtener reunion_id de la tabla actas
        $query = "SELECT reunion_id FROM actas WHERE id = :acta_id";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(':acta_id', $acta_id);
        $stmt->execute();
        $reunion_id = $stmt->fetchColumn();

        if ($reunion_id === false) {
            return false;
        }

        // Verificar si el reunion_id obtenido de actas es el mismo que en la tabla asistentes para el usuario
        $query = "SELECT COUNT(*) FROM asistentes WHERE usuario_id = :responsable_id AND reunion_id = :reunion_id";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(':responsable_id', $responsable_id);
        $stmt->bindParam(':reunion_id', $reunion_id);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    public function actualizar()
    {
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "$key=$value";
        }
        $query = "UPDATE " . static::$tabla . " SET " . implode(', ', $valores) . " WHERE id = " . self::$db->quote($this->id);
        $resultado = self::$db->query($query);
        return [
            'resultado' => $resultado,
            'mensaje' => $resultado ? '' : 'Error al actualizar el registro'
        ];
    }

    public function eliminar()
    {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->quote($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = " . self::$db->quote($id) . " LIMIT 1";
        $resultado = self::consultarSQL($query);
        return !empty($resultado) ? $resultado[0] : null;
    }
}
