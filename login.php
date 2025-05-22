<?php
session_start();
require_once 'dbgestion/sqlDatabase.php';

$errores = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $clave = $_POST['clave'] ?? '';

    echo "<p> $correo y $clave </p>";
    if ($correo && $clave) {
        $conn = Database::getInstancia()->getConexion();

        $stmt = $conn->prepare("SELECT ID_Cliente, Correo, Contrasena FROM Clientes WHERE Correo = ?");
        $stmt->execute([$correo]);
        $cliente = $stmt->fetch();

        echo "<pre>";
        print_r($cliente);
        echo "</pre>";

        echo "Correo introducido: [$correo]";

//       if ($cliente && password_verify($clave, $cliente['Contrasena'])) {
//      El if de arriba se usará cuando se guarden las contraseñas hasheadas
//      que todavía no está implementado el crear sesión como tal
        if ($cliente && $clave === $cliente['Contrasena']){
            $_SESSION['idCliente'] = $cliente['ID_Cliente'];
            header('Location: catalogo.php');
            exit;
        } else {
            if($correo != $cliente['Correo'])
                $errores .= "El email es incorrecto.";
            else if($clave != $cliente['contrasena'])
                $errores .= "La contrasena es incorrecto.";

            $errores .= "Correo o contraseña incorrectos.";
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
    <h2>Iniciar sesión</h2>
    <form class="login">
      <p>
        <label for="usuario">Usuario</label><br />
        <input type="text" id="usuario" name="usuario" required />
      </p>
      <p>
        <label for="contrasena">Contraseña</label><br />
        <input type="password" id="contrasena" name="contrasena" required />
      </p>
      <p>
        <button type="submit">Entrar</button>
      </p>
      <a class= link_alta href="altausuarios.php">¿No tienes cuenta?</a>
    </form>
    <?php include 'elementos/footer.php'?>
</body>
</html>
