<?php

use App\Producto;
require "includes/app.php";
include 'includes/templates/header.php';

$idTipoproducto = $_GET["tipoProducto"] ?? null;
$Tipoproducto = "";
$perPage = 12;
$currentPage = $_GET["page"] ?? 1;
$search = $_GET["search"] ?? null;
$filtro = $_GET["filtroTienda"] ?? null;
$ordenar = $_GET["ordenar"] ?? null;
// Si el usuario selecciona un tipo de producto, se filtra por ese tipo.
// Si no, se muestran todos los productos.
// Si el usuario selecciona una tienda, se filtra por esa tienda.


// Si la variable search no es nula, se busca por el nombre del producto, si no, se muestran todos los productos por el tipo de producto o tiendas.
if ($search != null) {
    $parametro = "search";
    $parametroValor = $_GET["search"];
    $busqueda = $_GET["search"];
    if ($filtro != null) {
        if($ordenar){
            $orden = $ordenar == 1 ? "asc" : "desc";
            $cuentaProductos = count(Producto::all( param: $busqueda , extras: "and Tiendas_idTiendas= :param3 order by precio {$orden}", where : "nombre" , param2: $busqueda, param3: $filtro));
        }else{
            $cuentaProductos = count(Producto::all( param: $busqueda , extras: "and Tiendas_idTiendas= :param3", where : "nombre" , param2: $busqueda, param3: $filtro));
        }
        
        
    } else {
        
        $cuentaProductos = count(Producto::all($busqueda, "order by idProducto", "nombre", $busqueda));// equal es lo de la condicion de igualdad o like.
        var_dump($cuentaProductos);
        
    }
} else {
    if ($filtro != null) {
        $parametro = "tipoProducto";
        $parametroValor = $idTipoproducto;

        $cuentaProductos = count(Producto::all($idTipoproducto, "and Tiendas_idTiendas={$filtro}"));
    } else {

        $parametro = "tipoProducto";
        $parametroValor = $idTipoproducto;
        $cuentaProductos = count(Producto::all(extras: "where id_TipoProducto= :param1", param2: $idTipoproducto));
    }
}



$paginas = ceil($cuentaProductos / $perPage);

switch ($idTipoproducto) {
    case 1:
        $Tipoproducto = "Procesadores";
        break;
    case 2:
        $Tipoproducto = "Tarjetas gráficas";
        break;
    case 3:
        $Tipoproducto = "Placa madre";
        break;
    case 4:
        $Tipoproducto = "Ram";
        break;
    case 5:
        $Tipoproducto = "Almacenamiento";
        break;
    case 6:
        $Tipoproducto = "Gabinete";
        break;
    case 7:
        $Tipoproducto = "Fuente de poder";
        break;
    case 8:
        $Tipoproducto = "Refrigeración";
        break;
    case 9:
        $Tipoproducto = "Monitor";
        break;
    case 10:
        $tipoproducto = "Teclado";
        break;
    case 11:
        $Tipoproducto = "Mouse";
        break;
}
$x = ($currentPage - 1) * $perPage;
$y = $perPage;

