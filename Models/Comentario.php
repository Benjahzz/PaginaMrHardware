<?php


namespace App;

use ConexionPDO;
use PDO;

class Comentario
{

    public static ConexionPDO $db;
    public static $errores = [];

    public $idComentario;
    public $fecha;
    public $comentario;
    public $votoPositivo;
    public $id_Producto;
    public Usuario $id_Usuario;
    public $id_Comentario_Respuesta;
    public $cantidad_Reportes;
    public $estado_Comentario;
    public $idComentario_Principal;

    // Constructor de la clase Comentario.
    public function __construct($args = [])
    {

        $this->idComentario = $args["idComentario"] ?? '';
        $this->fecha = $args["fecha"] ?? '';
        $this->comentario = $args["comentario"] ?? '';
        $this->votoPositivo = $args["votoPositivo"] ?? '';
        $this->id_Producto = $args["id_Producto"] ?? '';
        $this->id_Usuario = $args["id_Usuario"] ?? '';
        $this->id_Comentario_Respuesta = $args["id_Comentario_Respuesta"] ?? null;
        $this->cantidad_Reportes = $args["cantidad_Reportes"] ?? null;
        $this->estado_Comentario = $args["estado_Comentario"] ?? null;
        $this->idComentario_Principal = $args["idComentario_Principal"] ?? null;
    }


    // Funcion para obtener todos los comentarios de un producto o un Usuario.
    public static function all($id, $idProductoSelec = "")
    {
        if ($id == "" && $idProductoSelec == "") {
            $query = "select * from comentario inner join usuario on comentario.id_Usuario = usuario.idUsuario where estado_Comentario = 1 and cantidad_Reportes = 0";
        }
        else if($idProductoSelec == ""){
            $query = "select * from comentario inner join usuario on comentario.id_Usuario = usuario.idUsuario where estado_Comentario = 1 and id_Usuario = {$id}";
        }else{
            $query = "select * from comentario inner join usuario on comentario.id_Usuario = usuario.idUsuario where estado_Comentario = 1 and id_Producto = {$idProductoSelec} order by fecha desc";
        }
        return self::consultarSQL($query);
    }
    // Funcion para encontrar un comentario por cualquier campo.
    public static function find($where, $param, $extras = false)
    {
        if (gettype($param) == "integer") {
            $query = "select * from comentario inner join usuario on comentario.id_Usuario = usuario.idUsuario where {$where} = {$param} ";
            $query .= $extras ? " {$extras}" : "";
        } else {
            $query = "select * from comentario inner join usuario on comentario.id_Usuario = usuario.idUsuario where {$where} = \"$param\" ";
            $query .= $extras ? " {$extras}" : "";
        }

        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Funcion para consultar una query en la base de datos.
    public static function consultarSQL($query)
    {
        $sentencia = self::$db->mysql->prepare($query);
        $array = [];
        if ($sentencia->execute()) {
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $key => $dato) {
                
                $dato["id_Usuario"] = Usuario::crearObjeto($dato);
               
                $array[] = self::crearObjeto($dato);
            }
        }

        $sentencia->closeCursor();


        return $array;
    }
    // Funcion para crear un objeto de la clase Comentario.
    public static function crearObjeto($args)
    {


        $args["id_Usuario"] = Usuario::crearObjeto($args);
        $objeto = new self($args);

        return $objeto;
    }
    //Funcion que permite banear a un comentario por el id del comentario. Retorna true si se pudo banear, false si no.
    public static function banearComentario($id)
    {
        $query = "update comentario set estado_Comentario = 0 where idComentario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $id);
        if ($sentencia->execute()) {
            $datos = true;
        }
        else {
            $datos = false;
        }
        return $datos;
    }

