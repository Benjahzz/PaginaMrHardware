<?php

namespace App;

use ConexionPDO;
use PDO;
use UConverter;

class Setup
{

    public static ConexionPDO $db;
    public static $errores = [];

    public $idSetup;
    public $Usuario_idUsuario;
    public $nombre;
    public $copias;
    public $estado;
    public $producto_setup;



    // Constructor de la clase Setup.
    public function __construct($args = [])
    {
        $this->idSetup = $args["idSetup"] ?? null;
        $this->Usuario_idUsuario = $args["Usuario_idUsuario"] ?? null;
        $this->nombre = $args["nombre"] ?? '';
        $this->copias = $args["copias"] ?? null;
        $this->estado = $args["estado"] ?? '';
        $this->producto_setup = $args["producto_setup"] ?? null;
    }

    // Funcion para obtener todos los Setup
    public static function all($extras = false)
    {
        $query = "select * from setup where estado = 1 ";
        $query .= $extras ? "{$extras}" : "";
        $sentencia =self::$db->mysql->prepare($query);

        return self::consultarSQL($sentencia);
    }

    // Funcion para encontrar un Setup por cualquier campo.
    public static function find($where, $param, $extras = false)
    {
        if (gettype($param) == "integer") {
            $query = "select * from setup where estado = 1 and {$where} = :param";
            $query .= $extras ? " {$extras}" : "";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":param", $param);
        }
        else {
            $query = "select * from setup where estado = 1 and {$where} = :param";
            $query .= $extras ? " {$extras}" : "";
            $sentencia = self::$db->mysql->prepare($query);
            $sentencia->bindParam(":param", $param);
        }

