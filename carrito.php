<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="/imgs/pokeball.gif" />
    <title>Gotta Collect 'Em All</title>
    <link rel="stylesheet" href="css/pagina_producto.css">
    <link rel="stylesheet" href="css/styles_tienda.css">
    <link rel="stylesheet" href="css/styles_carrito.css">
</head>

<body>
    <?php include 'elementos/header.php';?>
    <main>
        <div class="producto-layout">
            <a href="catalogo.html" class="back-button">⬅ Volver a la tienda</a>

            <div class="contenedor">
                <h1>Productos en el carrito</h1>

                <div class="producto-carrito">
                    <div class="imagen-cont">
                        <img src="imgs/silvertempest.jpeg" alt="Pack Silver Tempest">
                    </div>

                    <div class="info-carrito">
                        <h3>Pack de cartas: Silver Tempest</h3>
                        <p>10 cartas de juego</p>
                        <p class="precio">5€</p>
                        <div class="cantidad-cont">
                            <button class="btn-cantidad">-</button>
                            <span class="cantidad">1</span>
                            <button class="btn-cantidad">+</button>
                        </div>
                        <button class="btn-eliminar">Eliminar</button>
                    </div>
                </div>

                <div class="total-carrito">
                    <p>Total: <span>5€</span></p>
                    <button class="btn-comprar">Finalizar compra</button>
                </div>
            </div>
        </div>
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
</body>

</html>