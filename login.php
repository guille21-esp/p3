<?php
// 1. Inicio de sesión SIEMPRE TIENE QUE IR AL PRINCIPIO DEL PHP
session_start();

// 2. Incluir dependencias
require_once 'dbgestion/sqlDatabase.php';

// Añado una opción para debuggear la página y ver errores:


$errores = "";

// 3. Lógica PHP 
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
                header('Location: carrito.php');
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
// 4. HTML (después de toda la lógica que pueda redirigir)
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
