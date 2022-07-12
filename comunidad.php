<?php

use App\Comentario;
use App\Megusta;
use App\Setup;
use App\Usuario;

$comunidad = true;
require "includes/app.php";
include 'includes/templates/header.php';

$perfilAutenticado = $_SESSION['login'] ?? null;
$usuariosVeteranos = Usuario::find("id_TipoUsuario", 2, "and confirmacion = 1 order by meGusta desc LIMIT 10");

$setupPopulares = Setup::all("order by copias desc");
$meGusta = $perfilAutenticado != null ? Megusta::all($perfilAutenticado->idUsuario, " and idComentario is null", null) : null;

?>

<main class="container container-community">

    <section class="accounts-masVeteranos">
        <h3 class="title">Más veteranos</h3>

        <div class="items-comunity container">
            <?php foreach ($usuariosVeteranos as $usuario) : ?>
                <a href="perfil.php?idUsuario=<?= $usuario->idUsuario ?>">

                    <div class="item">

                        <img src="build/imagenesUsuarios/<?= $usuario->avatar? $usuario->avatar: "avatar-placeholder.png" ?>" alt="">
                        <div class="container-information__item">
                            <span class="user-item"><?= $usuario->username ?></span>
                            <?php if ($usuario->descripcion != '') : ?>
                                <p class="description-item"><?= $usuario->descripcion ?></p>
                            <?php else : ?>
                                <p class="description-item">Todos sabemos que <?= $usuario->username ?> es genial.</p>

                            <?php endif ?>

                            <div class="container-stats">
                                <div class="item-stat">
                                    <i class="fa fa-heart-o span-orange" aria-hidden="true"></i>
                                    <span><?= $usuario->meGusta ?></span>
                                </div>

                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>

        </div>
    </section>
    <section class="setups-masPopulares">
        <h3 class="title">Setup Populares</h3>

        <div class="items-comunity container">
            <?php foreach ($setupPopulares as $setup) : ?>
                <a href="perfil.php?idUsuario=<?= $setup->Usuario_idUsuario ?>">
                    <div class="item">

                        <img src="build/img/img_SetupIcon.jpeg" alt="">
                        <div class="container-information__item">
                            <span class="user-item"><?= $setup->nombre ?></span>


                            <div class="container-stats">
                                <div class="item-stat">
                                    <div class="stat">
                                        <i class="fa-solid fa-clipboard span-orange"></i>
                                        <span><?= $setup->copias ?></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>

        </div>
    </section>

    <section class="community-search-container">
        
        <?php if ($perfilAutenticado) : ?>
            <h3 class="title">Usuarios Seguidos</h3>
            <?php foreach ($meGusta as $megusta) : ?>
                <?php
                $array = Usuario::find("idUsuario", $megusta->Usuario_idUsuario);
                $usuario = array_shift($array) ;?>
                
                <div class="item container">
                    <a href="perfil.php?idUsuario=<?= $usuario->idUsuario ?>" class="information-left_account">
                        <img src="build/imagenesUsuarios/<?= $usuario->avatar ?>" alt="">
                        <div class="container-information__item">
                            <span class="user-item"><?= $usuario->username ?></span>
                            <p class="description-item"><?= $usuario->descripcion == "" ? "Todos sabemos que {$usuario->username} es genial" : $usuario->descripcion ?></p>

                        </div>
                    </a>
                    <div class="container-stats">
                        <div class="item-stat">
                            <i class="fa fa-heart-o span-orange" aria-hidden="true"></i>
                            <span><?= $usuario->meGusta ?></span>
                        </div>
                        <div class="item-stat">
                            <i class="fa fa-comment-o" aria-hidden="true"></i>
                            <span><?= Comentario::contarComentarios($usuario->idUsuario) ?></span>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="container-community-noLogeado">
                <p class="description-community">Debes iniciar sesión para ver tus seguidores</p>
                <button class="btn-mostrarLogin container-icon-login">Iniciar Sesión</button>

            </div>
        <?php endif; ?>


    </section>
</main>




<?php include 'includes/templates/footer.php'; ?>