if ($search != null) {
    if ($filtro != null) {
        if($ordenar){
            $orden = $ordenar == 1 ? "asc" : "desc";
            $productos = Producto::all( param: $busqueda , extras: "and Tiendas_idTiendas= :param3 order by precio {$orden} limit {$x},{$y}", where : "nombre" , param2: $busqueda, param3: $filtro);
        }else{
            $productos = Producto::all( param: $busqueda , extras: "and Tiendas_idTiendas= :param3 order by idProducto limit {$x},{$y}", where : "nombre" , param2: $busqueda, param3: $filtro);
        }
    } else {
        if($ordenar){
            $orden = $ordenar == 1 ? "asc" : "desc";
            $productos = Producto::all(param: $busqueda, extras: "order by precio {$orden} limit {$x},{$y}", where: "nombre", param2: $busqueda);
        }else{
            $productos = Producto::all(param: $busqueda, extras: "order by idProducto limit {$x},{$y}", where: "nombre", param2: $busqueda);
        }
    }
} else {
    if ($filtro != null) {

        $filtro = $_GET["filtroTienda"];
        if($ordenar){
            $orden = $ordenar == 1 ? "asc" : "desc";
            $productos = Producto::all($idTipoproducto, " and Tiendas_idTiendas=:param3 order by precio {$orden} limit {$x},{$y} ",param2:$idTipoproducto, param3:$filtro);
        }else{
            $productos = Producto::all($idTipoproducto, " and Tiendas_idTiendas=:param3 order by idProducto limit {$x},{$y} ",param2:$idTipoproducto, param3:$filtro);
        }
    } else {
        if($ordenar){
            $orden = $ordenar == 1 ? "asc" : "desc";
            $productos = Producto::all($idTipoproducto, "order by precio {$orden} limit {$x},{$y}", param2: $idTipoproducto);
        }else{
            $productos = Producto::all($idTipoproducto, "order by idProducto limit {$x},{$y}", param2: $idTipoproducto);
        }
    }
}
var_dump($filtro);
?>


