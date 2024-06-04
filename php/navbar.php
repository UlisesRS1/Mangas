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
        <a class="nav-link text-white" href="<?php echo BASE_URL; ?>php/upload_manga.php">Subir manga</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="#">Enlace 2</a>
      </li>
      <!-- Agrega más enlaces según sea necesario -->
    </ul>
  </div>
</nav>

<script src="<?php echo BASE_URL; ?>js/bootstrap.min.js"></script>
