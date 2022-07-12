<?php
require "includes/app.php";

use App\Comentario;
use App\detalleProducto;
use App\Producto;
use App\Rating;
use App\Setup;
use App\Tiendas;

include 'includes/templates/header.php';


if (!$_GET["idProducto"]) {
    header("Location: index.php");
}
$idProducto = $_GET["idProducto"];

// Se busca el producto por id.

$producto = Producto::find($idProducto);
$producto->sumarVistas();


// Se busca el detalle del Producto por id.

$detalleproducto = $producto->Detalle_idDetalle == null ? null : detalleProducto::find($producto->Detalle_idDetalle);

$perfilAutenticado = $_SESSION["login"] ?? null;

$rating = null;

// Si el usuario está autenticado, se busca el rating del producto y el setup del Usuario.
if ($perfilAutenticado != null) {
    $setup = Setup::find("Usuario_idUsuario", $perfilAutenticado->idUsuario);

    $ratingArray = Rating::find($idProducto, $perfilAutenticado->idUsuario);

    $rating = array_shift($ratingArray);
}

// Se busca el comentario del producto.

$comentarios = Comentario::find("id_Producto", $idProducto);
$productosRelacionados = Producto::allApp($producto->id_TipoProducto, "limit 8");






?>


<main class="container producto_detalle-container">

    <div class="calification-producto">

        <?php if ($rating != null) : ?>
            <?php for ($i = 1; $i < 6; $i++) : ?>
                <?php if ($i == $rating->numeroRating) : ?>
                    <button class="star-calification selected" value="<?= $i  ?>" data-id=<?= $idProducto ?>>
                        &#9734;
                    </button>
                <?php else : ?>
                    <button class="star-calification" value="<?= $i  ?>" data-id=<?= $idProducto ?>>
                        &#9734;
                    </button>

                <?php endif; ?>

            <?php endfor; ?>
        <?php else : ?>

            <?php for ($i = 1; $i < 6; $i++) : ?>
                <button class="star-calification" value="<?= $i  ?>" data-id=<?= $idProducto ?>>
                    &#9734;
                </button>
            <?php endfor; ?>
        <?php endif; ?>

        <span class="calificacion-producto__puntaje"><?= $rating ? $rating->numeroRating . ".0/5.0" : "0/5.0" ?> </span>
    </div>


    <section class="container-producto-seleccionado">

        <div class="producto-seleccionado-left">

            <?php if (!($producto->id_TipoProducto == 10 || $producto->id_TipoProducto == 11)) : ?>

                <?php if ($perfilAutenticado) : ?>
                    <div class="container-addItem">
                        <div class="container-contentText">
                            <p>Agregar a Setup</p>
                            <i class="fa-solid fa-angle-down downArrow_AddSetup"></i>
                        </div>
                        <div tabindex="-1" class="dropdown-menuAdd">

                            <?php if ($setup) : ?>
                                <?php foreach ($setup as $dato) : ?>
                                    <button tabindex="0" value="<?= $dato->nombre ?>" data-productoId="<?= $producto->idProducto ?>"><?= $dato->nombre ?></button>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>No hay Setup</p>
                            <?php endif; ?>


                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="img-seleccionado">
                <div class="datos-left">
                    <img src="<?= $producto->link ?>" alt="">
                    <hr>
                    <span class="titulo-tienda"><?= $producto->Tiendas_idTiendas->tienda ?></span>

                    <span class="subtitulo-left"><?= $producto->nombre ?></span>
                    <span class="precio-producto">Precio: <span class="precio-numeros">$<?= number_format($producto->precio, 0, ',', '.')  ?></span></span>
                </div>
                <a href="<?= $producto->linkTienda ?>" target="_blank" class="btn-paginaProducto"><i class="fa-solid fa-up-right-from-square"></i> Ir a la Tienda</a>

            </div>
        </div>
        <div class="producto-seleccionado-right">
            <?php if ($detalleproducto) : ?>
                <?php foreach ($detalleproducto as $key => $detalle) :
                    $stringDetalle = explode(",", $detalle) ?>

                    <?php if ($detalle == "''") : ?>

                        <?php break; ?>
                    <?php endif; ?>
                    <?php if ($key == "idDetalle") : ?>
                        <?php continue; ?>
                    <?php else : ?>
                        <?php if (utf8_decode($stringDetalle[0]) != "0") : ?>
                            <div>
                                <span class="titulo-especifico"><?= $stringDetalle[0] ?></span>
                                <span class="especificacion-especifico"><?= $stringDetalle[1] ?></span>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                <?php endforeach; ?>
            <?php else : ?>

                <p>No hay detalles</p>
            <?php endif; ?>
        </div>

    </section>


    <div class="produtos-relacionados">
        <span>
            Productos Relacionados
            <hr>
        </span>
    </div>

    <div class=" container-carousel">
        <div class="glider-prev">
            <img src="build/Icons/next-left.png" alt="">
        </div>

        <div class="glider-carousel">
            <?php foreach ($productosRelacionados as $relacionado) : ?>


                <a class="card" href="producto.php?idProducto=<?= $relacionado->idProducto ?>">
                    <div class="imgproducto">
                        <img src="<?= $relacionado->link ?>"></img>
                    </div>
                    <div class="nombreProducto">
                        <label><?= $relacionado->nombre ?></label>
                    </div>
                    <div class="info">
                        <label class="precio span-orange">$<?= number_format($relacionado->precio, 0, ',', '.')  ?></label>
                        <label class="tienda"><?= $relacionado->Tiendas_idTiendas->tienda ?></label>
                    </div>
                </a>
            <?php endforeach; ?>

        </div>
        <div class="glider-next">
            <img src="build/Icons/next-right.png" alt="">
        </div>
    </div>

    <div class="comentario-productos">
        <h3>Comentarios</h3>
        <hr>
    </div>

    <section class="comentarios-producto-container">
        <form class="form-comentario" action="Controllers/Comentario/comentarioController.php?action=enviarComentario&idProducto=<?= $idProducto ?>" method="POST">
            <div class="textarea-producto-comentario">
                <textarea name="comentario" id="" cols="30" rows="10" placeholder="Escribir un comentario" required></textarea>
            </div>
            <button type="submit" class="btn-enviarComentario">Enviar</button>
        </form>


        <div class="comentarios-producto-seleccionado">
            <?php
            $i = 0; ?>
            <?php $idComentario_Principal ?>
            <?php foreach ($comentarios as $comentario) : ?>

                <?php if ($comentario->id_Comentario_Respuesta == null) : ?>

                    <div class="comentario-producto">
                        <div class="container-votos">
                            <?php if ($perfilAutenticado) : ?>
                                <?php if (Comentario::findMegusta($perfilAutenticado->idUsuario, $comentario->idComentario)) : ?>
                                    <i class="fa-solid fa-caret-up comentario-votado" data-idComentario=<?= $comentario->idComentario ?>></i>
                                    <span class="count-votes__comentario"><?= $comentario->votoPositivo ?></span>
                                <?php else : ?>
                                    <i class="fa-solid fa-caret-up" data-idComentario=<?= $comentario->idComentario ?>></i>
                                    <span class="count-votes__comentario"><?= $comentario->votoPositivo ?></span>
                                <?php endif; ?>
                            <?php else : ?>
                                <i class="fa-solid fa-caret-up votar-noLogeado" data-idComentario=<?= $comentario->idComentario ?>></i>
                                    <span class="count-votes__comentario"><?= $comentario->votoPositivo ?></span>
                            <?php endif; ?>

                        </div>
                        <div class="container-comentario">
                            <span class="nombre-comentarioTitular"><?= $comentario->id_Usuario->username ?></span>
                            <p class="comentario-comentarioTitular"><?= $comentario->comentario ?></p>

                            <span class="span-orange responder-comentario" id="span-resp-<?= $i ?>">Responder</span>
                        </div>
                        <div class="container-opcionesComentario">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                            <div class="ripper">

                            </div>
                            <div class="dropbox-menuDots d-n">
                                <div class="dropbox-menuDots-item">
                                    <span name="span-reportarComentario" data-id="<?= $comentario->idComentario ?>">Reportar</span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <form action="Controllers/Comentario/comentarioController.php?action=enviarRespuesta&idProducto=<?= $idProducto ?>&idComentario=<?= $comentario->idComentario ?>&idComentario_Principal=<?= $comentario->idComentario ?>" method="POST" class="hidden form-comentario_respuesta" id="form-respuesta-<?= $i ?>">
                        <div class="textarea-producto-comentario">
                            <div class="wrapper-textarea_respuesta">
                                <textarea name="comentario_respuesta" id="" cols="30" rows="10" placeholder="Escribe tu respuesta" required></textarea>
                                <button type="submit" class="btn-enviarComentario btn-respuesta">Enviar</button>

                            </div>
                        </div>
                    </form>
                    <?php foreach ($comentarios as $respuesta) : ?>
                        <?php if ($respuesta->idComentario_Principal == $comentario->idComentario) : ?>
                            <?php $i++ ?>
                            <div class="comentario-producto" style="margin-left: 2rem;">
                                <div class="container-votos">
                                    <?php if (Comentario::findMegusta($perfilAutenticado->idUsuario, $respuesta->idComentario)) : ?>
                                        <i class="fa-solid fa-caret-up" data-idComentario=<?= $respuesta->idComentario ?> style="color:  #1D3557;"></i>
                                        <span class="count-votes__comentario"><?= $respuesta->votoPositivo ?></span>
                                    <?php else : ?>
                                        <i class="fa-solid fa-caret-up" data-idComentario=<?= $respuesta->idComentario ?>></i>
                                        <span class="count-votes__comentario"><?= $respuesta->votoPositivo ?></span>
                                    <?php endif; ?>

                                </div>
                                <div class="container-comentario">
                                    <span class="nombre-comentarioTitular"><?= $respuesta->id_Usuario->username ?></span>
                                    <p class="comentario-comentarioTitular"><span class="subrayado">@<?= Comentario::find("idComentario", $respuesta->idComentario_Principal)[0]->id_Usuario->username ?></span> <?= $respuesta->comentario ?></p>

                                    <span class="span-orange responder-comentario" id="span-resp-<?= $i ?>">Responder</span>
                                </div>

                            </div>
                            <form action="Controllers/Comentario/comentarioController.php?action=enviarRespuesta&idProducto=<?= $idProducto ?>&idComentario=<?= $respuesta->idComentario ?>&idComentario_Principal=<?= $respuesta->idComentario_Principal ?>" method="POST" class="hidden form-comentario_respuesta" id="form-respuesta-<?= $i ?>">
                                <div class="textarea-producto-comentario">
                                    <div class="wrapper-textarea_respuesta">
                                        <textarea name="comentario_respuesta" id="" cols="30" rows="10" placeholder="Escribe tu respuesta" required></textarea>
                                        <button type="submit" class="btn-enviarComentario btn-respuesta">Enviar</button>

                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>


                <?php $i++; ?>
                <?php
                $count = 2; ?>

                <?php $i++; ?>
            <?php endforeach; ?>

        </div>
    </section>
</main>

<?php include 'includes/templates/footer.php'; ?>