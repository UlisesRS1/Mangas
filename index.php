<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mangas</title>
    <link rel="icon" href="icon/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style-navbar-footer.css">
</head>
<body>
    <?php
        include("php/navbar.php");
    ?>
       
        <!-- Card, para los mangas, base-->
        <div class="container mt-3">
            <div class="row justify-content-evenly">
            <?php
                include("php/getDataManga.php");
            ?>
            </div>
        </div>

    <?php
        include("php/footer.php");
    ?>
</body>
</html>