<?php
session_start();

require_once 'dbgestion/sqlDatabase.php';

if(!isset($_SESSION['idCliente'])){
    header('Location: login.php');
    exit;
}

$idCliente = $_SESSION['idCliente'];
$idProducto = $_POST['idProducto'] ?? null;
$accion = $_POST['accion'] ?? null;

// esta puesto así para que se pueda recibir el formulario desde
// finalizar_compra.php y se utilice el vaciar carrito del switch
// para la reutilización de código
if(!$accion || ($accion !== 'vaciar' && !$idProducto)) {
    header('Location: catalogo.php');
    exit;
}

$conn = Database::getInstancia()->getConexion();

// 1. Obtener carrito si existe
$stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas WHERE ID_Cliente = ?");
$stmt->execute([$idCliente]);
$carrito = $stmt->fetch();

// 2. Creo el carrito si no existe
if (!$carrito) {
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
    header('Location: catalogo.php');
    exit;
}

$precio = $producto['Precio_Venta'];

switch ($accion) {
    case 'sumar':
        $stmt = $conn->prepare("SELECT Cantidad FROM Detalle_Carrito WHERE ID_Carrito = ? AND ID_Producto = ?");
        $stmt->execute([$idCarrito, $idProducto]);
        $detalle = $stmt->fetch();

        if ($detalle) {
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

    case 'vaciar':
        //Vaciar carrito, es decir, eliminar cada Detalle_Carrito s(sin eliminar el registro del carrito en sí)
        $stmt = $conn->prepare("DELETE FROM Detalle_Carrito WHERE ID_Carrito = ?");
        $stmt->execute([$idCarrito]);

        // Resetear los totales del carrito también
        $stmt = $conn->prepare("UPDATE Carrito_Ventas SET Total = 0, Cantidad_Productos = 0 WHERE ID_Carrito = ?");
        $stmt->execute([$idCarrito]);
        echo "Su carrito se vacío con éxito. ";
        break;
}

header('Location: carrito.php');
exit;