    //Funcion que permite editar un comentario por el id del comentario. Retorna true si se pudo editar, false si no.
    public static function editarComentario($id, $comentario)
    {
        $query = "update comentario set comentario = :comentario where idComentario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":comentario", $comentario);
        $sentencia->bindParam(":id", $id);
        if ($sentencia->execute()) {
            $datos = true;
        }
        else {
            $datos = false;
        }
        return $datos;
    }
    public static function reportarComentario($idComentario, $idUsuario,$idTipoReporte)
    {
        $query = "update comentario set cantidad_Reportes = cantidad_Reportes + 1 where idComentario = :idComentario";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":idComentario", $idComentario);
        if ($sentencia->execute()) {
            Reporte::crearReporte($idUsuario,$idTipoReporte,$idComentario);
            $datos = true;
        }
        else {
            $datos = false;
        }
        return $datos;
    }
    
    // Funcion que permite registrar un comentario.
    public static function registrarComentario($comentario, $idProducto,$idUsuario, $idComentario = null, $idComentario_Principal = null){
        // Si el idComentario es nulo, se registra un comentario nuevo. En cambio, si no es nulo, se registra una respuesta a un comentario
        if($idComentario == null){
            $query = "insert into comentario(comentario,id_Producto,id_Usuario, fecha) values(:comentario,:idProducto,:idUsuario, :fecha)";
            $sentencia = self::$db->mysql->prepare($query);
            
            $sentencia->bindParam(":comentario", $comentario);
            $sentencia->bindParam(":idProducto",$idProducto);
            $sentencia->bindParam(":idUsuario", $idUsuario);
            $fecha =  date('y-m-d');
            $sentencia->bindParam(":fecha",$fecha);
            $sentencia->execute();
        }else{
            $query = "insert into comentario(comentario,id_Producto,id_Usuario, fecha, id_Comentario_Respuesta, idComentario_Principal) values(:comentario,:idProducto,:idUsuario, :fecha, :idComentario, :idComentario_Principal)";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":comentario", $comentario);
            $sentencia->bindParam(":idProducto", $idProducto);
            $sentencia->bindParam(":idUsuario", $idUsuario);
            $sentencia->bindParam(":idComentario", $idComentario);
            $sentencia->bindParam(":idComentario_Principal", $idComentario_Principal);
            
            $fecha =  date('y-m-d');
            $sentencia->bindParam(":fecha",$fecha);
            $sentencia->execute();
        }
        return self::$db->mysql->lastInsertId();
    }
    // Funcion que permite registrar un voto positivo a un comentario.
    public static function votar($idComentario,$idUsuario){
        $query = "update comentario set votoPositivo = votoPositivo + 1 where idComentario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $idComentario);
        

        if($sentencia->execute()){
            $query = "insert into `me gusta`(Usuario_idUsuarioLogeado,idComentario) values(:idUsuario,:idComentario)";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":idUsuario", $idUsuario);
            $sentencia->bindParam(":idComentario", $idComentario);
            
            $sentencia->execute();
        }

    }
    // Funcion que permite quitar voto a un comentario si este ya lo había votado.
    public static function quitarVoto($idComentario,$idUsuario){
        $query = "update comentario set votoPositivo = votoPositivo -1 where idComentario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $idComentario);
        

        if($sentencia->execute()){
            $query = "delete from `me gusta` where Usuario_idUsuarioLogeado = :idUsuario and idComentario = :idComentario";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":idUsuario", $idUsuario);
            $sentencia->bindParam(":idComentario", $idComentario);
            
            $sentencia->execute();
        }

        
    }
    // Funcion que encuentra un megusta por el id del comentario y el id del usuario.
    public static function findMegusta($idUsuario,$idComentario){
        $query = "select * from `me gusta` where Usuario_idUsuarioLogeado = :idUsuario and idComentario = :idComentario";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":idUsuario", $idUsuario);
        $sentencia->bindParam(":idComentario", $idComentario);
        $sentencia->execute();
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        // Si el resultado es nulo, no existe un megusta por ese id de usuario y comentario.
        if($resultado == null){
            return false;
        }else{
            return true;
        }
            

    }
    // Funcion que cuenta los comentarios de un usuario por el id del usuario.
    public static function contarComentarios($idUsuario){
        $query = "select count(*) as cantidad from comentario where id_Usuario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $idUsuario);
        $sentencia->execute();
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        return $resultado["cantidad"];
    }




}