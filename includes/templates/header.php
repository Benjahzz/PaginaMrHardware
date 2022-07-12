<!-- Header de la Pagina -->

<?php

if (session_status() == 1) {
    session_start();
}


$auth = $_SESSION['login'] ?? null; // Si no existe la variable de sesión login, se crea con valor null.

?>

<?php $comunidadHeader = $comunidad ?? null ?>
<!-- $comunidad es una variable creada en el archivo comunidad.php para comprobar si se está llamando desde el lado de comunidad o productos -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="build/img/iconcircle.png">

    <script src="https://kit.fontawesome.com/b782900f3a.js" crossorigin="anonymous"></script>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="build/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.2.0/css/all.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/glider-js/1.6.6/glider.min.css" integrity="sha512-YM6sLXVMZqkCspZoZeIPGXrhD9wxlxEF7MzniuvegURqrTGV2xTfqq1v9FJnczH+5OGFl5V78RgHZGaK34ylVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;500;600;700&family=Nanum+Gothic&family=Quicksand:wght@300;400;500;600;700&family=Ubuntu:ital,wght@0,300;0,500;0,700;1,300;1,400&display=swap" rel="stylesheet">
    <title>Inicio</title>
</head>

<body>
    <div class="loader">
        <div class="wrapper-dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>
    <header class="header">
        <div class="header-container container">
            <a href="<?= $comunidadHeader? "comunidad.php": "index.php" ?>" class="titulo-principal">
                <h1>MrHardware.<span class="span-orange">cl</span> </h1>
                <p class="span-orange community-p"><?= $comunidadHeader ? "Comunidad" : "" ?></p>
            </a>
            <div class="container-hamburguer open-hamburguer">
                <i class="fa-solid fa-bars menu-hamburguer"></i>
            </div>
            <div class="container-search">

                <?php if ($comunidadHeader) : ?>
                    <input type="text" class="search-comunidad">
                    <div class="search-box d-n">
                    </div>


                <?php else : ?>
                    <input type="text" class="search-principal">
                    <button class="search-button"><i class="fa fa-search"></i></button>
                <?php endif; ?>





            </div>
            <?php if ($comunidadHeader) : ?>
                <a href="index.php" class="button-comunidad">


                    <img src="build/Icons/product.png" alt="">
                    <span class="span-orange">Productos</span>

                </a>
            <?php else : ?>
                <a href="comunidad.php" class="button-comunidad">


                    <img src="build/Icons/chat-group (1).png" alt="">
                    <span class="span-orange">Comunidad</span>

                </a>
            <?php endif; ?>


            <?php if ($auth != null) { ?>
                <div class="wrapper-perfil  <?= $comunidadHeader ? "profile-icon-community" : "" ?>">
                    <div class="container-profile-icon btn-mostrar-login ">
                        <a href="perfil.php" class="container-icon">
                            <i class="fa fa-user-circle" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="wrapper-menu-list">
                        <div class="container-options btn-mostrar-setup">
                            <i class="fa-solid fa-computer"></i>
                        </div>
                        <div class="container-options">
                            <a href="./Controllers/logoutController.php"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>

                            </ul>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="wrapper-perfil  <?= $comunidadHeader ? "profile-icon-community" : "" ?>">
                    <div class="container-profile-icon btn-mostrar-login container-icon-login">
                        <div class="container-icon">
                            <i class="fa fa-user-circle" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>



            <?php } ?>


            <?php include 'includes/templates/modalLogin.php' ?>
            <!-- Modal de Login -->
            <?php include 'includes/templates/modalRegistrar.php' ?>
            <!-- Modal de Registro -->
            <?php include 'includes/templates/menu.php' ?>
            <!-- Menu -->




    </header>