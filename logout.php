<?php
// 1. Siempre iniciar sesión al principio
session_start();

// 2. Destruir completamente la sesión
$_SESSION = array(); // Limpia todos los datos de sesión

// Si se desea destruir la cookie de sesión (opcional pero recomendado)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

session_destroy(); // Destruye la sesión

// 3. Redireccionar a la página principal con mensaje
header('Location: catalogo.php');
exit;
?>