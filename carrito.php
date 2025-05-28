<?php
session_start();
require_once 'dbgestion/sqlDatabase.php';

$conn = Database::getInstancia()->getConexion();
$idCarrito = null;
$temporalToken = $_COOKIE['cart_token'] ?? null;

if (isset($_SESSION['idCliente'])){
    $idCliente = $_SESSION['idCliente'];

    // Obtener carrito del cliente
    $stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas WHERE ID_Cliente = ?");
    $stmt->execute([$idCliente]);
    $row = $stmt->fetch();
    $idCarrito = $row ? $row['ID_Carrito'] : null;
    
    // Si existe un token temporal, fusionar los carritos
    if($temporalToken) {
        $stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas WHERE Temporal_Token = ?");
        $stmt->execute([$temporalToken]);
        $tempCart = $stmt->fetch();
    
        if($tempCart) {
            // Fusionar items
            $conn->exec("UPDATE Detalle_Carrito SET ID_Carrito = $idCarrito WHERE ID_Carrito = {$tempCart['ID_Carrito']}");
            //Eliminar el carrito temporal
            $conn->exec("DELETE FROM Carrito_Ventas WHERE ID_Carrito = {$tempCart['ID_Carrito']}");
            // Eliminar cookie
            setcookie('cart_token', '', time() - 3600, '/');
        }
    }
    // Crear carrito si no existe (SOLO para usuarios registrados)
    if(!$idCarrito) {
        $stmt = $conn->prepare("INSERT INTO Carrito_Ventas (ID_Cliente) VALUES (?)");
        $stmt->execute([$idCliente]);
        $idCarrito = $conn->lastInsertId();
    }
} else { // Usuario invitado
    if($temporalToken) {
        $stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas 
                              WHERE Temporal_Token = ? AND Fecha_Expiracion > NOW()");
        $stmt->execute([$temporalToken]);
        $row = $stmt->fetch();
        $idCarrito = $row ? $row['ID_Carrito'] : null;
    }
    
    if(!$idCarrito) {
        // Crear nuevo carrito temporal
        $token = bin2hex(random_bytes(16));
        $stmt = $conn->prepare("INSERT INTO Carrito_Ventas (Temporal_Token, Fecha_Expiracion) 
                               VALUES (?, NOW() + INTERVAL 7 DAY)");
        $stmt->execute([$token]);
        $idCarrito = $conn->lastInsertId();
        setcookie('cart_token', $token, time() + 604800, '/'); // 7 días
    }
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
                        Oh no! El carrito no existe :/
                    </p>
                <?php else: ?>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM Detalle_Carrito WHERE ID_Carrito = ?");
                    $stmt->execute([$idCarrito]);
                    $productos = $stmt->fetchAll();
                    ?>

                    <?php if (empty($productos)): ?>
                        <p>Oh no, tu carrito está vacío :/ <br><br>¡Vuelve a nuestra tienda para seleccionar algunos productos alucinantes! </p>
                    <?php else: ?>
                        <?php foreach ($productos as $detalle):
                        // Calculo de la ruta de la imagen
                            $stmt = $conn->prepare("SELECT ImagenURL FROM Productos WHERE ID_Producto = ?");
                            $stmt->execute([$detalle['ID_Producto']]);
                            $imagenData = $stmt->fetch();
                            $imagen = $imagenData['ImagenURL']; // Acceder a la columna específica

                            $subtotal = $detalle['Precio'] * $detalle['Cantidad'];
                            $total += $subtotal;
                        ?>
                        <div class="producto-carrito">
                            <div class="imagen-cont">
                                <img src="<?=htmlspecialchars($imagen)?>" alt="<?=$detalle['Nombre_Producto']?>">
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