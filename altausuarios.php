<?php

// 1. Inicializamos las variables y los errores en un array de asignación
$formSent = $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST);


$allOK = false;


$campos = [
    'nombre' => '',
    'apellidos' => '',
    'nacimiento' => '',
    'email' => '',
    'tlf' => '',
    'intereses' => '',
    'compra' => '',
    'coleccion' => ''
];

$error = [
    'nombre' => '',
    'apellidos' => '',
    'nacimiento' => '',
    'email' => '',
    'tlf' => '',
    'intereses' => '',
    'compra' => '',
    'coleccion' => ''
];

// 2. Procesamos el formulario cuando se envía la primera vez
foreach ($campos as $campo => $valor) {
    $campos[$campo] = $_POST[$campo] ?? '';
}

// Realizamos la validación de datos sólo si se ha enviado el formulario
if ($formSent) {
    if (empty($campos['nombre']))
        $error['nombre'] = "El nombre es obligatorio.";
    else if (is_numeric($campos['nombre']))
        $error['nombre'] = "El nombre no puede ser numérico";
    else if (!ctype_upper(substr($campos['nombre'],0,1)))
        $error['nombre'] = "El nombre debe comenzar en mayúscula.";

    if(empty($campos['apellidos']))
        $error['apellidos'] = "Los apellidos son obligatorios. ";
    else if (is_numeric($campos['apellidos']))
        $error['apellidos'] = "Los apellidos no pueden ser numéricos";
    else if (!ctype_upper(substr($campos['apellidos'],0,1)))
        $error['apellidos'] = "Los apellidos deben comenzar en mayúscula.";

    if(empty($campos['nacimiento']))
        $error['nacimiento'] = "La fecha no puede estar vacía.";
    else if(!mayorEdad($campos['nacimiento']))
        $error['nacimiento'] = "Debes ser mayor de edad.";

    if(empty($campos['email']))
        $error['email'] = "El email no puede estar vacío.";
    else if(!filter_var($campos['email'], FILTER_VALIDATE_EMAIL))
        $error['email'] = "El email no es válido.";

    $allOK = !array_filter($error);

    if($allOK){
        session_start();
        $_SESSION['form_completed'] = true;
        header("Location: exito.php");
        exit();
    }
}



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="imgs/pokeball.gif"/>
    <title>Gotta Collect 'Em All</title>
    <link rel="stylesheet" href="css/styles_tienda.css">
    <link rel="stylesheet" href="css/altausuarios.css">
</head>
<body>
    <?php include 'elementos/header.php'; ?>
    <main id="formulario">
        
        <section>
        <h2>Formulario de Inscripción</h2>
        
        <form action="altausuarios.php" method="post" class="form_1">
            <section class="form-linea">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required
                        value="<?= $campos['nombre'] ?? ''; ?>">
                <?php if(!empty($error['nombre'])): ?>
                    <span class="error"><?= $error['nombre']; ?></span>
                <?php endif; ?>
            </section>
            
            <section class="form-linea">
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required
                        value="<?= $campos['apellidos'] ?? ''; ?>">
                <?php if(!empty($error['apellidos'])): ?>
                    <span class="error"><?= $error['apellidos']; ?></span>
                <?php endif; ?>        
            </section>

            <section class="form-linea">
                <label for="nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="nacimiento" required
                        value="<?= $campos['nacimiento'] ?? ''; ?>" >
                <?php if(!empty($error['nacimiento'])): ?>
                    <span class="error"><?= $error['nacimiento']; ?></span>
                <?php endif; ?>
            </section>

            <section class="form-linea">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                        value="<?= $campos['email'] ?? ''; ?>">
                <?php if(!empty($error['email'])): ?>
                    <span class="error"><?= $error['email']; ?></span>
                <?php endif; ?>
            </section>

            <section class="form-linea">
                <label for="tlf">Teléfono de contacto:</label>
                <input type="tel" id="telefono" name="tlf" required maxlength="9"
                        value="<?= $campos['tlf'] ?? '';?>">
                <?php if(!empty($error['tlf'])): ?>
                    <span class="error"><?= $error['tlf']; ?></span>
                <?php endif; ?>
            </section>

            <section class="form-linea">
                <label for="lista_intereses">¿Cuales son tus intereses?</label>
                <input type="text" id="lista_intereses" name="lista_intereses" list="opciones_intereses" required value="<?=$campos['intereses'] ?? '';?>">
                <datalist id="opciones_intereses">
                    <option value="Coleccionismo">Coleccionismo</option>
                    <option value="Aficionado">Aficionado</option>
                    <option value="Competidor">Competidor</option>
                    
                </datalist>
            </section>

            <label>¿Es tu primera compra?</label>
            <section class="form-radio">
                <input type="radio" id="primera_vez_si" name="primera_vez" value="si" required checked>
                <label for="primera_vez_si">Sí</label>
                <input type="radio" id="primera_vez_no" name="primera_vez" value="no" required>
                <label for="primera_vez_no">No</label>
            </section>

            
            <label>¿Cual es tu colección favorita?</label>
            <section class="form-radio">
                <input type="radio" id="151" name="preferencia" value="151" required checked>
                <label for="151">151</label>
                <input type="radio" id="Chispas Fulgurantes" name="preferencia" value="Chispas Fulgurantes" required>
                <label for="Chispas Fulgurantes">Chispas Fulgurantes</label>
                <input type="radio" id="journeytogether" name="preferencia" value="journeytogether" required>
                <label for="journeytogether">Journeytogether</label>
            </section>
            <section class="botones">
                <button type="submit">Registrarse</button>
                <button type="reset">Resetear Formulario</button>
            </section>

        </form>
         </section>

    </main>

    <?php include 'elementos/footer.php'; ?>
   
    <div id="primerCompraPopup" class="popup">
        <div class="popup-content">
            <h3>¡Bienvenido nuevo cliente!</h3>
            <p>Por ser tu primera compra, te ofrecemos un 10% de descuento en tu pedido. Usa el código: BIENVENIDO10</p>
            <button onclick="cerrarPopup()">Aceptar</button>
        </div>
    </div>

    
    <script>
       
        const primeraVezSi = document.getElementById('primera_vez_si');
        const primeraVezNo = document.getElementById('primera_vez_no');
        const popup = document.getElementById('primerCompraPopup');

        
        primeraVezSi.addEventListener('change', function() {
            if(this.checked) {
                mostrarPopup();
            }
        });

        primeraVezNo.addEventListener('change', function() {
            if(this.checked) {
                cerrarPopup();
            }
        });

        
        function mostrarPopup() {
            popup.style.display = 'flex';
        }

        function cerrarPopup() {
            popup.style.display = 'none';
        }
    </script>
</body>
</html>

<?php 

function mayorEdad($edad){
    $fechaNacimiento = DateTime::createFromFormat('Y-m-d', $edad);
    $hoy = new DateTime();
    $mayoriaEdad = (clone $hoy)->modify('-18 years');

    return $fechaNacimiento <= $mayoriaEdad;

}

?>
