<?php
session_start();
require_once 'Database.php';

if (!isset($_SESSION['id_cliente'])) {
    header('Location: login.php');
    exit;
}

$idCliente = $_SESSION['id_cliente'];
$conn = Database::getInstancia()->getConexion();

// Obtener ID del carrito
$sql = "SELECT ID_Carrito FROM Carrito_Ventas WHERE ID_Cliente = :id_cliente";
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ":id_cliente", $idCliente);
oci_execute($stid);
$row = oci_fetch_assoc($stid);
$idCarrito = $row['ID_CARRITO'] ?? null;

if (!$idCarrito) {
    echo "No hay carrito activo.";
    exit;
}

// Calcular total y cantidad
$sql = "SELECT SUM(Precio * Cantidad) AS Total, SUM(Cantidad) AS CantidadTotal FROM Detalle_Carrito WHERE ID_Carrito = :id_carrito";
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ":id_carrito", $idCarrito);
oci_execute($stid);
$resumen = oci_fetch_assoc($stid);

// Actualizar carrito con valores calculados
$sqlUpdate = "UPDATE Carrito_Ventas SET Total = :total, Cantidad_Productos = :cantidad WHERE ID_Carrito = :id_carrito";
$stid = oci_parse($conn, $sqlUpdate);
oci_bind_by_name($stid, ":total", $resumen['TOTAL']);
oci_bind_by_name($stid, ":cantidad", $resumen['CANTIDADTOTAL']);
oci_bind_by_name($stid, ":id_carrito", $idCarrito);
oci_execute($stid);

// Mostrar mensaje, lo podemos hacer en un gracias.php o similar
echo "<h2>¡Compra finalizada con éxito!</h2>";
echo "<p>Total: " . number_format($resumen['TOTAL'], 2) . "€</p>";
echo "<a href='index.php'>Volver a la tienda</a>";

?>