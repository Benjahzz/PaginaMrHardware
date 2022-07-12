<?php

namespace App;

use ConexionPDO;
use PDO;

class Reporte
{

    public static ConexionPDO $db;
    public static $errores = [];

    public $idReporte;
    public Usuario $id_Usuario;
    public $id_TipoReporte;
    public Comentario $id_Comentario;

    // Constructor de la clase Tienda.
    public function __construct($args = [])
    {
        $this->idReporte = $args["idReporte"] ?? '';
        $this->id_Usuario = $args["id_Usuario"] ?? '';
        $this->id_TipoReporte = $args["id_TipoReporte"] ?? '';
        $this->id_Comentario = $args["id_Comentario"] ?? '';
    }

    // Funcion para obtener todos los Reportes.
    public static function all()
    {
        $query = "select * from reporte inner join usuario on reporte.id_Usuario = usuario.idUsuario 
        inner join comentario on reporte.id_Comentario = comentario.idComentario where estado_Comentario = 1";

        return self::consultarSQL($query);
    }

    // Funcion para encontrar un reporte por cualquier campo
    public static function find($where,$param){
        if(gettype($param) == "integer"){
            $query = "select * from reporte where {$where} = {$param} ";
        }else{
            $query = "select * from usuario where {$where} = \"$param\" "; 
        }
        
        
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    // Funcion para consultar una query en la base de datos.
    public static function consultarSQL($query)
    {
        $sentencia = self::$db->mysql->prepare($query);
        $array = [];
        if ($sentencia->execute()) {
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $key => $dato) {

                $queryUsuarioC = "select * from comentario inner join usuario on comentario.id_Usuario = usuario.idUsuario  where comentario.idComentario = :id";
                $sentenciaC = self::$db->mysql->prepare($queryUsuarioC);
                $sentenciaC->bindParam(":id", $dato["id_Comentario"]);
                $sentenciaC->execute();
                $resultadoComentarioU = null;

                if ($sentenciaC->execute()) {
                    $resultadoComentarioU = $sentenciaC->fetch(PDO::FETCH_ASSOC);
                }
                $dato["id_Usuario"] = Usuario::crearObjeto($dato);
                $dato["id_Comentario"] = Comentario::crearObjeto($resultadoComentarioU);
                $array[] = self::crearObjeto($dato);


            }
        }


        $sentencia->closeCursor();


        return $array;
    }


    // Funcion para crear un objeto de la clase Tienda.
    public static function crearObjeto($args)
    {
        $objeto = new self($args);
        return $objeto;
    }
    public static function crearReporte($idUsuario, $idTipoReporte, $idComentario){
        $query = "insert into reporte (id_Usuario,id_TipoReporte,id_Comentario) values (:id_Usuario,:id_TipoReporte,:id_Comentario)";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id_Usuario", $idUsuario);
        $sentencia->bindParam(":id_TipoReporte", $idTipoReporte);
        $sentencia->bindParam(":id_Comentario", $idComentario);
        if ($sentencia->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}