        $resultado = self::consultarSQL($sentencia);
        return $resultado;
    }



    // Funcion para consultar una query en la base de datos.
    public static function consultarSQL($sentencia)
    {
        $setNames = "set names utf8";
        $sentenciaNames = self::$db->mysql->prepare($setNames);
        $sentenciaNames->execute();
        $array = [];
        if ($sentencia->execute()) {
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $key => $dato) {
                $queryUsuarioC = "select producto.* from producto_has_setup s inner join producto on s.Producto_idProducto = producto.idProducto where Setup_idSetup = :id";
                $sentenciaC = self::$db->mysql->prepare($queryUsuarioC);
                $sentenciaC->bindParam(":id", $dato["idSetup"]);
                if ($sentenciaC->execute()) {
                    $resultadoProductoSetup = $sentenciaC->fetchAll(PDO::FETCH_ASSOC);
                    for ($i = 0; $i < count($resultadoProductoSetup); $i++) {

                        $resultadoProductoSetup[$i]["nombre"] = utf8_decode($resultadoProductoSetup[$i]["nombre"]);
                        $resultadoProductoSetup[$i]["Tiendas_idTiendas"] = Tiendas::find($resultadoProductoSetup[$i]["Tiendas_idTiendas"]);
                    }

                }

                $dato["producto_setup"] = $resultadoProductoSetup;

                $array[] = self::crearObjeto($dato);


            }
        }

        $sentencia->closeCursor();


        return $array;
    }

    // Funcion para crear un objeto de la clase Setup.
    public static function crearObjeto($args)
    {

        $objeto = new self($args);

        return $objeto;
    }
    public function actualizar($args)
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }


    //Fucion para actualizar al Setup
    public function actualizarSetup($args)
    {
        if (gettype($args) != "integer") {
            $producto = $args;
            if ($this->producto_setup != null) {
                $queryEliminar = "delete from producto_has_setup where Setup_idSetup = :id";
                $sentenciaEliminar = self::$db->mysql->prepare($queryEliminar);
                $sentenciaEliminar->bindParam(":id", $this->idSetup);
                $sentenciaEliminar->execute();
                $productoSetup = $this->producto_setup;
                for ($i = 0; $i < count($this->producto_setup); $i++) {
                    $tipoProducto = $productoSetup[$i]["id_TipoProducto"];
                    if ($tipoProducto == $producto->id_TipoProducto) {

                        $query = "insert into producto_has_setup (Producto_idProducto, Setup_idSetup) values (:idProducto, :id)";
                        $sentencia = self::$db->mysql->prepare($query);
                        $sentencia->bindParam(":id", $this->idSetup);
                        $sentencia->bindParam(":idProducto", $producto->idProducto);
                        $sentencia->execute();

                        for ($t = 0; $t < count($this->producto_setup); $t++) {
                            if ($i == $t) {
                                continue;
                            }
                            else {
                                $query = "insert into producto_has_setup (Producto_idProducto, Setup_idSetup) values (:idProducto, :id)";
                                $sentencia = self::$db->mysql->prepare($query);
                                $sentencia->bindParam(":id", $this->idSetup);
                                $sentencia->bindParam(":idProducto", $productoSetup[$t]["idProducto"]);
                                $sentencia->execute();
                            }
                        }
                    }
                }
                $query = "insert into producto_has_setup (Producto_idProducto, Setup_idSetup) values (:idProducto, :id)";
                $sentencia = self::$db->mysql->prepare($query);
                $sentencia->bindParam(":id", $this->idSetup);
                $sentencia->bindParam(":idProducto", $producto->idProducto);
                $sentencia->execute();
                for ($i = 0; $i < count($this->producto_setup); $i++) {

                    $query = "insert into producto_has_setup (Producto_idProducto, Setup_idSetup) values (:idProducto, :id)";
                    $sentencia = self::$db->mysql->prepare($query);
                    $sentencia->bindParam(":id", $this->idSetup);
                    $sentencia->bindParam(":idProducto", $productoSetup[$i]["idProducto"]);
                    $sentencia->execute();
                }
            }

            else {
                $query = "insert into producto_has_setup (Producto_idProducto, Setup_idSetup) values (:idProducto, :id)";
                $sentencia = self::$db->mysql->prepare($query);
                $sentencia->bindParam(":id", $this->idSetup);
                $sentencia->bindParam(":idProducto", $producto->idProducto);

                $sentencia->execute();
            }
        }else{
            $queryEliminar = "delete from producto_has_setup where Setup_idSetup = :id and Producto_idProducto = :idProd";
                $sentencia = self::$db->mysql->prepare($queryEliminar);
                $sentencia->bindParam(":id", $this->idSetup);
                $sentencia->bindParam(":idProd", $args);
                $sentencia->execute();
        }
        return $sentencia;
    }

    //Funcion para registrar productos en el setup
    public static function registrarSetup($idUsuario, $nombre)
    {
        $query = "INSERT INTO setup (Usuario_idUsuario,nombre)
         VALUES(:idUsuario,:nombre)";

        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":idUsuario", $idUsuario);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->execute();

        return self::$db->mysql->lastInsertId();
    }

    //Funcion para Eliminar el Setup
    public function eliminarSetup()
    {

        $query = "update setup set estado = 0 where idSetup = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $this->idSetup);
        $sentencia->execute();
        return $sentencia;
    }

    public function copiarSetup($idUsuario_logeado, $idSetup, $setupNombre = null)
    {
        $productosSetup = $this->producto_setup;
        if ($setupNombre == null) {
            $queryEliminar = "delete from producto_has_setup where Setup_idSetup = :id";
            $sentenciaEliminar = self::$db->mysql->prepare($queryEliminar);

            $sentenciaEliminar->bindParam(":id", $idSetup);
            $sentenciaEliminar->execute();

            for ($i = 0; $i < count($productosSetup); $i++) {



                $query = "insert into producto_has_setup (Producto_idProducto, Setup_idSetup) values (:idProducto, :id)";
                $sentencia = self::$db->mysql->prepare($query);
                $sentencia->bindParam(":id", $idSetup);
                $sentencia->bindParam(":idProducto", $productosSetup[$i]["idProducto"]);
                $sentencia->execute();
            }
        }
        else {
            $lastId = self::registrarSetup($idUsuario_logeado, $setupNombre);
            var_dump($lastId);
            for ($i = 0; $i < count($productosSetup); $i++) {



                $query = "insert into producto_has_setup (Producto_idProducto, Setup_idSetup) values (:idProducto, :id)";
                $sentencia = self::$db->mysql->prepare($query);
                $sentencia->bindParam(":id", $lastId);
                $sentencia->bindParam(":idProducto", $productosSetup[$i]["idProducto"]);
                $sentencia->execute();
            }
        }

        $sentencia->closeCursor();
    }
}
