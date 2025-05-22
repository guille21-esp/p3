<?php
session_start();
require_once 'dbgestion/sqlDatabase.php';

if(isset($_SESSION['idCliente'])){
    header('Location: login.php');
    exit;
}

$idCliente = $_SESSION['idCliente'];
$idProducto = $_POST['idProducto'] ?? null;
$accion = $_POST['accion'] ?? null;

if(!$idProducto || !$accion) {
    header('Location: carrito.php');
    exit;
}

$conn = Database::getInstancia()->getConexion();

// 1. Obtener carrito si existe
$stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas WHERE ID_Cliente = ?");
$stmt->execute([$idCliente]);
$carrito = $stmt->fetch();

// 2. Creo el carrito si no existe
if (!$idCarrito) {
    $stmt = $conn->prepare("INSERT INTO Carrito_Ventas (ID_Cliente) VALUES (?)");
    $stmt->execute([$idCliente]);
    $idCarrito = $conn->lastInsertId();
} else {
    $idCarrito = $carrito['ID_Carrito'];
}

// 3. Obtener información del producto
$stmt = $conn->prepare("SELECT * FROM Productos WHERE ID_Producto = ?");
$stmt->execute([$idProducto]);
$producto = $stmt->fetch();

if (!$producto) {
    header('Location: carrito.php');
    exit;
}

$precio = $producto['PRECIO_VENTA'];

switch ($accion) {
    case 'sumar':
        $stmt = $conn->prepare("SELECT Cantidad FROM Detalle_Carrito WHERE ID_Carrito = ? AND ID_Producto = ?");
        $stmt->execute([$idCarrito, $idProducto]);
        $existe = $stmt->fetch();

        if ($existe) {
            // Ya existe: actualizar cantidad
            $stmt = $conn->prepare("UPDATE Detalle_Carrito SET Cantidad = Cantidad + 1 WHERE ID_Carrito = ? AND ID_Producto = ?");
            $stmt->execute([$idCarrito, $idProducto]);
        } else {
            // No existe: insertar
            $stmt = $conn->prepare("INSERT INTO Detalle_Carrito (ID_Carrito, ID_Producto, Nombre_Producto, Categoria, GTIN, Precio, Cantidad) VALUES (?, ?, ?, ?, ?, ?, 1)");
            $stmt->execute([
                $idCarrito,
                $producto['ID_Producto'],
                $producto['Nombre'],
                $producto['Categoria'],
                $producto['GTIN'],
                $producto['Precio_Venta'],
            ]);
        }
        break;

    case 'restar':
        // Esto controla que cuando llegue a 0 se llame a eliminar o algo?
        $stmt = $conn->prepare("UPDATE Detalle_Carrito SET Cantidad = Cantidad - 1 
                      WHERE ID_Carrito = ? AND ID_Producto = ? AND Cantidad > 1");
        $stmt->execute([$idCarrito, $idProducto]);
        break;

    case 'eliminar':
        $stmt = $conn->prepare("DELETE FROM Detalle_Carrito WHERE ID_Carrito = ? AND ID_Producto = ?");
        $stmt->execute([$idCarrito, $idProducto]);
        break;

    // No estoy seguro que el default sea totalmente necesario
    default:
        header('Location: carrito.php');
        exit;
}

header('Location: carrito.php');
exit;