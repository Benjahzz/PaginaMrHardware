<?php

use App\Comentario;
use App\detalleProducto;
use App\Megusta;
use App\Pais;
use App\Producto;
use App\Rating;
use App\Reporte;
use App\Setup;
use App\Tiendas;
use App\Usuario;

require 'funciones.php';

// en App.php se llama al autoload de composer, para que cargue todos los archivos de la carpeta vendor.
// Se llama a conexionPDO para que se conecte a la base de datos.
// se llama a la clase PHPmailer para que se pueda enviar correos.

require __DIR__.'\..\Database\ConexionPDO.php';
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../vendor/phpmailer/phpmailer/src/PHPMailer.php";


// se crea un objeto de la clase ConexionPDO
$db = new ConexionPDO();

// se le asigna a cada modelo la conexion a la base de datos en la variable estatic $db.
Usuario::$db = $db;
Producto::$db = $db;
Comentario::$db = $db;
Reporte::$db = $db;
Setup::$db = $db;
Tiendas::$db = $db;
detalleProducto::$db = $db;
Rating::$db = $db;
Pais::$db = $db;
Megusta::$db = $db;

