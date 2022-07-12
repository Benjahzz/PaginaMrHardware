<?php

use App\Producto;
require "includes/app.php";
include 'includes/templates/header.php';
$vistasDesc = "order by vistas desc limit 10";
$precioAsc = "order by precio asc limit 10";


$productosPopulares = Producto::allListaLimpia($vistasDesc);
$productosRecomendados = Producto::allListaLimpia($precioAsc);





?>
<section class="banner-logo">

    <img src="build/img/icono.png" alt="logo">
    <p class="text-banner">Llega antes que los mineros!!</p>
</section>

<div class="container container-carousel">
    <div class="glider-prev">
        <img src="build/Icons/next-left.png" alt="">
    </div>
    <h2 class="title-section">Populares</h2>
    <div class="glider-carousel">
        <?php foreach ($productosPopulares as $producto) : ?>

            <a href="producto.php?idProducto=<?= $producto->idProducto ?>" class="card card-populares">
                <div class="container-vistasProducto">
                    <span><i class="fa-solid fa-eye"></i></span>
                    <span class="vistasProducto"><?= $producto->vistas ?></span>
                </div>
                <div class="imgproducto">
                    <img src=<?= $producto->link ?>></img>
                </div>
                <div class="nombreProducto">
                    <label><?= $producto->nombre ?></label>
                </div>
                <div class="info">
                    <label class="precio span-orange">$ <?= number_format($producto->precio , 0, ',', '.')  ?></label>
                    <label class="tienda"><?= $producto->Tiendas_idTiendas->tienda ?></label>
                </div>
            </a>


        <?php endforeach; ?>

    </div>
    <div class="glider-next">
        <img src="build/Icons/next-right.png" alt="">
    </div>
</div>

<div class="container container-carousel">
    <h2 class="title-section">Recomendados</h2>
    <div class="glider-prev prev-2">
        <img src="build/Icons/next-left.png" alt="">
    </div>
    <div class="glider-carousel carousel-2">
        <?php foreach ($productosRecomendados as $producto) : ?>

            <a href="producto.php?idProducto=<?= $producto->idProducto ?>" class="card">
                <div class="imgproducto">
                    <img src=<?= $producto->link ?>></img>
                </div>
                <div class="nombreProducto">
                    <label><?=$producto->nombre ?></label>
                </div>
                <div class="info">
                    <label class="precio span-orange">$ <?= number_format($producto->precio , 0, ',', '.')  ?></label>
                    <label class="tienda"><?= $producto->Tiendas_idTiendas->tienda ?></label>
                </div>
            </a>


        <?php endforeach; ?>

    </div>
    <div class="glider-next next-2">
        <img src="build/Icons/next-right.png" alt="">
    </div>
</div>


<?php include 'includes/templates/footer.php' ?>

</html>