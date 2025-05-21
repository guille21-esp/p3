<header class="cabecera">
  <section class="barra_superior">
    <figure class="logoynombre">
      <img src="imgs/pokeball.gif" alt="Logo">
      <figcaption>Gotta Collect 'Em All</figcaption>
    </figure>
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
    <p>
      <button type="button" class="carrito-btn" onclick="window.location.href='carrito.php'">
        🛒 Carrito de compra
      </button>
    </p>
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