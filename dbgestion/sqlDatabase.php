<?php
require_once 'dbgestion/sqlCreds.php';

class Database {
    private static $instancia = null;
    private $conexion; // ESTO ES EL DATO PDO QUE ES LA CONEXIÓN A LA BBDD

    private function __construct() {
        try {
            // Cadena de conexión para MySQL
            $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
            
            // Opciones de configuración PDO
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => true // Conexiones persistentes
            ];
            
            $this->conexion = new PDO($dsn, DBUSER, DBPWD, $options);
            
        } catch (PDOException $e) {
            die("Error de conexión a MySQL: " . $e->getMessage());
        }
    }

    // Devuelve la instancia única (patrón Singleton)
    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    // Devuelve la conexión PDO activa
    public function getConexion() {
        return $this->conexion;
    }

    // Cierra la conexión al destruir la instancia
    public function __destruct() {
        $this->conexion = null;
    }
}