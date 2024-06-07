<?php
include("database_connection.php");
session_start();

$_SESSION['messages'] = [];

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $manga_id = $_POST['manga_id'] ?? '';

    // Verificar si el manga_id es válido
    if ($manga_id == '' || !is_numeric($manga_id)) {
        $_SESSION['messages'][] = "ID de manga no válido.";
        header("Location: manga_detalle.php?id=$manga_id");
        exit;
    }

    // Obtener el título del manga desde la base de datos
    $sql = "SELECT nombre_manga FROM mangas WHERE id_manga = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $manga_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $manga_title = $row['nombre_manga'];
    } else {
        $_SESSION['messages'][] = "No se encontró el manga con el ID proporcionado.";
        header("Location: manga_detalle.php?id=$manga_id");
        exit;
    }
    $stmt->close();

    // Manejar la subida de capítulos en PDF
    $chapter_dir = "L:/Mangas/Mangas/" . $manga_title . "/";
    if (!file_exists($chapter_dir)) {
        $_SESSION['messages'][] = 'Error: La carpeta para los capítulos no existe.';
        header("Location: manga_detalle.php?id=$manga_id");
        exit;
    }

    $uploadSuccess = true;

    foreach ($_FILES["chapterFiles"]["name"] as $index => $chapter_file_name) {
        $chapter_target_file = $chapter_dir . basename($chapter_file_name);
        $pdfFileType = strtolower(pathinfo($chapter_target_file, PATHINFO_EXTENSION));

        // Verificar si el archivo es un PDF
        if ($pdfFileType != "pdf") {
            $_SESSION['messages'][] = "Lo siento, solo se permiten archivos PDF.";
            $uploadSuccess = false;
            continue;
        }

        // Verificar si el archivo ya existe
        if (file_exists($chapter_target_file)) {
            $_SESSION['messages'][] = "Lo siento, el archivo $chapter_file_name ya existe.";
            $uploadSuccess = false;
            continue;
        }

        // Mover el archivo PDF a la carpeta de capítulos
        if (move_uploaded_file($_FILES["chapterFiles"]["tmp_name"][$index], $chapter_target_file)) {
            // Insertar la URL del documento en la tabla manga_documents
            $chapter_url = "/Mangas/" . $manga_title . "/" . basename($chapter_file_name);
            $sql = "INSERT INTO manga_documents (id_manga, doc_url) VALUES (?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("is", $manga_id, $chapter_url);

            if ($stmt->execute()) {
                $_SESSION['messages'][] = "El capítulo $chapter_file_name fue subido exitosamente.";
            } else {
                $_SESSION['messages'][] = "Error al insertar el documento $chapter_file_name en la base de datos: " . $stmt->error;
                $uploadSuccess = false;
            }

            $stmt->close();
        } else {
            $_SESSION['messages'][] = "Lo siento, hubo un error al subir el archivo $chapter_file_name.";
            $uploadSuccess = false;
        }
    }

    $conexion->close();

    if (!$uploadSuccess) {
        $_SESSION['messages'][] = "Hubo errores en la subida de algunos capítulos.";
    }
}

header("Location: manga_detalle.php?id=$manga_id");
exit;

