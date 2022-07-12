<?php

use App\Producto;
use App\Setup;
use App\Usuario;

require '../../includes/app.php';

session_start();

// Controller de comunidad

$perfilAutenticado = $_SESSION["login"]?? null;

$action = $_GET["action"]?? null;


switch($action){


    // Busca un usuario por el nombre colocado en el campo de busqueda.
    case "buscarUsuario":
        $nombreUsuario = $_POST["nombreUsuario"]?? null;
        $usuario = Usuario::all("and username like '%{$nombreUsuario}%' limit 7");
        echo json_encode($usuario);

        
        
       
        break;
    
}