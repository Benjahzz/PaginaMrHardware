<?php


namespace App;

use ConexionPDO;
use PDO;

class Rating
{

    public static ConexionPDO $db;
    public static $errores = [];

    public $idRating;
    public $numeroRating;
    public $Producto_idProducto;
    public $Usuario_idUsuario;
   

    public function __construct($args = [])
    {

        $this->idRating = $args["idRating"] ?? '';
        $this->numeroRating = $args["numeroRating"] ?? '';
        $this->Producto_idProducto = $args["Producto_idProducto"] ?? '';
        $this->Usuario_idUsuario = $args["Usuario_idUsuario"] ?? '';
        
       
    }
    // Funcion para obtener todos los rating, con la id del usuario y la id del producto.
    public static function find($idProducto, $idUsuario, $extras = null)
    {
        $query = "select * from rating where Producto_idProducto = {$idProducto}  and Usuario_idUsuario = {$idUsuario}";
        $query .= $extras ? " {$extras}" : "";


        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    // Funcion para buscar todos los rating de cualquier campo a dar en el $where y $param.
    public static function all($where, $param )
    {
        $query = "select * from rating where {$where} = {$param}";

        return self::consultarSQL($query);
    }

    // Funcion para consultar una query en la base de datos.
    public static function consultarSQL($query)
    {
        $sentencia = self::$db->mysql->prepare($query);
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
    // funcion para crear un objeto de la clase.
    public static function crearObjeto($args)
    {


        $objeto = new self($args);

        return $objeto;
    }





}