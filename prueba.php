

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'dbgestion/Database.php';

try {
    echo "<h1>Probando conexión a Oracle</h1>";

    // Obtener la instancia de la base de datos
    $db = Database::getInstancia();
    $conexion = $db->getConexion();
    
    echo "<p>Conexion establecida</p>";
    // Consulta simple para verificar la conexión
    $query = "SELECT * FROM dual";
    $stid = oci_parse($conexion, $query);
    
    if (!$stid) {
        $error = oci_error($conexion);
        throw new Exception("Error al preparar la consulta: " . $error['message']);
    }
    
    $resultado = oci_execute($stid);
    
    if (!$resultado) {
        $error = oci_error($stid);
        throw new Exception("Error al ejecutar la consulta: " . $error['message']);
    }
    
    echo "<h2>Conexión exitosa a la base de datos Oracle</h2>";
    echo "<p>Se ha conectado correctamente a la base de datos.</p>";
    
    // Mostrar resultados si la consulta devuelve datos
    while ($fila = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<pre>";
        print_r($fila);
        echo "</pre>";
    }
    
    oci_free_statement($stid);
    
} catch (Exception $e) {
    echo "<h2>Error en la conexión</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>