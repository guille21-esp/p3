<?php
session_start();
require_once 'dbgestion/sqlDatabase.php';

if (!isset($_SESSION['idCliente'])) {
    header('Location: login.php');
    exit;
}

$idCliente = $_SESSION['id_cliente'];
$conn = Database::getInstancia()->getConexion();

// Obtener carrito
$stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas WHERE ID_Cliente = ?");
$stmt->execute([$idCliente]);
$row = $stmt->fetch();
$idCarrito = $row ? $row['ID_Carrito'] : null;

if (!$idCarrito) {
    echo "No tienes productos en el carrito";
    exit;
}

// Calcular total y cantidad
$stmt = $conn->prepare("SELECT SUM(Precio * Cantidad) AS Total, SUM(Cantidad) AS CantidadTotal FROM Detalle_Carrito WHERE ID_Carrito = ?");
$stmt->execute([$idCarrito]);
$resumen = $stmt->fetch();

// Actualizar carrito con valores calculados
$stmt = $conn->prepare("UPDATE Carrito_Ventas SET Total = ?, Cantidad_Productos = ? WHERE ID_Carrito = ?");
$stmt->execute([$resumen['Total'], $resumen['CantidadTotal'], $idCarrito]);

// Mostrar mensaje, lo podemos hacer en un gracias.php o similar
echo "<h2>¡Compra finalizada con éxito!</h2>";
echo "<p>Total: " . number_format($resumen['TOTAL'], 2) . "€</p>";
echo "<a href='catalogo.php'>Volver a la tienda</a>";

// FALTARÍA REDUCIR EL STOCK AL FINALIZAR LA COMPRA
// CREAR TABLAS DE VENTAS (CREO QUE PA ESTA PRÁCTICA TMP HARÍA FALTA)

?>