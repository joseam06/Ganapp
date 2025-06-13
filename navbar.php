<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<nav class="navbar-custom">
  <div class="navbar-container">
    
    <!-- IZQUIERDA: Logo + Nombre -->
    <div class="navbar-left">
      <img src="https://img.icons8.com/ios/50/cow--v1.png"  alt="Logo">
      <a href="index.php" class="brand-text">GanApp</a>
    </div>

    <!-- CENTRO: Menú de navegación centrado -->
    <div class="navbar-center">
      <ul class="navbar-menu">
        <li><a href="index.php">Inicio</a></li>
        <li><a href="#funciones">Funciones</a></li>
        <li><a href="#beneficios">Beneficios</a></li>
        <li><a href="#objetivo">Objetivo</a></li>
        <li><a href="#contacto">Contacto</a></li>
      </ul>
    </div>

    <!-- DERECHA: Menú de usuario -->
    <?php if (isset($_SESSION['usuario_id'])): ?>
 <div class="dropdown" style="margin-left: 30px;">
  <span class="user-name dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer; color: white;">
    Hola, <?php echo $_SESSION['nombre']; ?>
  </span>
  <ul class="dropdown-menu" aria-labelledby="userDropdown">
    <li><a class="dropdown-item" href="perfil.php">Mi Perfil</a></li>
    <li><a class="dropdown-item" href="mis_publicaciones.php">Mis publicaciones</a></li>
    <li><a class="dropdown-item" href="mis_compras.php">Mis Compras</a></li>
    <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
  </ul>
</div>




    <?php endif; ?>
    
  </div>
</nav>
