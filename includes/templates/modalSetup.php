<!-- Modal flotante de el/los setup de un usuario -->

<?php

use App\Setup;

$perfilAutenticado = $_SESSION['login'] ?? null; // Si no esta autenticado, se pone null.
// Si esta autenticado, se buscan los setup del usuario.
if($perfilAutenticado != null){
    $setup = Setup::find("Usuario_idUsuario", $perfilAutenticado->idUsuario);
}
// tipos de productos que se pueden agregar a un setup.
$tiposProductos = array("Procesador", "Tarjeta Grafica", "Placa Madre", "Ram", "Almacenamiento", "Gabinete", "Fuente de Poder", "Refrigeración", "Monitor");


?>
<div class="modal-setup">
    <div class="modal-container-setup">
        <div class="header-modal">
            <div class="item-header">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div class="item-header">
                <i class="fa-solid fa-plus"></i>
                <select class="select-setup__profile-float">
                    
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
            </div>
            <div class="item-header close-setup">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        <div class="body-modal">
            

                <?php $index = 0;
                foreach ($setup as $dato) : ?>

                    <div class="grid-container <?= $index == 0 ? "" : "d-n" ?> container-setup__table-float" id="setup-<?= $index ?>">

                        <?php $i = 0;
                        while ($i < 9) : ?>
                            <?php $existe = false ?>
                            <?php foreach ($dato->producto_setup as $producto) : ?>
                                <?php if ($producto["id_TipoProducto"] == $i + 1) : ?>
                                    <div class="item-1 item">
                                        <span class="componente"><?= $tiposProductos[$i] ?></span>
                                        <span class="nombre-componente"><?= $producto["nombre"]  ?></span>
                                        <span class="precio-componente__setup">$<?= $producto["precio"] ?></span>
                                    </div>
                                    <?php $existe = true ?>
                                <?php endif ?>



                            <?php endforeach; ?>
                            <?php if (!$existe) : ?>
                                <div class="item-1 item">
                                    <span class="componente"><?= $tiposProductos[$i]  ?></span>
                                    <span class="nombre-componente">Sin <?= $tiposProductos[$i]  ?></span>
                                    <span class="precio-componente__setup">$0</span>
                                </div>
                                
                            <?php endif; ?>
                            <?php $i++ ?>



                        <?php endwhile; ?>





                        <div class="precio-total-container" style="text-align: right;">
                    Total: <span class="total-setup">$<?= $listaPrecios[0] ?></span>
                </div>

                            </div>
                <?php $index++;
                    $i++;
                endforeach; ?>

                
            </div>
        </div>
    </div>
</div>