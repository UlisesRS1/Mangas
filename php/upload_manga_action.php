<?php
include("database_connection.php");
session_start();

$_SESSION['messages'] = [];

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $title = $_POST['title'] ?? '';
    $status = $_POST['status'] ?? '';
    $demography = $_POST['demography'] ?? '';
    $synopsis = $_POST['synopsis'] ?? '';

    // Manejar la subida de la imagen de portada
    $cover_dir = "L:/Mangas/Caratulas/";
    $cover_file_name = basename($_FILES["cover"]["name"]);
    $cover_target_file = $cover_dir . $cover_file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($cover_target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es una imagen real
    $check = getimagesize($_FILES["cover"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION['messages'][] = "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    // Verificar si el archivo ya existe
    if (file_exists($cover_target_file)) {
        $_SESSION['messages'][] = "Lo siento, el archivo ya existe.";
        $uploadOk = 0;
    }

    // Verificar el tamaño del archivo
    if ($_FILES["cover"]["size"] > 500000) {
        $_SESSION['messages'][] = "Lo siento, tu archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Permitir ciertos formatos de archivo
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $_SESSION['messages'][] = "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    }

    // Verificar si $uploadOk está establecido a 0 por un error
    if ($uploadOk == 0) {
        $_SESSION['messages'][] = "Lo siento, tu archivo no fue subido.";
    } else {
        if (move_uploaded_file($_FILES["cover"]["tmp_name"], $cover_target_file)) {
            // Insertar los datos en la base de datos
            $cover_url = "/Caratulas/" . $cover_file_name;
            $sql = "INSERT INTO mangas (nombre_manga, id_estado, id_demografia, img_url_image, sinopsis) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sisss", $title, $status, $demography, $cover_url, $synopsis);

            if ($stmt->execute()) {
                $manga_id = $stmt->insert_id; // Obtener el ID del manga insertado
                $_SESSION['messages'][] = "El manga fue subido exitosamente.";
            } else {
                $_SESSION['messages'][] = "Error al insertar el manga en la base de datos: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $_SESSION['messages'][] = "Lo siento, hubo un error al subir tu archivo.";
        }
    }

    // Manejar la subida de los capítulos en PDF
    $chapter_dir = "L:/Mangas/Mangas/" . $title . "/";
    if (!file_exists($chapter_dir)) {
        if (!mkdir($chapter_dir, 0777, true)) {
            $_SESSION['messages'][] = 'Error al crear la carpeta para los capítulos.';
        }
    }

    foreach ($_FILES["chapters"]["tmp_name"] as $key => $tmp_name) {
        $chapter_file_name = basename($_FILES["chapters"]["name"][$key]);
        $chapter_target_file = $chapter_dir . $chapter_file_name;
        $pdfFileType = strtolower(pathinfo($chapter_target_file, PATHINFO_EXTENSION));

        // Verificar si el archivo es un PDF
        if ($pdfFileType != "pdf") {
            $_SESSION['messages'][] = "Lo siento, solo se permiten archivos PDF.";
            continue;
        }

        // Verificar si el archivo ya existe
        if (file_exists($chapter_target_file)) {
            $_SESSION['messages'][] = "Lo siento, el archivo ya existe.";
            continue;
        }

        // Mover el archivo PDF a la carpeta de capítulos
        if (move_uploaded_file($tmp_name, $chapter_target_file)) {
            // Insertar la URL del documento en la tabla manga_documents
            $chapter_url = "/Mangas/" . $title . "/" . $chapter_file_name;
            $sql = "INSERT INTO manga_documents (id_manga, doc_url) VALUES (?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("is", $manga_id, $chapter_url);

            if ($stmt->execute()) {
                $_SESSION['messages'][] = "El capítulo " . $chapter_file_name . " fue subido exitosamente.";
            } else {
                $_SESSION['messages'][] = "Error al insertar el documento en la base de datos: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $_SESSION['messages'][] = "Lo siento, hubo un error al subir el archivo " . $chapter_file_name;
        }
    }

    $conexion->close();
}

header("Location: upload_manga.php");
exit;

