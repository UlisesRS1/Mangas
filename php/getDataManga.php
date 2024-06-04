<?php
/* En este scrip se debe obtener la información del manga a
 traves de la base de datos para su uso en las cards del inicio (index.php)*/

    include("database_connection.php");

    $sql = "SELECT m.img_url_image, m.nombre_manga, e.estado FROM mangas m INNER JOIN estado e ON m.id_estado = e.id_estado;";

    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="card border-0 mt-1 mb-3" style="width: 18rem;">';
            echo '<img src="'. $row["img_url_image"] .'" class="card-img-top" alt="Card image manga">';
            echo '<div class="card-body p-0">';
            echo '<h5 class="card-title fs-5 m-0">'.$row["nombre_manga"].'</h5>';

            echo '<p class="card-text text-danger p-0">'.$row["estado"].'</p>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "No hay mangas disponibles";
    }