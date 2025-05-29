<?php
session_start();
require_once 'dbgestion/sqlDatabase.php';

// Redirigir invitados a login
if (!isset($_SESSION['idCliente'])) {
    $_SESSION['checkout_redirect'] = true;
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
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Compra fallida</title>
        <link rel="stylesheet" href="css/styles_tienda.css">
    </head>
    <body>
        <?php include 'elementos/header.php'; ?>
        <main>
            <h2>No tienes productos en el carrito</h2>
            <a href="catalogo.php">Volver a la tienda</a>
        </main>
        <?php include 'elementos/footer.php'; ?>
    </body>
    </html>
    HTML;
    exit;
}

// Calcular total y cantidad
$stmt = $conn->prepare("SELECT SUM(Precio * Cantidad) AS Total, SUM(Cantidad) AS CantidadTotal FROM Detalle_Carrito WHERE ID_Carrito = ?");
$stmt->execute([$idCarrito]);
$resumen = $stmt->fetch();

// Actualizar carrito con valores calculados
$stmt = $conn->prepare("UPDATE Carrito_Ventas SET Total = ?, Cantidad_Productos = ? WHERE ID_Carrito = ?");
$stmt->execute([$resumen['Total'], $resumen['CantidadTotal'], $idCarrito]);

// Vaciar carrito (Detalle_Carrito)
$stmt = $conn->prepare("DELETE FROM Detalle_Carrito WHERE ID_Carrito = ?");
$stmt->execute([$idCarrito]);

// Resetear totales del carrito
$stmt = $conn->prepare("UPDATE Carrito_Ventas SET Total = 0, Cantidad_Productos = 0 WHERE ID_Carrito = ?");
$stmt->execute([$idCarrito]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra exitosa</title>
    <link rel="stylesheet" href="css/styles_tienda.css">
</head>
<body>
    <?php include 'elementos/header.php'; ?>

    <main class="contenedor-centro">
        <section class="mensaje-compra">
        <h2>¡Compra finalizada con éxito!</h2>
        <p>Total: <?= number_format($resumen['Total'], 2) ?>€</p>
        <p>Cantidad de productos: <?= $resumen['CantidadTotal'] ?></p>

        <a href="catalogo.php">Volver a la tienda</a>
        </section>
    </main>

    <?php include 'elementos/footer.php'; ?>

    <!-- Vaciar carrito en JS -->
    <script>
        fetch('modificar_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                accion: 'vaciar'
            })
        }).then(response => {
            if (!response.ok) {
                console.error('Error al vaciar el carrito');
            }
        });
    </script>
</body>
</html>
