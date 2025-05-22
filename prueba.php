<?php
require_once 'dbgestion/sqlDatabase.php';

// Obtener la conexión
$db = Database::getInstancia();
$conexion = $db->getConexion();

// Ejemplo de consulta
try {
    $stmt = $conexion->prepare("SELECT * FROM Productos WHERE Stock > :stock");
    $stmt->execute(['stock' => 0]);
    $productos = $stmt->fetchAll();
    
    foreach ($productos as $producto) {
        echo $producto['Nombre'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>