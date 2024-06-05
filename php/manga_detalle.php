<?php
    // ejemplo.php
    include("database_connection.php");
    
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
        <?php 
                //Mostrar la info de la consulta
                echo '<div class="card border-0">';
                echo '<div class="row g-0">';
                echo '<div class="d-flex col-md-4 justify-content-end">';
                echo '<img src="'.htmlspecialchars($row["img_url_image"]).'" class="img-fluid size-img" alt="Imagen del mamga">';
                echo '</div>';
                echo '<div class="col-md-8">';
                echo '<div class="card-body p-3">';
                echo '<h3 class="card-title">'.htmlspecialchars($row["nombre_manga"]).'</h3>';
                echo '<p class="card-text">'.htmlspecialchars($row["sinopsis"]).'</p>';
                echo '<p class="card-text text-secondary p-0">'.htmlspecialchars($row["estado"]).'</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
    
            }else{
                echo '<div class="text-warning">Este manga no existe</div>';
            }
        }else{  
            echo '<div class="text-warning">No se proporciono el ID de manera correcta</div>';
        }
        ?>
    </div>
</body>
</html>