<main class="container container-buscar-producto">
    <section class="header-buscarProducto">
        <h3 class="title-filtrar">Filtrar Por Tienda</h3>
        <div class="container-btns-filtrar">
            <i class="fa-solid fa-angle-left glider-prev-filtro"></i>
            <div class="glider-carousel-filtro">
                <a href="buscarProducto.php?<?= $parametro ?>=<?= $parametroValor ?>&filtroTienda=<?= 3 ?>" class="btn-filtrarTienda <?= $filtro == 3 ? "tienda-seleccionado" : "" ?> " value="3">Invasion Gamer</a>
                <a href="buscarProducto.php?<?= $parametro ?>=<?= $parametroValor ?>&filtroTienda=<?= 4 ?>" class="btn-filtrarTienda <?= $filtro == 4 ? "tienda-seleccionado" : "" ?> " value="4">LaPolar</a>
                <a href="buscarProducto.php?<?= $parametro ?>=<?= $parametroValor ?>&filtroTienda=<?= 1 ?>" class="btn-filtrarTienda <?= $filtro == 1 ? "tienda-seleccionado" : "" ?> " value="1">SpDigital</a>
                <a href="buscarProducto.php?<?= $parametro ?>=<?= $parametroValor ?>&filtroTienda=<?= 2 ?>" class="btn-filtrarTienda <?= $filtro == 2 ? "tienda-seleccionado" : "" ?> " value="2">PcFactory</a>

            </div>
            <i class="fa-solid fa-angle-right glider-next-filtro"></i>
        </div>

    </section>


    <section class="main-buscarProducto">
        <div class="header-main__filtrar">

            <p class="span-orange">Hardware > <span><?php
                                                    if ($Tipoproducto) {
                                                        echo $Tipoproducto;
                                                    } else {
                                                        echo $search;
                                                    }
                                                    ?></span></p>
            <div class="ordenar-container">

                Ordenar Por
                <select name="ordenar" id="">
                    <option value="0">-- Seleccione --</option>
                    <option value="1">Menor Precio</option>
                    <option value="2">Mayor Precio</option>
                    
                </select>
            </div>
        </div>
    </section>

    <section class="container__productos-encontrados">
        <?php foreach ($productos as $producto) : ?>


            <a class="card" href="producto.php?idProducto=<?= $producto->idProducto ?>">
                <div class="imgproducto">
                    <img src="<?= $producto->link ?>"></img>
                </div>
                <div class="nombreProducto">
                    <label><?= $producto->nombre ?></label>
                </div>
                <div class="info">
                    <label class="precio span-orange">$<?= number_format($producto->precio , 0, ',', '.') ?></label>
                    <label class="tienda"><?= $producto->Tiendas_idTiendas->tienda ?></label>
                </div>
                </div>
            </a>
        <?php endforeach; ?>

    </section>

    <div class="footer__main-productos-encontrados">
        <p class="resultados-productos-encontrados"><?= $x ?> - <?=$x +12  ?> de <?= $cuentaProductos ?> resultados</p>

        <div class="container-numPaginas">
            <?php if ($paginas > 6) : ?>
                <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= 1 ?><?php if($ordenar):?>&ordenar=<?= $ordenar?><?php endif?>" class="btn-numPagina"><?= 1 ?></a>
                <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= 2 ?>" class="btn-numPagina"><?= 2 ?></a>
                <?php if ($currentPage == $paginas) : ?>
                    <p>...</p>
                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $currentPage - 2 ?><?php if($ordenar):?>&ordenar=<?= $ordenar?><?php endif?>" class="btn-numPagina"><?= $currentPage - 2 ?></a>

                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $currentPage - 1 ?>" class="btn-numPagina"><?= $currentPage - 1 ?></a>

                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $paginas ?>" class="btn-numPagina"><?= $paginas ?></a>

                <?php endif; ?>
                <?php if ($currentPage <= 2) : ?>
                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= 3 ?>" class="btn-numPagina"><?= 3 ?></a>
                    <p>...</p>
                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $paginas ?>" class="btn-numPagina"><?= $paginas ?></a>

                <?php elseif ($currentPage == 3) : ?>
                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $currentPage ?>" class="btn-numPagina"><?= $currentPage ?></a>

                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $currentPage + 1 ?>" class="btn-numPagina"><?= $currentPage + 1  ?></a>
                    <p>...</p>
                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $paginas ?>" class="btn-numPagina"><?= $paginas ?></a>

                <?php endif; ?>
                <?php if ($currentPage >= 4 && $currentPage <= $paginas - 3) : ?>
                    <p>...</p>
                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $currentPage - 1 ?>" class="btn-numPagina"><?= $currentPage - 1 ?></a>

                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $currentPage ?>&ordenar=1" class="btn-numPagina"><?= $currentPage ?></a>

                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $currentPage + 1 ?>&ordenar=1" class="btn-numPagina"><?= $currentPage + 1 ?></a>
                    <p>...</p>
                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $paginas ?>&ordenar=1" class="btn-numPagina"><?= $paginas ?></a>

                <?php endif; ?>


                <?php if ($currentPage < $paginas && $currentPage > $paginas - 3) : ?>
                    <p>...</p>
                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $currentPage - 1 ?>&ordenar=1" class="btn-numPagina"><?= $currentPage - 1 ?></a>

                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $currentPage ?>&ordenar=1" class="btn-numPagina"><?= $currentPage ?></a>
                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $currentPage + 1 ?>&ordenar=1" class="btn-numPagina"><?= $currentPage + 1 ?></a>
                    <?php if ($currentPage <  $paginas - 1) : ?>
                        <p>...</p>
                        <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $paginas ?>&ordenar=1" class="btn-numPagina"><?= $paginas ?></a>

                    <?php endif; ?>
                <?php endif; ?>


            <?php else : ?>
                <?php for ($i = 0; $i < $paginas; $i++) : ?>
                    <a href="buscarProducto.php?<?php if ($idTipoproducto) : ?><?= "tipoProducto={$idTipoproducto}" ?><?php else : ?><?= "search={$search}" ?><?php endif; ?><?= $filtro ? "&filtroTienda={$filtro}" : ""?>&page=<?= $i + 1 ?><?php if($ordenar):?>&ordenar=<?= $ordenar?><?php endif?>" class="btn-numPagina"><?= $i + 1 ?></a>
                <?php endfor; ?>
            <?php endif; ?>



        </div>
    </div>
</main>



<?php
include 'includes/templates/footer.php';
?>