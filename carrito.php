<?php
session_start();
require_once 'Database.php';

if(!isset($_SESSION['idCliente'])) {
    header('Location: login.php');
    exit;
}

$idCliente = $_SESSION['idCliente'];
try {


    $conn = Database::getInstancia()->getConexion();

    // Obtener el ID del carrito activo del cliente (uno por cliente)
    $sql = 'SELECT ID_CARRITO FROM CARRITO_VENTAS WHERE ID_CLIENTE = :id_cliente';
    $stid = oci_parse($conn, $sql);
    oci_bind_by_name($stid, ":id_cliente", $idCliente);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);
    $idCarrito = $row ? $row['ID_CARRITO'] : null;
} catch (Exception $e) {
    error_log("Fallo de conexion Oracle: " . $e->getMessage());
    die("Error de conexion a la base de datos");
}
$total = 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="/imgs/pokeball.gif" />
    <title>Gotta Collect 'Em All</title>
    <link rel="stylesheet" href="css/pagina_producto.css">
    <link rel="stylesheet" href="css/styles_tienda.css">
    <link rel="stylesheet" href="css/styles_carrito.css">
</head>

<body>
    <?php include 'elementos/header.php';?>
    <main>
        <div class="producto-layout">
            <a href="catalogo.html" class="back-button">⬅ Volver a la tienda</a>

            <div class="contenedor">
                <h1>Productos en el carrito</h1>

                <?php if (!$idCarrito): ?>
                    <p>Tu carrito está vacío.</p>
                <?php else: ?>
                    <?php
                    $sqlDetalles = "SELECT * FROM Detalle_Carrito WHERE ID_Carrito = :id_carrito";
                    $stidDetalles = oci_parse($conn, $sqlDetalles);
                    oci_bind_by_name($stidDetalles, ":id_carrito", $idCarrito);
                    oci_execute($stidDetalles);
                    $hayProductos = false;

                    while($detalle = oci_fetch_assoc($stidDetalles)):
                        $hayProductos = true;
                        $subtotal = $detalle['Precio'] * $detalle['Cantidad'];
                        $total += $subtotal;
                        ?>
                        <div class="producto-carrito">
                            <div class="imagen-cont">
                                <img src="imgs/<?=htmlspecialchars($detalle['GTIN']) ?>.jpeg" alt="<?=htmlspecialchars($detalle['NOMBRE_PRODUCTO'])?>">
                            </div>

                            <div class="info-carrito">
                                <h3><?= htmlspecialchars($detalle['Nombre_Producto']) ?></h3>
                                <p><?= htmlspecialchars($detalle['Categoria'])?></p>
                                <p class="precio"><?= $detalle['Precio'] ?>€</p>
                                <div class="cantidad-cont">
                                    <form method="post" action="modificar_carrito.php" style="display: inline;">
                                        <input type="hidden" name="id_producto" value="<?=$detalle['ID_Producto']?>">
                                        <input type="hidden" name="accion" value="restar">
                                        <button class="btn-cantidad">-</button>
                                    </form>
                                    <span class="cantidad"><?= $detalle['Cantidad'] ?> </span>
                                    <form method="post" action="modificar_carrito.php" style="display: inline;">
                                        <input type="hidden" name="id_producto" value="<?= $detalle['ID_Producto'] ?>">
                                        <input type="hidden" name="accion" value="sumar">
                                        <button class="btn-cantidad">+</button>
                                    </form>
                                </div>
                                <form method="post" action="modificar_carrito.php">
                                    <input type="hidden" name="id_producto" value="<?= $detalle['ID_Producto'] ?>">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <button class="btn-cantidad">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>

                    <?php if (!$hayProductos): ?>
                        <p> Tu carrito está vacío. </p>
                    <?php else: ?>
                        <div class="total-carrito">
                            <p>Total: <span><?=number_format($total, 2) ?>€</span></p>
                            <form action="finalizar_compra.php" method="post">
                                <button class="btn-comprar">Finalizar compra</button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include 'elementos/footer.php'; ?>
</body>

</html>