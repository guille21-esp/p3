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
    <?php include 'elementos/header.html'; ?>
    <main id="formulario">
        
        <section>
        <h2>Formulario de Inscripción</h2>
        
        <form action="exito.html" method="get" class="form_1">
            <section class="form-linea">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </section>
            
            <section class="form-linea">
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required>
            </section>

            <section class="form-linea">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </section>

            <section class="form-linea">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$">
            </section>

            <section class="form-linea">
                <label for="telefono">Teléfono de contacto:</label>
                <input type="tel" id="telefono" name="telefono" required maxlength="9">
            </section>

            <section class="form-linea">
                <label for="lista_intereses">¿Cuales son tus intereses?</label>
                <input type="text" id="lista_intereses" name="lista_intereses" list="opciones_intereses" required>
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
      
    <footer class="pie">
        <p>
            <a href="contacto.html">¿Alguna duda? Contáctanos</a>
            |
            <a href="contacto.html">Metodos de pago</a>
            |
            <a href="contacto.html">Comunidad</a>
        </p>
    </footer>

   
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