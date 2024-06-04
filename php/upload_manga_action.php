<?php
include("database_connection.php");

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
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    // Verificar si el archivo ya existe
    if (file_exists($cover_target_file)) {
        echo "Lo siento, el archivo ya existe.";
        $uploadOk = 0;
    }

    // Verificar el tamaño del archivo
    if ($_FILES["cover"]["size"] > 500000) {
        echo "Lo siento, tu archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Permitir ciertos formatos de archivo
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    }

    // Verificar si $uploadOk está establecido a 0 por un error
    if ($uploadOk == 0) {
        echo "Lo siento, tu archivo no fue subido.";
    // Si todo está bien, intenta subir el archivo
    } else {
        if (move_uploaded_file($_FILES["cover"]["tmp_name"], $cover_target_file)) {
            // Insertar los datos en la base de datos
            $cover_url = "/Caratulas/" . $cover_file_name;
            $sql = "INSERT INTO mangas (nombre_manga, id_estado, id_demografia, img_url_image, sinopsis) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sisss", $title, $status, $demography, $cover_url, $synopsis);

            if ($stmt->execute()) {
                echo "El manga fue subido exitosamente.";
            } else {
                echo "Error al insertar el manga en la base de datos: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Lo siento, hubo un error al subir tu archivo.";
        }
    }

    // Manejar la subida de los capítulos en PDF
    $chapter_dir = "L:/Mangas/Mangas/" . $title . "/";
    if (!file_exists($chapter_dir)) {
        if (!mkdir($chapter_dir, 0777, true)) {
            die('Error al crear la carpeta para los capítulos.');
        }
    }

    foreach ($_FILES["chapters"]["tmp_name"] as $key => $tmp_name) {
        $chapter_file_name = basename($_FILES["chapters"]["name"][$key]);
        $chapter_target_file = $chapter_dir . $chapter_file_name;
        $pdfFileType = strtolower(pathinfo($chapter_target_file, PATHINFO_EXTENSION));

        // Verificar si el archivo es un PDF
        if ($pdfFileType != "pdf") {
            echo "Lo siento, solo se permiten archivos PDF.";
            continue;
        }

        // Verificar si el archivo ya existe
        if (file_exists($chapter_target_file)) {
            echo "Lo siento, el archivo ya existe.";
            continue;
        }

        // Mover el archivo PDF a la carpeta de capítulos
        if (move_uploaded_file($tmp_name, $chapter_target_file)) {
            echo "El capítulo " . $chapter_file_name . " fue subido exitosamente.";
        } else {
            echo "Lo siento, hubo un error al subir el archivo " . $chapter_file_name;
        }
    }

    $conexion->close();
}
