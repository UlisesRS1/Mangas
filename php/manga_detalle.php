<?php
    // ejemplo.php
    include("database_connection.php");
    include("estado_color.php");
    
    // Verificar si el parámetro 'id' está presente en la URL
    if (isset($_GET['id'])) {
        // Recuperar el valor del parámetro 'id'
        $id = $_GET['id'];
        // Consulta para la card con la info del manga
        $sql = "SELECT m.img_url_image, m.nombre_manga, m.sinopsis, e.estado 
                FROM mangas m 
                INNER JOIN estado e ON m.id_estado = e.id_estado 
                WHERE m.id_manga = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);  
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../icon/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style-navbar-footer.css">
    <title><?php echo htmlspecialchars($row["nombre_manga"])?></title>
</head>

<body>
    <?php include("navbar.php")?>
    <div class="container mt-4">
        <div class="row">
            <?php 
            // Mostrar la info de la consulta
            echo '<div class="card border-0">';
            echo '<div class="row g-0">';
            echo '<div class="d-flex col-md-4 justify-content-end">';
            echo '<img src="'.htmlspecialchars($row["img_url_image"]).'" class="img-fluid size-img" alt="Imagen del manga">';
            echo '</div>';
            echo '<div class="col-md-8">';
            echo '<div class="card-body p-3">';
            echo '<h3 class="card-title">'.htmlspecialchars($row["nombre_manga"]).'</h3>';
            echo '<p class="card-text">'.htmlspecialchars($row["sinopsis"]).'</p>';
            echo estado_color($row['estado']);
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
             
            echo '<div class="row">';
            ?>
            <!-- Botón para abrir el modal -->
            <div class="col-md-12 mt-4 justify-content-center d-flex">
                <button type="button" class="btn btn-primary fit-content w-100" data-bs-toggle="modal"
                    data-bs-target="#uploadModal">
                    Subir nuevo capítulo
                </button>
            </div>
            <?php
            // Consulta para obtener los capítulos
            $sql_docs = "SELECT id, doc_url FROM manga_documents WHERE id_manga = ?";
            $stmt_docs = $conexion->prepare($sql_docs);
            $stmt_docs->bind_param("i", $id);
            $stmt_docs->execute();
            $result_docs = $stmt_docs->get_result();
            if ($result_docs->num_rows > 0) {
                echo '<div class="accordion accordion-flush mt-4 col-md-12" id="accordion">';
                $index = 1;
                while ($doc = $result_docs->fetch_assoc()) {
                    echo '<div class="accordion-item">';
                    echo '<h2 class="accordion-header">';
                    echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse' . $index . '" aria-expanded="false" aria-controls="flush-collapse' . $index . '">';
                    echo 'Capítulo ' . $index;
                    echo '</button>';
                    echo '</h2>';
                    echo '<div id="flush-collapse' . $index . '" class="accordion-collapse collapse" data-bs-parent="#accordion">';
                    echo '<div class="accordion-body">';
                    echo '<a href="manga_view.php?id=' . $doc['id'] . '" target="_self">Ver Capítulo ' . $index . '</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    $index++;
                }
                echo '</div>';
            } else {
                echo '<div class="text-warning">No hay capítulos disponibles para este manga.</div>';
            }
        } else {
            echo '<div class="text-warning">Este manga no existe.</div>';
        }
    } else {  
        echo '<div class="text-warning">No se proporcionó el ID de manera correcta.</div>';
    }
    ?>
        </div>
        <!-- Modal -->
        <!-- Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Subir nuevos capítulos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="upload_chapter.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="chapterFiles" class="form-label">Selecciona los archivos PDF de los
                                    capítulos</label>
                                <input class="form-control" type="file" id="chapterFiles" name="chapterFiles[]" multiple
                                    required>
                            </div>
                            <input type="hidden" name="manga_id" value="<?php echo $id; ?>">
                            <button type="submit" class="btn btn-primary">Subir capítulos</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>