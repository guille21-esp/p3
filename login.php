<?php
session_start();
require_once 'dbgestion/sqlDatabase.php';

$errores = "";
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $clave = $_POST['clave'] ?? '';


    if ($correo && $clave) {
        $conn = Database::getInstancia()->getConexion();


        $stmt = $conn->prepare("SELECT ID_Cliente, Correo, Contrasena FROM Clientes WHERE Correo = ?");
        $stmt->execute([$correo]);
        $cliente = $stmt->fetch();
        
//      if ($cliente && password_verify($clave, $cliente['Contrasena'])) {
//      El if de arriba se usará cuando se guarden las contraseñas hasheadas
//      que todavía no está implementado el crear sesión como tal
        
        if ($cliente){
            if ($clave === $cliente['Contrasena']){
                $_SESSION['idCliente'] = $cliente['ID_Cliente'];
                $idCliente = $cliente['ID_Cliente'];

                // Fusión de carrito temporal si existe
                if(isset($_COOKIE['cart_token'])) {
                    $temporalToken = $_COOKIE['cart_token'];

                    // 1. Obtener el carrito temporal
                    $stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas WHERE Temporal_Token = ? AND Fecha_Expiracion > NOW()");
                    $stmt->execute([$temporalToken]);
                    $tempCart = $stmt->fetch();

                    if ($tempCart) {
                        $idTempCarrito = $tempCart['ID_Carrito'];

                        // 2. Obtener el carrito del cliente (si ya existía)
                        $stmt = $conn->prepare("SELECT ID_Carrito FROM Carrito_Ventas WHERE ID_Cliente = ?");
                        $stmt->execute([$idCliente]);
                        $existingCart = $stmt->fetch();

                        if ($existingCart) {
                            $idClienteCarrito = $existingCart['ID_Carrito'];

                            // 3. Fusionar los productos
                            // Por cada producto temporal, sumar o insertar en el carrito real
                            $stmt = $conn->prepare("SELECT * FROM Detalle_Carrito WHERE ID_Carrito = ?");
                            $stmt->execute([$idTempCarrito]);
                            $productosTemporales = $stmt->fetchAll();

                            foreach ($productosTemporales as $producto) {
                                $stmt = $conn->prepare("SELECT * FROM Detalle_Carrito WHERE ID_Carrito = ? AND ID_Producto = ?");
                                $stmt->execute([$idClienteCarrito, $producto['ID_Producto']]);
                                $existe = $stmt->fetch();

                                if ($existe) {
                                    $stmt = $conn->prepare("UPDATE Detalle_Carrito SET Cantidad = Cantidad + ? WHERE ID_Carrito = ? AND ID_Producto = ?");
                                    $stmt->execute([$producto['Cantidad'], $idClienteCarrito, $producto['ID_Producto']]);
                                } else {
                                    $stmt = $conn->prepare("INSERT INTO Detalle_Carrito (ID_Carrito, ID_Producto, Nombre_Producto, Categoria, GTIN, Precio, Cantidad)
                                                            VALUES (?, ?, ?, ?, ?, ?, ?)");
                                    $stmt->execute([
                                        $idClienteCarrito,
                                        $producto['ID_Producto'],
                                        $producto['Nombre_Producto'],
                                        $producto['Categoria'],
                                        $producto['GTIN'],
                                        $producto['Precio'],
                                        $producto['Cantidad']
                                    ]);
                                }
                            }

                            // 4. Borrar el carrito temporal
                            $stmt = $conn->prepare("DELETE FROM Carrito_Ventas WHERE ID_Carrito = ?");
                            $stmt->execute([$idTempCarrito]);

                        } else {
                            // 5. Si no había carrito cliente, simplemente asignar el temporal al cliente
                            $stmt = $conn->prepare("UPDATE Carrito_Ventas SET ID_Cliente = ?, Temporal_Token = NULL, Fecha_Expiracion = NULL WHERE ID_Carrito = ?");
                            $stmt->execute([$idCliente, $idTempCarrito]);
                        }

                        // 6. Eliminar cookie
                        setcookie('cart_token', '', time() - 3600, '/');
                    }
                }

                // Redirección tras login (viene de finalizar compra)
                if (isset($_SESSION['checkout_redirect'])) {
                    unset($_SESSION['checkout_redirect']);
                    header('Location: finalizar_compra.php');
                } else {
                    header('Location: catalogo.php');
                }
            } else {
                $errores = "Contrasena incorrecta.";
            } 
        } else {
            $errores = "No existe un cliente con ese correo";
        }
    } else {
        $errores .= "Completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/styles_tienda.css">
</head>
<body>
    <?php include 'elementos/header.php';?>
    <main>
        <form class="login" method="POST" action="login.php" autocomplete="off" novalidate>
            <h2>Iniciar Sesión</h2>
            <?php if(!empty($errores)): ?>
                <p class="error"><?=$errores?></p>
            <?php endif; ?>
            <p>
                <label for="correo">Usuario</label><br />
                <input type="text" id="usuario" name="correo" required autocomplete="off"/>
            </p>
            <p>
                <label for="clave">Contraseña</label><br />
                <input type="password" id="contrasena" name="clave" required autocomplete="off"/>
            </p>
            <p>
                <button type="submit">Entrar</button>
            </p>
            <a class= link_alta href="altausuarios.php">¿No tienes cuenta?</a>
        </form>
    </main>
    <?php include 'elementos/footer.php'?>
</body>
</html>
