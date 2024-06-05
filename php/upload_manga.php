<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir manga</title>
    <link rel="icon" href="../icon/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style-navbar-footer.css">
</head>
<body>
    <?php include("navbar.php"); ?>
    <div class="container mt-4">
        <form action="upload_manga_action.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="title">Título del Manga</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Título del Manga" required>
                    <div class="invalid-feedback">
                        Por favor, proporciona un título.
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status">Estado</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="">Selecciona el estado del manga...</option>
                        <?php
                        // Conectar a la base de datos y obtener los estados
                        include("database_connection.php");
                        $sql = "SELECT id_estado, estado FROM estado";
                        $result = $conexion->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='".$row['id_estado']."'>".$row['estado']."</option>";
                            }
                        } else {
                            echo "<option value=''>No hay estados disponibles</option>";
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback">
                        Por favor, selecciona un estado.
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="demography">Demografía</label>
                    <select class="form-control" id="demography" name="demography" required>
                        <option value="">Selecciona una demografía...</option>
                        <?php
                        // Obtener las demografías
                        $sql = "SELECT id_demografia, demografia FROM demografias";
                        $result = $conexion->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='".$row['id_demografia']."'>".$row['demografia']."</option>";
                            }
                        } else {
                            echo "<option value=''>No hay demografías disponibles</option>";
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback">
                        Por favor, selecciona una demografía.
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cover">Portada</label>
                    <input type="file" class="form-control" id="cover" name="cover" accept="image/*" required>
                    <div class="invalid-feedback">
                        Por favor, sube una imagen de portada.
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="synopsis">Sinopsis</label>
                    <textarea class="form-control" id="synopsis" name="synopsis" rows="3" placeholder="Sinopsis del Manga" required></textarea>
                    <div class="invalid-feedback">
                        Por favor, proporciona una sinopsis.
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="chapters">Capítulos en PDF</label>
                    <input type="file" class="form-control" id="chapters" name="chapters[]" accept=".pdf" multiple required>
                    <div class="invalid-feedback">
                        Por favor, sube uno o varios archivos PDF con los capítulos.
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Subir Manga</button>
        </form>
    </div>

    <?php include("upload_manga_result.php"); ?>

    <script src="../js/valid.form.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (<?php echo isset($_SESSION['messages']) ? 'true' : 'false'; ?>) {
                var modal = new bootstrap.Modal(document.getElementById('uploadResultModal'));
                modal.show();
            }
        });

        // Limpiar la sesión después de mostrar los mensajes
        <?php
        if (isset($_SESSION['messages'])) {
            unset($_SESSION['messages']);
        }
        ?>
    </script>
</body>
</html>
