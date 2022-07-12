<!-- Menu-Aside de la pagina -->
<?php

use App\Producto;

$productos = Producto::all();




?>
<div class="wrapper-menuH">
  <div class="menu-aside menu-principal">
    <section class="bienvenidaH">
      <div class="container-name-menu">
        <span> <?= $auth ? "Hola, " . $auth->username : "Bienvenido" ?></span>
      </div>
      <hr>
    </section>
    <section class="cuerpoH">
      <span class="tituloH">Productos</span>
      <div class="conjuntoH abrir-hardwareH">
        <span class="opcionH">Hardware</span>
        <i class="far fa-angle-right irH disparejo"></i>
      </div>
      <div class="conjuntoH abrir-perifericosH">
        <span class="opcionH ">Periféricos</span>
        <i class="far fa-angle-right irH"></i>
    </div>
<hr>
<span class="tituloH">Cuenta y Configuración</span>
          <a href="perfil.php" class="opcionH">Mi Cuenta</a>
          <a href="perfil.php" class="opcionH">Mis Setups</a>
          <a href="Controllers/logoutController.php" class="opcionH">Salir</a>
          <hr>
          <a href="" class="opcionH">Ayuda</a>
          <a href="contactanos.php" class="opcionH">Contáctanos</a>
          <a href="terminosCondiciones.php" class="opcionH">Sobre Nosotros</a>
          <a href="tiendasIntegradas.php" class="opcionH">Tiendas Integradas</a>
          
          
    </section>
    <section class="footerH">
      <hr>
    </section>
  </div>

  <div class="menu-aside menu-hardware">
    <section class="bienvenidaH">
      <span> Hola, Diego Borquez</span>
      <hr>
    </section>
    <section class="cuerpoH">
      <div class="conjuntoH volverH">
        <i class="far fa-angle-right volverH-icono"></i>
        <span class="tituloH subtitulo-opcion">Menú Principal</span>
      </div>
      <hr>
      <form action="buscarProducto.php" method="post">

      <span class="tituloH">Hardware</span>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=1">Procesadores</a>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=2">Tarjetas gráficas</a>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=3">Placa madre</a>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=4">Ram</a>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=5">Almacenamiento</a>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=6">Gabinete</a>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=7">Fuente de poder</a>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=8">Refrigeración</a>
    </section>
    <section class="footerH">
      <hr>
    </section>
  </div>

  <div class="menu-aside menu-perifericos">
    <section class="bienvenidaH">
      <span> Hola, Diego Borquez</span>
      <hr>
    </section>
    <section class="cuerpoH">
      <div class="conjuntoH volverH">
        <i class="far fa-angle-right volverH-icono"></i>
        <span class="tituloH subtitulo-opcion">Menú Principal</span>
      </div>
      <hr>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=9">Monitor</a>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=10">Teclado</a>
      <a class="opcionH" href="buscarProducto.php?tipoProducto=11">Mouse</a>

      </form>
    </section>
    <section class="footerH">
      <hr>
    </section>
  </div>

  <i class="fa-solid fa-xmark close-hamburguer"></i>
</div>