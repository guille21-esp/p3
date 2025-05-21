<?php
require_once 'credentials.php';

class Database {
    private static $instancia = null;
    private $conexion;

    private function __construct(){
        try {
            $cadena_conexion = "(DESCRIPTION =
                                    (ADDRESS = (PROTOCOL = TCP)(HOST = " . DBHOST . ")(PORT = " . DBPORT . "))
                                    (CONNECT_DATA = (SERVICE_NAME = " . SERVICE_NAME . "))
                                    )";

            $this->conexion = oci_connect(DBUSER, DBPWD, $cadena_conexion, 'AL32UTF8');
            
            if (!$this->conexion) {
                $error = oci_error();
                throw new Exception("Error de conexión a Oracle: " . $error['message']);
            }
        } catch (Exception $e) {
            die("Conexión fallida: " . $e->getMessage());
        }
    }

    // Devuelve la instancia única
    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    // Devuelve la conexión Oracle activa
    public function getConexion() {
        return $this->conexion;
    }

    // Cierra la conexión al destruir la instancia
    public function __destruct(){
        if ($this->conexion){
            oci_close($this->conexion);
        }
    }

    
}