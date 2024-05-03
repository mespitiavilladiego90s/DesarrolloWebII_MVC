<?php

require_once 'includes/db.php';

class ActiveRecord {
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];
    protected $id;

    public static function setDB($database) {
        self::$db = $database;
    }

    public static function consultarSQL($query) {
        $resultado = self::$db->query($query);
        $array = [];
        while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $array[] = static::crearObjeto($registro);
        }
        return $array;
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    public function atributos() {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->quote($value);
        }
        return $sanitizado;
    }

    public function sincronizar($args = []) {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    public function guardar() {
        $resultado = null;
        if (!is_null($this->id)) {
            $resultado = $this->actualizar();
        } else {
            $resultado = $this->crear();
        }
        return $resultado;
    }

    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public function crear() {
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

    public function actualizar() {
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

    public function eliminar() {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->quote($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = " . self::$db->quote($id) . " LIMIT 1";
        $resultado = self::consultarSQL($query);
        return !empty($resultado) ? $resultado[0] : null;
    }
}

