<header class="cabecera">
  <section class="barra_superior">
    <figure class="logoynombre">
      <img src="imgs/pokeball.gif" alt="Logo">
      <figcaption>Gotta Collect 'Em All</figcaption>
    </figure>
    <!-- No sé si queréis crear otra clase en css que sea login-btn -->
    <div class="botones-derecha">
      <button type="button" class="login-btn" onclick="window.location.href='login.php'">
        Iniciar Sesión
      </button>
    
      <button type="button" class="carrito-btn" onclick="window.location.href='carrito.php'">
        🛒 Carrito de compra
      </button>
    </div>
  </section>

  <div class="barra_inferior">
    <div class="centro-busqueda">
      <form action="/buscar" method="GET">
        <input type="search" name="q" placeholder="Buscar..." />
        <button type="submit">Buscar</button>
      </form>
    </div>
  </div>
   
</header>