<?php

use App\Setup;
use App\Usuario;
use App\Comentario;
use App\Megusta;
use App\Pais;
use App\Producto;
use App\Rating;



require "includes/app.php";
session_start();
$perfilAutenticado = $_SESSION['login'] ?? null;

$perfilPropio = false;
$perfilClickeadoId = $_GET["idUsuario"] ?? null;

if ($perfilAutenticado == null && $perfilClickeadoId == null) {
    session_start();
    $perfilAutenticado = $_SESSION['login'] ?? null;
    if ($perfilAutenticado == null) {
        header("location: ../../index.php");
        return;
    }
}

include 'includes/templates/header.php';


if ($perfilAutenticado == null) {

    $perfilPropio = false;
} else {
    $setupPropios = Setup::find("Usuario_idUsuario", $perfilAutenticado->idUsuario);




    if ($perfilAutenticado->idUsuario == $perfilClickeadoId) {
        $perfilPropio = true;
    } else if ($perfilClickeadoId == null) {
        $perfilPropio = true;
    }
}
if ($perfilPropio) {
    $perfil = $perfilAutenticado;
    $usuariosMegusta = Megusta::all($perfilAutenticado->idUsuario);
} else {
    $perfilNoLogeado = Usuario::find("idUsuario", $perfilClickeadoId);
    $perfil = array_shift($perfilNoLogeado);
    if ($perfilAutenticado) {
        $meGusta = Megusta::find("Usuario_idUsuario", $perfil->idUsuario, $perfilAutenticado->idUsuario);
    }
}


$setup = Setup::find("Usuario_idUsuario", $perfil->idUsuario);

$comentariosPropios = Comentario::all($perfil->idUsuario);

$ratingProductos = Rating::all("Usuario_idUsuario", $perfil->idUsuario);

$tiposProductos = array("Procesador", "Tarjeta Grafica", "Placa Madre", "Ram", "Almacenamiento", "Gabinete", "Fuente de Poder", "Refrigeración", "Monitor");

?>




