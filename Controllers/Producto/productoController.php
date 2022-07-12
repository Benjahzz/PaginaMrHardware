<?php

use App\Producto;
use App\Setup;
require '../../includes/app.php';

session_start();

// Controlador de producto

$perfilAutenticado = $_SESSION["login"]?? null;

$action = $_GET["action"]?? null;




switch($action){

    // Usuario califica un producto del 1 - 5 estrellas y se registra en la base de datos mediante el metodo calificarProducto().
    // 

    case "calificarProducto":
        $calificacion =  $_POST["calificacion"]?? null;
        $idProducto =  $_POST["idProducto"]?? null;
        if($perfilAutenticado == null){
            echo "error";
            return "error";
        }
        $idUsuario =  $perfilAutenticado->idUsuario;
        
        if($calificacion >= 1 && $calificacion <=5){
            
            if(Producto::calificarProducto($calificacion, $idProducto,$idUsuario)){
                return "ok";
            }else{
                echo "error";
                return;
            }
           
        }
        
       
        break;
}