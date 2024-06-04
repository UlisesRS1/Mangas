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
            <!-- Inicio de card -->
                <!-- <div class="card border-0 mt-1 mb-3" style="width: 18rem;">
                    <img class="card-img-top" src="/Caratulas/promise_neverland.jpg" alt="Card image cap">
                    <div class="card-body p-0">
                        <h5 class="card-title fs-5 m-0">Yakusoku no nebarando</h5>
                        <p class="card-text text-danger p-0">Finalizado</p>
                    </div>
                </div> -->
                <!-- Fin de card -->
            </div>
        </div>

    <?php
        include("php/footer.php");
    ?>
</body>
</html>