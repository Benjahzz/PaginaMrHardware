<?php

namespace App;

use ConexionPDO;
use PDO;

class Megusta
{

    public static ConexionPDO $db;
   

    public $idMegusta;
    public $Usuario_idUsuario;
    public $Usuario_idUsuarioLogeado;
    public $idComentario;




    public function __construct($args = [])
    {

        $this->idMegusta = $args["idMegusta"] ?? '';
        $this->Usuario_idUsuario = $args["Usuario_idUsuario"] ?? '';
        $this->Usuario_idUsuarioLogeado = $args["Usuario_idUsuarioLogeado"] ?? '';
        $this->idComentario = $args["idComentario"] ?? '';
        
    }


    // Funcion para obtener todos los megusta, con la id del usuario.

    public static function all($idUsuario,$extras = false, $Usuario_idUsuario = "Usuario_idUsuario")
    {
        $where = $Usuario_idUsuario != null? $Usuario_idUsuario : "Usuario_idUsuarioLogeado";
        $query = "select * from `me gusta` where {$where} = :param1";
        $query .= $extras ? " {$extras}" : "";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(':param1', $idUsuario);

        
        return self::consultarSQL($sentencia);
    }

    // Funcion para encontrar un megusta por cualquier campo.
    public static function find($where, $param1, $param2,$extras = false)
    {
        $query = "select * from `me gusta` where {$where} = :param1 and Usuario_idUsuarioLogeado = :param2";
        $query .= $extras ? " {$extras}" : "";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(':param1', $param1);
        $sentencia->bindParam(':param2', $param2);

        


        $resultado = self::consultarSQL($sentencia);
        return $resultado;
    }



    // Funcion para consultar una query en la base de datos.

    public static function consultarSQL($sentencia)
    {
       
        $array = [];
        if ($sentencia->execute()) {
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $key => $dato) {
                $array[] = self::crearObjeto($dato);
            }
        }

        $sentencia->closeCursor();


        return $array;
    }
    // Funcion para crear un objeto de la clase Megusta.

    public static function crearObjeto($args)
    {

        $objeto = new self($args);

        return $objeto;
    }
    // Funcion para insertar un megusta en la base de datos.
    public static function seguirUsuario($idUsuario)
    {
        // Se suma 1 al contador de megusta del usuario al que se le dió el megusta.
        $query = "update usuario set meGusta = meGusta + 1 where idUsuario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $idUsuario);


        if ($sentencia->execute()) {
            // Si se ejecuta correctamente
            // Se inserta el megusta en la base de datos con los datos correspondientes.
            $query = "insert into `me gusta`(Usuario_idUsuario, Usuario_idUsuarioLogeado) values(:idUsuario, :idUsuarioLogeado)";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":idUsuario", $idUsuario);
            $sentencia->bindParam(":idUsuarioLogeado", $_SESSION["login"]->idUsuario);
            $sentencia->execute();
            $sentencia->closeCursor();
        }
        
    }
    // Funcion para eliminar un megusta en la base de datos.
    public static function unfollowUsuario($idUsuario)
    {
       // Se resta 1 al contador de megusta del usuario al que se le quitó el megusta.
        $query = "update usuario set meGusta = meGusta + -1 where idUsuario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $idUsuario);


        if ($sentencia->execute()) {
            // Si se ejecuta correctamente
            // Se elimina el megusta en la base de datos con los datos correspondientes (UsuarioLogeado y el Usuario al que se le quito el megusta).
            $query = "delete from `me gusta` where Usuario_idUsuarioLogeado = :idUsuarioLogeado and Usuario_idUsuario = :idUsuario";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":idUsuarioLogeado", $_SESSION["login"]->idUsuario);
            $sentencia->bindParam(":idUsuario", $idUsuario);
    
            $sentencia->execute();
        }
        
    }
    // Funcion para votar un voto positivo en un comentario en la base de datos.
    public static function votar($idComentario, $idUsuario)
    {
        // Se suma 1 al contador de votos positivos del comentario al que se le dió el voto.
        $query = "update comentario set votoPositivo = votoPositivo + 1 where idComentario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $idComentario);


        if ($sentencia->execute()) {
            // Si se ejecuta correctamente
            // Se inserta el voto positivo en la base de datos con los datos correspondientes.
            $query = "insert into `me gusta`(Usuario_idUsuarioLogeado,idComentario) values(:idUsuario,:idComentario)";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":idUsuario", $idUsuario);
            $sentencia->bindParam(":idComentario", $idComentario);

            $sentencia->execute();
        }
    }

    // Funcion para quitar el voto positivo en un comentario en la base de datos.
    public static function quitarVoto($idComentario, $idUsuario)
    {
        // Se resta 1 al contador de votos positivos del comentario al que se le quitó el voto.
        $query = "update comentario set votoPositivo = votoPositivo -1 where idComentario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $idComentario);


        if ($sentencia->execute()) {
            // Si se ejecuta correctamente
            // Se elimina el voto positivo en la base de datos con los datos correspondientes.
            $query = "delete from `me gusta` where Usuario_idUsuarioLogeado = :idUsuario and idComentario = :idComentario";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":idUsuario", $idUsuario);
            $sentencia->bindParam(":idComentario", $idComentario);

            $sentencia->execute();
        }
    }
}
