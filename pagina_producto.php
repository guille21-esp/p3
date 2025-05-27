<?php 
session_start();
require_once 'dbgestion/sqlDatabase.php';

$conn = Database::getInstancia()->getConexion();

$gtin = $_GET['gtin'] ?? null;
$producto = null;

if($gtin) {
    $stmt = $conn->prepare("SELECT * FROM Productos WHERE GTIN = ?");
    $stmt->execute([$gtin]);
    $producto = $stmt->fetch();
}

if(!$producto) {
    // Producto no encontrado
    header('Location: catalogo.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="imgs/pokeball.gif"/>
    <title><?=htmlspecialchars($producto['Nombre'])?></title>
    <link rel="stylesheet" href="css/pagina_producto.css">
    <link rel="stylesheet" href="css/styles_tienda.css">
</head>
<body>
    <?php include 'elementos/header.php';?>
    <main>
        <div class="producto-layout">
            <a href="catalogo.php" class="back-button">⬅ Volver a la tienda</a>

            <div class="container">
                <div class="info">
                    <h1><?=htmlspecialchars($producto['Nombre'])?></h1>

                    <?php if(!empty($producto['Contenido'])):?>
                        <p><strong>Contenido: </strong><?=$producto['Contenido']?></p>
                    <?php endif;?>

                    <?php if(!empty($producto['Edicion'])):?>
                        <p><strong>Edición: </strong><?=$producto['Edicion']?></p>
                    <?php endif;?>

                    <?php if(!empty($producto['Rarezas'])):?>
                        <p><strong>Rarezas: </strong><?=$producto['Rarezas']?></p>
                    <?php endif;?>

                    <div class="price"><?=number_format($producto['Precio_Venta'], 2)?>€</div>
                    
                    <form method="post" action="modificar_carrito.php">
                        <input type="hidden" name="idProducto" value="<?=$producto['ID_Producto']?>">
                        <input type="hidden" name="accion" value="sumar">
                        <button type="submit" class="carrito-btn">🛒 Añadir al carrito</button>
                    </form>
                    
                </div>
                <div class="image">
                    <img src="<?=htmlspecialchars($producto['ImagenURL'])?>" alt="<?=$producto['Nombre']?>">
                </div>
            </div>
        </div>
    </main>  
    <?php include 'elementos/footer.php'; ?>
</body>
</html>