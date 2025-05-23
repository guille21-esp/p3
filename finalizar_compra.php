<?php
session_start();
require_once 'dbgestion/sqlDatabase.php';

if (!isset($_SESSION['idCliente'])) {
    header('Location: login.php');
    exit;
}

$idCliente = $_SESSION['idCliente'];
$conn = Database::getInstancia()->getConexion();

// Obtener carrito
$stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas WHERE ID_Cliente = ?");
$stmt->execute([$idCliente]);
$row = $stmt->fetch();
$idCarrito = $row ? $row['ID_Carrito'] : null;

if (!$idCarrito) {
    echo "<h2>No tienes productos en el carrito</h2>";
    echo "<a href='catalogo.php'>Volver a la tienda</a>";
    exit;
}

// Calcular total y cantidad
$stmt = $conn->prepare("SELECT SUM(Precio * Cantidad) AS Total, SUM(Cantidad) AS CantidadTotal FROM Detalle_Carrito WHERE ID_Carrito = ?");
$stmt->execute([$idCarrito]);
$resumen = $stmt->fetch();

// Actualizar carrito con valores calculados
$stmt = $conn->prepare("UPDATE Carrito_Ventas SET Total = ?, Cantidad_Productos = ? WHERE ID_Carrito = ?");
$stmt->execute([$resumen['Total'], $resumen['CantidadTotal'], $idCarrito]);

//Vaciar carrito, es decir, eliminar cada Detalle_Carrito s(sin eliminar el registro del carrito en sí)
$stmt = $conn->prepare("DELETE FROM Detalle_Carrito WHERE ID_Carrito = ?");
$stmt->execute([$idCarrito]);

// Resetear los totales del carrito también
$stmt = $conn->prepare("UPDATE Carrito_Ventas SET Total = 0, Cantidad_Productos = 0 WHERE ID_Carrito = ?");
$stmt->execute([$idCarrito]);

echo "<h2>¡Compra finalizada con éxito!</h2>";
echo "<p>Total: " . number_format($resumen['Total'], 2) . "€</p>";
echo "<p>Cantidad de productos: " . $resumen['CantidadTotal'] . "</p>";
// Inyección del JS para vaciar el carrito en segundo plano
echo <<<HTML
<script>
    fetch('modificar_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            accion: 'vaciar'
        })
    })
    .then(response => {
        if (!response.ok) {
            console.error('Error al vaciar el carrito');
        }
    });
</script>
HTML;

echo "<a href='catalogo.php'>Volver a la tienda</a>";

// FALTARÍA REDUCIR EL STOCK AL FINALIZAR LA COMPRA
// CREAR TABLAS DE VENTAS (CREO QUE PA ESTA PRÁCTICA TMP HARÍA FALTA)

?>