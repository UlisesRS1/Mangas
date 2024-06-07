<?php
// manga_view.php
include("database_connection.php");

// Verificar si el parámetro 'id' está presente en la URL
if (isset($_GET['id'])) {
    // Recuperar el valor del parámetro 'id'
    $id = $_GET['id'];

    // Consulta para obtener la URL del documento PDF del manga
    $sql = "SELECT doc_url FROM manga_documents WHERE id = ?";
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
    } else {
        die("El documento PDF no existe. Número de filas: " . $result->num_rows);
    }
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
    </div>
</body>
</html>
