<?php 

namespace App;

use ConexionPDO;
use PDO;

class detalleProducto{
    public static ConexionPDO $db;
    public $idDetalle;	
    public $caracteristica;
    public $caracteristica2;
    public $caracteristica3;
    public $caracteristica4;
    public $caracteristica5;
    public $caracteristica6;
    public $caracteristica7;
    public $caracteristica8;
    public $caracteristica9;
    public $caracteristica10;
    public $caracteristica11;
    public $caracteristica12;

    public function __construct($args = [])
    {
        $this->idDetalle = $args["idDetalle"] ?? null;
        $this->caracteristica = $args["caracteristica"] ?? '';
        $this->caracteristica2 = $args["caracteristica2"] ?? '';
        $this->caracteristica3 = $args["caracteristica3"] ?? '';
        $this->caracteristica4 = $args["caracteristica4"] ?? '';
        $this->caracteristica5 = $args["caracteristica5"] ?? '';
        $this->caracteristica6 = $args["caracteristica6"] ?? '';
        $this->caracteristica7 = $args["caracteristica7"] ?? '';
        $this->caracteristica8 = $args["caracteristica8"] ?? '';
        $this->caracteristica9 = $args["caracteristica9"] ?? '';
        $this->caracteristica10 = $args["caracteristica10"] ?? '';
        $this->caracteristica11 = $args["caracteristica11"] ?? '';
        $this->caracteristica12 = $args["caracteristica12"] ?? '';

    }
    // Funcion para buscar un detalle de producto por id.

    public static function find($idDetalle)
    {

        $query = "select * from detalle where idDetalle = {$idDetalle}";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }
    // Funcion para consultar una query en la base de datos.
    public static function consultarSQL($query)
    {
        $q = "set names utf8";
        $sentencia = self::$db->mysql->prepare($q);
        $sentencia->execute();
        $sentencia = self::$db->mysql->prepare($query);
        $array = [];
        try {
            $sentencia->execute();
                } catch (\Throwable $th) {
            echo $th;
            exit;
        }
        if ($sentencia->execute()) {
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $key => $dato) {
                foreach($dato as $key2 => $result){
                    
                    $dato[$key2] = utf8_decode($result);
                }
                
                
                $array[] = self::crearObjeto($dato);
            }
        }

        $sentencia->closeCursor();

        return $array;
    }
    // crea un objeto de la clase detalleProducto.
    public static function crearObjeto($args){
        $objeto = new self($args);
        return $objeto;
    }



}