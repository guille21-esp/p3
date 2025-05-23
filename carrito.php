<?php
session_start();

require_once 'dbgestion/sqlDatabase.php';

if(!isset($_SESSION['idCliente'])) {
    header('Location: login.php');
    exit;
  }

$idCliente = $_SESSION['idCliente'];
$conn = Database::getInstancia()->getConexion();

// Obtener ID del carrito del cliente (único por cliente)
$stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas WHERE ID_Cliente = ?");
$stmt->execute([$idCliente]);
$row = $stmt->fetch();
$idCarrito = $row ? $row['ID_Carrito'] : null;

if(!$idCarrito) {
    // Crear un carrito si no existe
    $stmt = $conn->prepare("INSERT INTO Carrito_Ventas (ID_Cliente) VALUES (?)");
    $stmt->execute([$idCliente]);
    $idCarrito = $conn->lastInsertId();
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
            <a href="catalogo.php" class="back-button">⬅ Volver a la tienda</a>

            <div class="contenedor">
                <h1>Productos en el carrito</h1>

                <?php if (!$idCarrito): ?>
                    <p>
                        Oh no! El carrito está vacío :/
                    </p>
                <?php else: ?>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM Detalle_Carrito WHERE ID_Carrito = ?");
                    $stmt->execute([$idCarrito]);
                    $productos = $stmt->fetchAll();
                    ?>

                    <?php if (empty($productos)): ?>
                        <p>TU carrito está vacío </p>
                    <?php else: ?>
                        <?php foreach ($productos as $detalle):
                            $subtotal = $detalle['Precio'] * $detalle['Cantidad'];
                            $total += $subtotal;
                        ?>
                        <div class="producto-carrito">
                            <div class="imagen-cont">
                                <img src="imgs/<?=$detalle['GTIN'] ?>.jpeg" alt="<?=$detalle['Nombre_Producto']?>">
                        </div>
                            <div class="info-carrito">
                                <h3><?= htmlspecialchars($detalle['Nombre_Producto']) ?></h3>
                                <p><?= htmlspecialchars($detalle['Categoria'])?></p>
                                <p class="precio"><?= $detalle['Precio'] ?>€</p>
                                <div class="cantidad-cont">
                                    <form method="post" action="modificar_carrito.php">
                                        <input type="hidden" name="idProducto" value="<?=$detalle['ID_Producto']?>">
                                        <input type="hidden" name="accion" value="restar">
                                        <button class="btn-cantidad">-</button>
                                    </form>
                                    <span class="cantidad"><?= $detalle['Cantidad'] ?> </span>
                                    <form method="post" action="modificar_carrito.php" style="display: inline;">
                                        <input type="hidden" name="idProducto" value="<?= $detalle['ID_Producto'] ?>">
                                        <input type="hidden" name="accion" value="sumar">
                                        <button class="btn-cantidad">+</button>
                                    </form>
                                </div>
                                <form method="post" action="modificar_carrito.php">
                                    <input type="hidden" name="idProducto" value="<?= $detalle['ID_Producto'] ?>">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <button class="btn-cantidad">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>

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