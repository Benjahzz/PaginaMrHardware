<?php

namespace App;

use ConexionPDO;
use PDO;



class Producto
{
    //put your code here
    public $idProducto;
    public $nombre;
    public $precio;
    public $Detalle_idDetalle;
    public $id_TipoProducto;
    public $link;
    public Tiendas $Tiendas_idTiendas;
    public $vistas;
    public $linkTienda;


    public static ConexionPDO $db;



    public function __construct($args = [])
    {
        $this->idProducto = $args["idProducto"] ?? null;
        $this->nombre = utf8_decode($args["nombre"]) ?? '';
        $this->precio = $args["precio"] ?? null;
        $this->Detalle_idDetalle = $args["Detalle_idDetalle"] ?? null;
        $this->id_TipoProducto = $args["id_TipoProducto"] ?? null;
        $this->link = $args["link"] ?? '';
        $this->Tiendas_idTiendas = $args["Tiendas_idTiendas"] ?? null;
        $this->vistas = $args["vistas"] ?? null;
        $this->linkTienda = $args["linkTienda"] ?? '';
    }

    // Funcion para obtener todos los productos con la tienda(inner join). Esta funcion puede consultar por cualquier campo y se le pueden agregar extras.

    public static function all($param = "", $extras = false, $where = null,$param2 = null,$param3 = null)
    {
        if ($param == "" && $extras == false) {
            $query = "select * from producto inner join tiendas on producto.Tiendas_idTiendas = tiendas.idTiendas";
            $sentencia = self::$db->mysql->prepare($query);
            $resultado = self::consultarSQL($sentencia);
            return $resultado;
        } else {
            
            if ($param == "") {
                
                $query = "select * from producto inner join tiendas on producto.Tiendas_idTiendas = tiendas.idTiendas";
                
                if($extras){
                    $query .= $extras ? " {$extras}" : "";
                    $sentencia = self::$db->mysql->prepare($query);
                    
                    if($param2 != null){
                        $sentencia->bindParam(":param1", $param2);
                    }
                    
                    $resultado = self::consultarSQL($sentencia);
                    return $resultado;
                }
            } else {
                $wheres = $where ? "{$where} like CONCAT('%', :param2 ,'%')" : "id_TipoProducto = :param2";
                
                $query = "select * from producto inner join tiendas on producto.Tiendas_idTiendas = tiendas.idTiendas where {$wheres}";
                $query .= $extras ? " {$extras}" : "";
                
                var_dump($query);
                if($extras){
                    
                    
                    $sentencia = self::$db->mysql->prepare($query);
                    if($param2 != null){
                        $sentencia->bindParam(":param2", $param2);
                    }
                    if($param3 != null){
                        $sentencia->bindParam(":param3", $param3);
                    }
                    
                    
                    $resultado = self::consultarSQL($sentencia);
                    
                    return $resultado;
                }
            }
        }


        
    }

    public static function allApp($where, $param, $extras = null)
    {

        if ($param == "" && $extras == false) {
            $query = "select * from producto inner join tiendas on producto.Tiendas_idTiendas = tiendas.idTiendas";
            $sentencia = self::$db->mysql->prepare($query);
            
        } else {
            $query = "select * from producto inner join tiendas on producto.Tiendas_idTiendas = tiendas.idTiendas where {$where} = :param";
            $query .= $extras ? " {$extras}" : "";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":param", $param);
        }
        