<main class="container container-account-information">



    <section class="profile-information">
        <?php if ($perfilPropio) : ?>
            <div class="editarPerfil-container">
                <i class="fa fa-edit" aria-hidden="true"></i>

            </div>
        <?php endif; ?>
        <div class="img-container__profile">
            <div class="wrapper-containerProfile">
                <img src="<?= "build/imagenesUsuarios/" . $perfil->avatar ?>" alt="">

                <?php if ($perfilPropio) : ?>
                    <i class="fa fa-camera d-n" aria-hidden="true" onclick="document.getElementById('getAvatarFile').click()"></i>

                <?php endif; ?>
            </div>


        </div>
        <div class="me_gusta-container" data-idUsuario=<?= $perfilClickeadoId ?>>
            <?php if (!empty($meGusta)) : ?>
                <i class="fa fa-heart icon-meGusta selected-heart" aria-hidden="true"></i>

            <?php else : ?>
                <?php if ($perfilPropio) : ?>
                    <i class="fa fa-heart selected-heart" aria-hidden="true"></i>
                <?php else : ?>
                    <?php if ($perfilAutenticado != null) : ?>
                        <i class="fa fa-heart icon-meGusta" aria-hidden="true"></i>
                    <?php else : ?>
                        <i class="fa fa-heart icon-meGusta icon-meGusta__noLog" aria-hidden="true"></i>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <p><?= $perfil->meGusta ?></p>
        </div>
        <div class="container-information container container-informationProfile">
            <p class="username-profile"><?= $perfil->username ?></p>
            <?php if ($perfil->nombre != '') : ?>

                <span class="nombreCompleto-profile"><?= $perfil->nombre ?></span>
            <?php endif; ?>

            <div class="container-descripcion__information">
                <?php if ($perfil->descripcion != '') : ?>
                    <p><?= $perfil->descripcion ?></p>
                <?php else : ?>
                    <p>Todos sabemos que <?= $perfil->username ?> es genial.</p>
                <?php endif ?>
            </div>
            <div class="wrapper-items-information__profile">
                <?php if ($perfil->Pais_idPais != null) : ?>
                    <div class="container-item__profile">
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?= $perfil->buscarPais() ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($perfil->instagram != null) : ?>
                    <div class="container-item__profile">
                        <i class="fa-brands fa-instagram"></i>
                        <span>@<?= $perfil->instagram ?></span>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <div class="container-information container-editProfile container d-n">
            <form action="Controllers/Usuario/perfilController.php?action=actualizarDatos" class="editProfile__form" method="POST" enctype="multipart/form-data">
                <label for="nombre-perfil">Nombre</label>
                <input type="text" id="nombre-perfil" class="txt" value="<?= $perfil->nombre ?>" name="input-nombre">
                <label for="descripcion-perfil">Descripción</label>
                <textarea name="description-textProfile" id="descripcion-perfil" cols="30" rows="3" placeholder="Ingrese una descripción"><?= $perfil->descripcion ?></textarea>
                <div class="container-item__profile">
                    <i class="fa-brands fa-instagram"></i>
                    <input type="text" id="" placeholder="Instagram" class="instagram-editProfile" name="input-instagram" value="<?= $perfil->instagram ?>">
                </div>
                <div class="container-item__profile">
                    <i class="fa-solid fa-location-dot"></i>
                    <select name="input-pais" id="" class="pais-editProfile">
                        <option value="0">Seleccione un país</option>
                        <?php foreach (Pais::all() as $pais) : ?>
                            <option value="<?= $pais->idPais ?>" <?= $perfil->Pais_idPais == $pais->idPais ? "selected" : "" ?>><?= $pais->nombre ?></option>
                        <?php endforeach; ?>

                    </select>


                </div>
                <input type="file" accept="image/jpg, image/jpeg, .png" id="getAvatarFile" class="d-n inputFile-avatar" name="input-image">

                <button class="btn-enviar btn-principales">Guardar Cambios</button>
            </form>

        </div>

    </section>
    <section class="profile-viewStats">
        <section class="viewStats-menuBar">
            <div class="viewStats-option" id="option-comments">
                <i class="fa-solid fa-comment-dots"></i>
                <p>Comentarios</p>
                <div class="bar">
                    <div class="content-bar"></div>
                </div>
            </div>
            <div class="viewStats-option" id="option-rating">
                <i class="fa-solid fa-star"></i>
                <p>Calificaciones</p>
                <div class="bar">
                    <div class="content-bar"></div>
                </div>
            </div>
        </section>
        <section class="viewStats-information" id="stats-comments">
            <?php
            if (!$comentariosPropios) : ?>
                <p>No hay Comentarios</p>

            <?php else : ?>

                <?php foreach ($comentariosPropios as $comentario) :
                    $idProducto = $comentario->id_Producto;
                    $producto = Producto::find($idProducto);

                ?>
                    <div class="container-estadisticas__profile">
                        <a href="producto.php?idProducto=<?= $idProducto ?>" class="titulo-estadisticas__profile"><?= $producto->nombre ?></a>
                        <p class="desc-estadisticas__profile"><?= $comentario->comentario ?></p>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

        </section>
        <section class="viewStats-information d-n" id="stats-rating">
            <?php
            if (!$ratingProductos) : ?>
                <p>No hay Calificaciones</p>

            <?php else : ?>

                <?php foreach ($ratingProductos as $rating) :
                    $idProducto = $rating->Producto_idProducto;
                    $producto = Producto::find($idProducto);
                ?>
                    <div class="container-estadisticas__profile">
                        <a href="producto.php?idProducto=<?= $producto->idProducto ?>" class="titulo-estadisticas__profile"><?= $producto->nombre ?></a>
                        <div class="container-rating__profile-stat">
                            <i class="fa fa-star span-orange" aria-hidden="true"></i>
                            <span><?= $rating->numeroRating ?> / 5</span>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>







        </section>
    </section>
    <section class="profile-setup">
        <?php $listaPrecios;
        if ($setup) : ?>

            <div class="header-modal">
                <div class="item-header">

                    <select class="select-setup__profile">
                        <?php $i = 0;
                        foreach ($setup as $dato) :

                            $total = 0;
                            foreach ($dato->producto_setup as $producto) {

                                $total += $producto["precio"];
                            }
                            $listaPrecios[] = $total;

                        ?>
                            <option value="<?= $i ?>"><?= $dato->nombre ?></option>
                            <?php $i++; ?>
                        <?php endforeach ?>
                    </select>
                    <div class="setup-profile-total">

                        Total: <span class="span-orange">$ <?= number_format($listaPrecios[0], 0, ',', '.') ?></span>
                    </div>

                </div>

                <div class="item-header">
                    <?php if ($perfilPropio) : ?>
                        <i class="fa-solid fa-trash-can"></i>

                        <i class="fa-solid fa-plus"></i>

                    <?php else : ?>
                        <?php if ($perfilAutenticado != null) : ?>
                            <i class="fa fa-copy" aria-hidden="true" data-id="<?= $perfil->idUsuario ?>"></i>


                        <?php endif; ?>
                    <?php endif ?>
                </div>


            </div>
            <div class="container-setup-profile">

                <?php $index = 0;
                foreach ($setup as $dato) : ?>

                    <table class="container-setup__table <?= $index == 0 ? "" : "d-n" ?>" id="setup-<?= $index ?>">

                        <thead>
                            <th>Componente</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                        </thead>
                        <?php $i = 0;
                        while ($i < 9) : ?>
                            <?php $existe = false ?>
                            <?php foreach ($dato->producto_setup as $producto) : ?>
                                <?php if ($producto["id_TipoProducto"] == $i + 1) : ?>
                                    <tr class="table-row-setup">
                                        <td data-label="Componente"><?= $tiposProductos[$i] ?></td>
                                        <td data-label="Nombre"><?= $producto["nombre"]  ?></td>
                                        <td data-label="Precio">$ <?= number_format($producto["precio"], 0, ',', '.')  ?><?php if($perfilPropio):?> <i class="fa fa-close" data-label="precio" data-idProducto="<?= $producto["idProducto"]?>" aria-hidden="true"></i> <?php endif;?></td>
                                        
                                    </tr>
                                    <?php $existe = true ?>
                                <?php endif ?>



                            <?php endforeach; ?>
                            <?php if (!$existe) : ?>
                                <tr class="table-row-setup">
                                    <td data-label="Componente"><?= $tiposProductos[$i]  ?></td>
                                    <td data-label="Nombre">Sin <?= $tiposProductos[$i]  ?></td>
                                    <td data-label="Precio">$0</td>
                                </tr>
                            <?php endif; ?>
                            <?php $i++ ?>



                        <?php endwhile; ?>







                    </table>
                <?php $index++;
                    $i++;
                endforeach; ?>
            </div>
        <?php else : ?>
            <div class="header-modal">

                <div class="item-header">
                    <?php if ($perfilPropio) : ?>

                        <i class="fa-solid fa-plus"></i>


                    <?php endif ?>
                </div>


            </div>
            <p class="no-setup">No hay datos</p>
        <?php endif ?>

    </section>

