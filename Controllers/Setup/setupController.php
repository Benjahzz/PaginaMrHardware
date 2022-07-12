<?php

use App\Producto;
use App\Setup;
require '../../includes/app.php';

session_start();

//Controlador de Setup

$perfilAutenticado = $_SESSION["login"]?? null;

$action = $_GET["action"]?? null;


switch($action){


    // Actualiza el setup de un usuario cuando este lo registra en su setup. Se busca mediante el nombre del setup y la id del usuario.

    case "actualizarSetup":
        $nombreSetup =  $_POST["setupNombre"]?? null;
        $setup = Setup::find("nombre", $nombreSetup, "and Usuario_idUsuario = {$perfilAutenticado->idUsuario}");
        if($nombreSetup == null){
            header("location: ../../perfil.php");
        }
        if($perfilAutenticado != null){
            $productoId = $_POST["productoId"]?? null;
            $producto = Producto::find($productoId);
            
            $setup[0]->actualizarSetup($producto);
            $setup = Setup::find("Usuario_idUsuario", $perfilAutenticado->idUsuario);
            
            exit;
            

        };
       
        break;
    
    // Elimina el setup de un usuario dejando en 0 el estado del setup. Se busca mediante el nombre del setup y la id del usuario.
    case "eliminarSetup":
        $nombreSetup =  $_POST["nombreSetup"]?? null;
        $setup = Setup::find("nombre", $nombreSetup, "and Usuario_idUsuario = {$perfilAutenticado->idUsuario}");
        if($nombreSetup == null){
            header("location: ../../perfil.php");
        }
        if($perfilAutenticado != null){
            
            $setup[0]->eliminarSetup();
            exit;
            

        };
        break;

    case "copiarSetup":
        
        $nombreCopiar =  $_POST["nombreCopiar"]?? null;
        $idUsuario = $_POST["idUsuario"]?? null;
        $idSetup = $_POST["idSetup"]?? null;
        $setupNombre = $_POST["setup-nombre"]?? null;
        
        $setup = Setup::find("nombre", $nombreCopiar, "and Usuario_idUsuario = {$idUsuario}");
        
        if($setup != null){
            
            $setup[0]->copiarSetup($perfilAutenticado->idUsuario, $idSetup,$setupNombre);
            echo json_encode(array('success' => 1));
            exit;
        }else{
            echo json_encode(array('error' => 0));
        }
        

        break;
    case "eliminarProducto":
        $idProducto = $_POST["idProducto"]?? null;
        $idSetup = $_POST["nombreSetup"]?? null;
        $setup = Setup::find("nombre", $idSetup, "and Usuario_idUsuario = {$perfilAutenticado->idUsuario}");
        if($idProducto == null || $idSetup == null || $perfilAutenticado == null){ 
            header("location: ../../perfil.php");
        }
        $setup[0]->actualizarSetup($idProducto);

        break;
}