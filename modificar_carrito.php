<?php
session_start();
require_once 'Database.php';

if(isset($_SESSION['id_cliente'])){
    header('Location: login.php');
    exit;
}

$idCliente = $_SESSION['id_cliente'];
$idProducto = $_POST['id_producto'] ?? null;
$accion = $_POST['accion'] ?? null;

if(!$idProducto || !$accion) {
    header('Location: carrito.php');
    exit;
}

$conn = Database::getInstancia()->getConexion();

// 1. Verficio si el cliente ya tiene un carrito
$sqlCarrito = "SELECT ID_CARRITO FROM CARRITO_VENTAS WHERE ID_CLIENTE = :id_cliente";
$stid = oci_parse($conn, $sqlCarrito);
oci_bind_by_name($stid, ":id_cliente", $idCliente);
oci_execute($stid);
$row = oci_fetch_assoc($stid);
$idCarrito = $row['ID_CARRITO'] ?? null;

// 2. Creo el carrito si no existe
if (!$idCarrito) {
    $sqlCrear = "INSERT INTO Carrito_Ventas (ID_Cliente, Cantidad_Productos, Total) VALUES (:id_cliente, 0, 0) RETURNING ID_Carrito INTO :id_nuevo";
    $stid = oci_parse($conn, $sqlCrear);
    oci_bind_by_name($stid, ":id_cliente", $idCliente);
    oci_bind_by_name($stid, ":id_nuevo", $idCarrito, 32);
    oci_execute($stid);
}

// 3. Obtener información del producto
$sqlProd = "SELECT ID_Producto, Nombre, Categoria, GTIN, Precio_venta FROM Productos WHERE ID_Producto = :id_producto";
$stid = oci_parse($conn, $sqlProd);
oci_bind_by_name($stid, ":id_producto", $idProducto);
oci_execute($stid);
$producto = oci_fetch_assoc($stid);

if (!$producto) {
    header('Location: carrito.php');
    exit;
}

$precio = $producto['PRECIO_VENTA'];

switch ($accion) {
    case 'sumar':
        // Ver si ya existe el producto en el carrito
        $sqlCheck = "SELECT Cantidad FROM Detalle_Carrito WHERE ID_Carrito = :id_carrito AND ID_Producto = :id_producto";
        $stid = oci_parse($conn, $sqlCheck);
        oci_bind_by_name($stid, ":id_carrito", $idCarrito);
        oci_bind_by_name($stid, ":id_producto", $idProducto);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);

        if ($row) {
            // Ya existe: actualizar cantidad
            $sqlUpdate = "UPDATE Detalle_Carrito SET Cantidad = Cantidad + 1 WHERE ID_Carrito = :id_carrito AND ID_Producto = :id_producto";
        } else {
            // No existe: insertar
            $sqlUpdate = "INSERT INTO Detalle_Carrito (ID_Carrito, ID_Producto, Nombre_Producto, Categoria, GTIN, Precio, Cantidad) 
                          VALUES (:id_carrito, :id_producto, :nombre, :categoria, :gtin, :precio, 1)";
        }
        break;

    case 'restar':
        $sqlUpdate = "UPDATE Detalle_Carrito SET Cantidad = Cantidad - 1 
                      WHERE ID_Carrito = :id_carrito AND ID_Producto = :id_producto AND Cantidad > 1";
        break;

    case 'eliminar':
        $sqlUpdate = "DELETE FROM Detalle_Carrito WHERE ID_Carrito = :id_carrito AND ID_Producto = :id_producto";
        break;

    default:
        header('Location: carrito.php');
        exit;
}

// Ejecutar acción
$stid = oci_parse($conn, $sqlUpdate);
oci_bind_by_name($stid, ":id_carrito", $idCarrito);
oci_bind_by_name($stid, ":id_producto", $idProducto);
if ($accion === 'sumar' && !$row) {
    oci_bind_by_name($stid, ":nombre", $producto['NOMBRE']);
    oci_bind_by_name($stid, ":categoria", $producto['CATEGORIA']);
    oci_bind_by_name($stid, ":gtin", $producto['GTIN']);
    oci_bind_by_name($stid, ":precio", $precio);
}
oci_execute($stid);

header('Location: carrito.php');
exit;