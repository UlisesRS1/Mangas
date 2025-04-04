<?php
/* En este script se debe obtener la información del manga a
 traves de la base de datos para su uso en las cards del inicio (index.php)*/
    include("database_connection.php");
    include("estado_color.php");

    $sql = "SELECT m.id_manga, m.img_url_image, m.nombre_manga, e.estado FROM mangas m INNER JOIN estado e ON m.id_estado = e.id_estado;";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="card border-0 mt-1 mb-3" style="width: 18rem;">';
            echo '<a href="'.BASE_URL.'php/manga_detalle.php?id='.$row["id_manga"].'" class="link-decoration">';
            echo '<img src="'. $row["img_url_image"] .'" class="card-img-top rounded" alt="Card image manga">';
            echo '<div class="card-body p-0">';
            echo '<h5 class="card-title fs-5 m-0">'.$row["nombre_manga"].'</h5>';
            echo '</div>';
            echo '</a>';
            echo estado_color($row['estado']);
            echo '</div>';
        }
    } else {
        echo "No hay mangas disponibles";
    }

