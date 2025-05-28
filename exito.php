<?php
session_start();
if(!isset($_SESSION['form_completed'])) {
    header('Location: altausuarios.php');
    exit();
}

unset($_SESSION['form_completed']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="imgs/pokeball.gif"/>
    <title>Gotta Collect 'Em All</title>
    <link rel="stylesheet" href="css/styles_tienda.css">
    <link rel="stylesheet" href="css/exito.css">

</head>
<body>
    <?php include 'elementos/header.php'; ?>
    <main id="pagina-exito">
    
        <div class="contenedor-exito">
            <h1>¡Registro Completado con Éxito!</h1>
            <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcDl4b3V0dGZzZ2RqY3V4Y3F5bGZ6Z2J5NnR0eGZ6dHZqZzB1YiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/ibolLe3mOqHE3PQTtk/giphy.gif" alt="Éxito" class="gif-exito">
            <p>Gracias por registrarte en Gotta Collect 'Em All. Tu cuenta ha sido creada exitosamente.</p>
            <a href="catalogo.html" class="boton-volver">Volver al Inicio</a>
        </div>
    
    </main>
    <?php include 'elementos/footer.php';?>
</body>
</html>

# he puesto el css dentro del html porque no se actualizaba en el localhost, porlomenos para mi

<style>
    .contenedor-exito {
        text-align: center;
        background-color: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 100%;
    }

        body {
            background-color: #f9f9f9;
            align-items: center;
        }
        .gif-exito {
            width: 150px;
            height: 150px;
            margin: 20px auto;
            display: block;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        p {
            color: #555;
            font-size: 18px;
            margin-bottom: 30px;
        }
        
        .boton-volver {
            background-color: #df3232;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .boton-volver:hover {
            background-color: #b82929;
        }
        
        #pagina-exito {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Centrado vertical */
            background-color: #f9f9f9;
        }
        
        
</style>