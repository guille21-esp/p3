<header class="cabecera">
  <section class="barra_superior">
    <figure class="logoynombre">
      <img src="imgs/pokeball.gif" alt="Logo">
      <figcaption>Gotta Collect 'Em All</figcaption>
    </figure>
    <!-- No sé si queréis crear otra clase en css que sea login-btn -->
    <div class="botones-derecha">
      <?php if(isset($_SESSION['idCliente'])): ?>
        <!-- Mostrar Cerrar Sesión si hay sesión -->
        <button type="button" class="login-btn" onclick="window.location.href='logout.php'">
          Cerrar Sesión
        </button>
      <?php else: ?>
        <!-- Mostrar Iniciar Sesión si NO hay sesión -->
        <button type="button" class="login-btn" onclick="window.location.href='login.php'">
          Iniciar Sesión
        </button>
      <?php endif; ?>
      
      <!-- Botón del carrito (siempre visible) -->
      <button type="button" class="carrito-btn" onclick="window.location.href='carrito.php'">
        🛒 Carrito de compra
      </button>
    </div>
  </section>   
</header>