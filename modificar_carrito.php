<?php
session_start();
require_once 'dbgestion/sqlDatabase.php';

$conn = Database::getInstancia()->getConexion();
$temporalToken = $_COOKIE['cart_token'] ?? null;

$idProducto = $_POST['idProducto'] ?? null;
$accion = $_POST['accion'] ?? null;

// esta puesto así para que se pueda recibir el formulario desde
// finalizar_compra.php y se utilice el vaciar carrito del switch
// para la reutilización de código
if(!$accion || ($accion !== 'vaciar' && !$idProducto)) {
    header('Location: catalogo.php');
    exit;
}

// Determinamos la identidad del carrito
if(isset($_SESSION['idCliente'])) {
    // Lógica para clientes registrados

    $idCliente = $_SESSION['idCliente'];
    
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

    
} else {
    // Lógica para invitados
    if(!$temporalToken) {
        header('Location: catalogo.php');
        exit;
    }

    // 1. Obtener Carrito Temporal
    $stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas
                            WHERE Temporal_Token = ? AND Fecha_Expiracion > NOW()");
    $stmt->execute([$temporalToken]);
    $carrito = $stmt->fetch();

    if(!$carrito) {
        // 2. Crear nuevo carrito si ha expirado
        $token = bin2hex(random_bytes(16));
        $stmt = $conn->prepare("INSERT INTO Carrito_Ventas ( Temporal_Token, Fecha_Expiracion)
                                                            VALUES (?, NOW() + INTERVAL 7 DAY)");
        $stmt->execute([$token]);
        $idCarrito = $conn->lastInsertId();
        setcookie('cart_token', $token, time() +  604800, '/');
    } else {
        $idCarrito = $carrito['ID_Carrito'];
    }
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
                $stmt->execute([ // line 91
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
                // Obtener cantidad actual del producto en el carrito
                $stmt = $conn->prepare("SELECT Cantidad FROM Detalle_Carrito WHERE ID_Carrito = ? AND ID_Producto = ?");
                $stmt->execute([$idCarrito, $idProducto]);
                $detalle = $stmt->fetch();
            
                if ($detalle) {
                    if ($detalle['Cantidad'] > 1) {
                        // Si la cantidad es mayor a 1, simplemente restamos
                        $stmt = $conn->prepare("UPDATE Detalle_Carrito SET Cantidad = Cantidad - 1 WHERE ID_Carrito = ? AND ID_Producto = ?");
                        $stmt->execute([$idCarrito, $idProducto]);
                    } else {
                        // Si la cantidad es 1, eliminamos el producto del carrito
                        $stmt = $conn->prepare("DELETE FROM Detalle_Carrito WHERE ID_Carrito = ? AND ID_Producto = ?");
                        $stmt->execute([$idCarrito, $idProducto]);
                    }
                }
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