</main>

<?php if($perfilPropio):?>

    <div class="wrapper-seguidoresProfile">
    <div class="container-seguidoresProfile">
        <?php foreach ($usuariosMegusta as $megusta) : ?>
            <?php
            $array = Usuario::find("idUsuario", $megusta->Usuario_idUsuarioLogeado);
            $usuario = array_shift($array); 
            ?>
            <a href="perfil.php?idUsuario=<?= $usuario->idUsuario ?>" class="container-usuario">
                <img src="build/imagenesUsuarios/<?=$usuario->avatar? $usuario->avatar : "avatar-placeholder.png"?>" alt="imagenAvatar" class="img-usuario">
                <p class="nombre-usuario"><?= $usuario->username ?></p>
            </a>
        <?php endforeach; ?>
        
    </div>


</div>
<?php endif;?>

<?php

include 'includes/templates/footer.php';

?>

<script type="text/javascript">
    $(".item-header .fa-copy").on("click", function() {
        var setups = <?php echo json_encode($setupPropios); ?>;


        var setupsArray = [];
        setups.forEach(function(setup) {

            setupsArray.push({
                id: setup.idSetup,
                nombre: setup.nombre

            });
        });


        var model = [];

        setupsArray.forEach(element => {
            model[element.id] = element.nombre;
        });
        let bloqueElemento = $(".select-setup__profile option:selected");
        let bloqueElementoSetupFloat = $(
            ".select-setup__profile-float option:selected"
        );
        let idUsuario = <?= $perfil->idUsuario ?>;

        let nombreCopiar = $(bloqueElemento).text();


        Swal.fire({
            imageUrl: "build/img/desktop.svg",
            imageWidth: 150,
            imageHeight: 150,
            title: "Seleccione un setup",
            input: "select",

            inputOptions: model,
            inputPlaceholder: 'Seleccione un setup',
            inputValidator: function(value) {
                return new Promise(function(resolve, reject) {
                    if (value !== '') {
                        resolve();
                    } else {
                        resolve('Necesitas seleccionar un setup');
                    }
                });
            },
            confirmButtonText: 'Copiar a Setup',
            showDenyButton: true,
            denyButtonText: 'Crear un Setup',


        }).then((result) => {
            if (result.isConfirmed) {
                console.log(result);

                let idSetup = result.value;
                $.ajax({
                    type: "POST",
                    url: "./Controllers/Setup/setupController.php?action=copiarSetup",
                    data: {
                        nombreCopiar,
                        idUsuario,
                        idSetup
                    },
                    success: function(response) {
                        console.log(response);
                        var jsonData = JSON.parse(response);

                        if (jsonData.success == "1") {
                            Swal.fire({
                                toast: true,
                                icon: 'success',
                                title: 'Se copió el setup',
                                position: 'top-right',
                                showConfirmButton: false,
                                timer: 2000,
                            })

                        } else {
                            Swal.fire({
                                toast: true,
                                icon: 'error',
                                title: 'No se pudo copiar el setup',
                                position: 'top-right',
                                showConfirmButton: false,
                                timer: 2000,

                            })
                        }
                    },
                });

            } else if (result.isDenied) {
                Swal.fire({
                    title: "Agregar Setup",
                    html: `<form action='Controllers/Setup/setupController.php?action=copiarSetup' method='POST' class="form-agregarSetup">
        <input type="text" id="setup-nombre" class="swal2-input" placeholder="Ingrese el nombre" name='setup-nombre'>
        <input type="text" id="setup-nombreCopiar" class="swal2-input d-n" placeholder="Ingrese el nombre" name='nombreCopiar' value="${nombreCopiar}">
        <input type="text" id="setup-idUsuario" class="swal2-input d-n" placeholder="Ingrese el nombre" name='idUsuario' value="${idUsuario}">
        </form>`,
                    confirmButtonText: "Agregar",
                    preConfirm: () => {
                        let setupNombre =
                            Swal.getPopup().querySelector("#setup-nombre").value;

                        if (!setupNombre) {
                            Swal.showValidationMessage(`Ingresa un nombre para tu setup`);
                        } else {
                            $(".form-agregarSetup").submit();
                        }
                    },
                });
            }



        });
    });
</script>