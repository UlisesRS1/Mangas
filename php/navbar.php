<?php
  include("config.php");
?>
<nav class="navbar navbar-expand-lg color-nav-footer">
  <a class="navbar-brand text-white p-flex align-items-center" href="../index.php">
    <img src="../img/logo.png" width="50" height="50" alt="Logo de mangas reader">
    Manga reader
  </a>

  <!-- Botón de hamburguesa -->
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Contenido del navbar -->
  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
      <!-- Enlaces de navegación -->
      <li class="nav-item">
        <a class="nav-link text-white" href="<?php echo BASE_URL?>index.php">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?php echo BASE_URL; ?>php/upload_manga.php">Subir manga</a>
      </li>
      <!-- Agrega más enlaces según sea necesario -->
    </ul>
  </div>
</nav>

<script src="<?php echo BASE_URL; ?>js/bootstrap.min.js"></script>

<style>
  .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255, 255, 255, 1%29' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
  }
</style>