        return self::consultarSQL($sentencia);
    }



    public static function allListaLimpia($extras)
    {
        $query = "select * from producto inner join tiendas on producto.Tiendas_idTiendas = tiendas.idTiendas";
        $query .= $extras ? " {$extras}" : "";
        $sentencia = self::$db->mysql->prepare($query);
        return self::consultarSQL($sentencia);
    }
    // Funcion para encontrar un producto por cualquier campo.
    public static function find($param, $where = null, $equal = null, $paramOpcional = null)
    {
        if ($where != null) {
            if ($equal == null) {
                $query = "select * from producto where {$where} = :param";
                $sentencia = self::$db->mysql->prepare($query);
                $sentencia->bindParam(":param", $param);
            } else {
                if($paramOpcional == null){
                    $query = "select * from producto where {$where} :param1";
                    $sentencia = self::$db->mysql->prepare($query);
                    $sentencia->bindParam(":param1", $param);
                    
                }else{
                    
                    $query = "select * from producto where {$where} :param1";
                    $sentencia = self::$db->mysql->prepare($query);
                    $sentencia->bindParam(":param1", $paramOpcional);

                }
                
            }

            $resultado = self::consultarSQL($sentencia);
            return $resultado;
        } else {
            $query = "select * from producto where idProducto = :param1";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":param1", $param);
            $resultado = self::consultarSQL($sentencia);
            
            return array_shift($resultado);
        }
    }
    public static function sumarVista($sumaVisa, $id)
    {
        $query = "update producto set vistas = :vistas where idProducto = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":vistas", $sumaVisa);
        $sentencia->bindParam(":id", $id);
        if ($sentencia->execute()) {
            $datos = true;
        }
        else {
            $datos = false;
        }
        return $datos;
    }


    // Funcion para consultar la base de datos con la query que se le pase.

    public static function consultarSQL($sentencia)
    {
        $setNames = "set names utf8";
        $sentenciaNames = self::$db->mysql->prepare($setNames);
        $sentenciaNames->execute();
        
        

        $array = [];
        if ($sentencia->execute()) {
            
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $key => $dato) {

                $dato["Tiendas_idTiendas"] = Tiendas::find($dato["Tiendas_idTiendas"]);
                $array[] = self::crearObjeto($dato);
            }
        }
        
        $sentencia->closeCursor();
        
        return $array;
    }
    // funcion para crear un objeto de la clase producto.
    public static function crearObjeto($args)
    {
        $objeto = new self($args);
        return $objeto;
    }
    
    // Funcion para calificar un producto por un usuario.
    public static function calificarProducto($calificacion, $idProducto, $idUsuario)
    {
        // Consulta la base de datos por si ya existe una calificacion para el producto por el usuario logeado.
        $query = "select * from rating where Producto_idProducto = {$idProducto} and Usuario_idUsuario = {$idUsuario}";
        $sentencia = self::$db->mysql->prepare($query);

        if ($sentencia->execute()) {

            // si existe una calificacion la actualiza, si no la crea.
            if ($sentencia->fetch()) {
                $queryUpdate = "update rating set numeroRating = {$calificacion} where Producto_idProducto = {$idProducto} and Usuario_idUsuario = {$idUsuario}";
                $sentenciaUpdate = self::$db->mysql->prepare($queryUpdate);
                if ($sentenciaUpdate->execute()) {
                    return true;
                }
            } else {
                $queryInsert = "insert into rating (numeroRating, Producto_idProducto, Usuario_idUsuario) values ({$calificacion}, {$idProducto}, {$idUsuario})";
                $sentenciaInsert = self::$db->mysql->prepare($queryInsert);
                if ($sentenciaInsert->execute()) {
                    return true;
                }
            }
        }else{
            return false;
        }
    }
    public function sumarVistas(){
        $query = "update producto set vistas = vistas + 1 where idProducto = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $this->idProducto);

        if ($sentencia->execute()) {
            return true;
        }
        else {
            return false;
        }
    }
    public function productosOrdenado($ordenar){
        $query = "select * from producto order by vistas desc";
        $sentencia = self::$db->mysql->prepare($query);
        $resultado = self::consultarSQL($sentencia);
        
        return $resultado;
    }
    public function contarProductos($param = "", $extras = false, $where = null,$param2 = null,$param3 = null){
    
        if ($param == "" && $extras == false) {
            $query = "select count(*) from producto inner join tiendas on producto.Tiendas_idTiendas = tiendas.idTiendas";
            $sentencia = self::$db->mysql->prepare($query);
            $resultado = self::consultarSQL($sentencia);
            return $resultado;
        } else {
            
            if ($param == "") {
                
                $query = "select count(*) from producto inner join tiendas on producto.Tiendas_idTiendas = tiendas.idTiendas";
                
                if($extras){
                    $query .= $extras ? " {$extras}" : "";
                    $sentencia = self::$db->mysql->prepare($query);
                    
                    if($param2 != null){
                        $sentencia->bindParam(":param1", $param2);
                    }
                    
                    $resultado = self::consultarSQL($sentencia);
                    return $resultado;
                }
            } else {
                $wheres = $where ? "{$where} like CONCAT('%', :param2 ,'%')" : "id_TipoProducto = :param2";
                
                $query = "select count(*) from producto inner join tiendas on producto.Tiendas_idTiendas = tiendas.idTiendas where {$wheres}";
                $query .= $extras ? " {$extras}" : "";
                
                var_dump($query);
                if($extras){
                    
                    
                    $sentencia = self::$db->mysql->prepare($query);
                    if($param2 != null){
                        $sentencia->bindParam(":param2", $param2);
                    }
                    if($param3 != null){
                        $sentencia->bindParam(":param3", $param3);
                    }
                    
                    
                    $resultado = self::consultarSQL($sentencia);
                    
                    return $resultado;
                }
            }
        }


        
    
    }
}
