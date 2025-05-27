<?php
session_start();
require_once 'dbgestion/sqlDatabase.php';
$conn = Database::getInstancia()->getConexion();

$productos = [];
$conditions = [];
$params = [];


if (!empty($_GET['busqueda'])) {
    $busqueda = trim($_GET['busqueda']);
    $conditions[] = "Nombre LIKE ?";
    $params[] = '%' . $busqueda . '%';
}


if (!empty($_GET['categorias'])) {
    $categoriasSeleccionadas = array_map('strval', $_GET['categorias']);
    $placeholders = implode(',', array_fill(0, count($categoriasSeleccionadas), '?'));
    $conditions[] = "Categoria IN ($placeholders)";
    $params = array_merge($params, $categoriasSeleccionadas);
}


$sql = "SELECT * FROM Productos";
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="imgs/pokeball.gif"/>
    <title>Gotta Collect 'Em All</title>
    <link rel="stylesheet" href="css/styles_tienda.css">
</head>
<body>
    <?php include 'elementos/header.php'; ?>
    <main id="contenedor-actividades">
        <div class="filtro">
            <form method="GET" action="catalogo.php">
                <input type="text" name="busqueda" placeholder="Buscar productos..." value="<?=htmlspecialchars($_GET['busqueda'] ?? '') ?>">
                
                <div class="checkout-container">
                    <label><input type="checkbox" name="categorias[]" value="Boosters" <?= in_array("Boosters", $_GET['categorias'] ?? []) ? 'checked' : '' ?>> Boosters</label>
                    <label><input type="checkbox" name="categorias[]" value="Cartas Gradadas" <?= in_array("Cartas Gradadas", $_GET['categorias'] ?? []) ? 'checked' : '' ?>> Cartas Gradadas</label>
                    <label><input type="checkbox" name="categorias[]" value="Lotes" <?= in_array("Lotes", $_GET['categorias'] ?? []) ? 'checked' : '' ?>> Lotes</label>
                    <label><input type="checkbox" name="categorias[]" value="Accesorios" <?= in_array("Accesorios", $_GET['categorias'] ?? []) ? 'checked' : '' ?>> Accesorios</label>
                </div>
                <div class="button-container">
                    <button type="submit">Filtrar</button>
                    <a href="catalogo.php">Limpiar Filtros</a>
                </div>
            </form>
        </div>

        <section id="galeria-actividades">
            <?php if (empty($productos)): ?>
                <p>No hay productos para mostrar.</p>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    <a href="pagina_producto.php?gtin=<?= $producto['GTIN'] ?>" class="actividad">
                        <div class="imagen-container">
                            <img src="<?= htmlspecialchars($producto['ImagenURL']) ?>" alt="<?= htmlspecialchars($producto['Nombre']) ?>">
                        </div>
                        <h3><?= htmlspecialchars($producto['Nombre']) ?></h3>
                        <p>Categoría: <?= htmlspecialchars($producto['Categoria']) ?></p>
                        <p><?= htmlspecialchars($producto['Precio_Venta']) ?>€</p>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>


        
    </main>
    <?php include 'elementos/footer.php'; ?>
</body>
</html>