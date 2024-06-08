<?php
// manga_view.php
include("database_connection.php");

// Verificar si el parámetro 'id' está presente en la URL
if (isset($_GET['id'])) {
    // Recuperar el valor del parámetro 'id'
    $id = $_GET['id'];

    // Consulta para obtener la URL del documento PDF del manga
    $sql = "SELECT doc_url, id_manga FROM manga_documents WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pdfUrl = htmlspecialchars($row["doc_url"]);
        $manga_id = $row['id_manga'];
    } else {
        die("El documento PDF no existe. Número de filas: " . $result->num_rows);
    }
    $stmt->close();

    // Obtener la lista de capítulos para determinar el anterior y el siguiente
    $sql = "SELECT id FROM manga_documents WHERE id_manga = ? ORDER BY id";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("i", $manga_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $chapter_ids = [];
        while ($row = $result->fetch_assoc()) {
            $chapter_ids[] = $row['id'];
        }
        $current_index = array_search($id, $chapter_ids);
        $previous_id = $current_index > 0 ? $chapter_ids[$current_index - 1] : null;
        $next_id = $current_index < count($chapter_ids) - 1 ? $chapter_ids[$current_index + 1] : null;
    } else {
        die("No se encontraron capítulos para este manga.");
    }
    $stmt->close();
} else {  
    die("No se proporcionó el ID de manera correcta.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor de Manga</title>
    <link rel="icon" href="../icon/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style-navbar-footer.css">
</head>
<body>
    <?php include("navbar.php")?>
    <div class="container mt-4">
        <div id="viewerContainer" class="d-flex justify-content-center">
            <embed src="<?php echo $pdfUrl; ?>" type="application/pdf" width="100%" height="800px">
        </div>
        <div class="row mt-4">
            <div class="col-6 d-flex justify-content-start">
                <?php if ($previous_id): ?>
                    <a href="manga_view.php?id=<?php echo $previous_id; ?>" class="btn btn-primary">
                        Capítulo anterior
                    </a>
                <?php else: ?>
                    <button class="btn btn-primary" disabled>Capítulo anterior</button>
                <?php endif; ?>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <?php if ($next_id): ?>
                    <a href="manga_view.php?id=<?php echo $next_id; ?>" class="btn btn-primary">
                        Capítulo siguiente
                    </a>
                <?php else: ?>
                    <button class="btn btn-primary" disabled>Capítulo siguiente</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
