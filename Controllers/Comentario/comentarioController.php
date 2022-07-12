<?php


// Controlador de comentario


use App\Comentario;
use App\Megusta;
use App\Producto;
use App\Setup;
require '../../includes/app.php';

session_start();
$perfilAutenticado = $_SESSION["login"]?? null;

$action = $_GET["action"]?? null;
$productoId = $_GET["idProducto"]?? null;



switch($action){


    // Registra un comentario realizado por un usuario en producto

    case "enviarComentario":
        $comentario = $_POST["comentario"]?? null;
        
        if($comentario == null || $productoId == null || $perfilAutenticado == null){
            header("location: ../../producto.php?idProducto={$productoId}");
        }
        if($perfilAutenticado != null){
            
            Comentario::registrarComentario($comentario,$productoId,$perfilAutenticado->idUsuario);
            header("location: ../../producto.php?idProducto={$productoId}");

            

        };
    
       
        break;

    // Registra una respuesta de un comentario realizado por un usuario en producto

    case "enviarRespuesta":
        $respuesta = $_POST["comentario_respuesta"]?? null;
        $idComentario = $_GET["idComentario"] ?? null;
        $idComentario_Principal = $_GET["idComentario_Principal"] ?? null; 
        if($respuesta == null || $productoId == null || $idComentario == null){
            header("location: ../../producto.php?idProducto={$productoId}");
        }
        if($perfilAutenticado != null){
            
            Comentario::registrarComentario($respuesta,$productoId,$perfilAutenticado->idUsuario, $idComentario,$idComentario_Principal);
            
            header("location: ../../producto.php?idProducto={$productoId}");

            

        };
        break;
        
    // Registra un voto de me gusta o sacar el voto de un comentario realizado por un usuario en producto

    case "votar":
        $idComentario = $_POST["idComentario"] ?? null;
        if($idComentario == null || $perfilAutenticado == null){
            if($productoId != null){
                header("location: ../../producto.php?idProducto={$productoId}");
            }else{
                header("location: ../../index.php");
            }
            
        }
        if(Comentario::findMegusta($perfilAutenticado->idUsuario,$idComentario)){
            Megusta::quitarVoto($idComentario, $perfilAutenticado->idUsuario);

        }else{
            Megusta::votar($idComentario, $perfilAutenticado->idUsuario);

        }

        break;

    case "reportarComentario":
        $idComentario = $_POST["idComentario"] ?? null;
        $idTipoReporte = $_POST["idTipoReporte"] ?? null;
        if($idComentario == null || $perfilAutenticado == null || $idTipoReporte == null){
            echo json_encode(array("error"=>"0"));
            return;
        }
        Comentario::reportarComentario($idComentario,$perfilAutenticado->idUsuario,$idTipoReporte);
        echo json_encode(array("success"=>"1"));
        break;

}