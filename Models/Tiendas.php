<?php

namespace App;

use ConexionPDO;
use PDO;



class Tiendas {
    public static ConexionPDO $db;
    public $idTiendas;
    public $tienda;

    // Constructor de la clase Tiendas.
    public function __construct($args = [])
    {            
            $this->idTiendas = $args["idTiendas"] ?? null;
            $this->tienda = $args["tienda"] ?? '';

        
    }

    // Funcion para crear un objeto de la clase Tienda
    public static function crearObjeto($args){
        $objeto = new self($args);
        return $objeto;
    }

    // Funcion para encontrar una tienda por cualquier campo.
    public static function find($idTiendas){
        $query = "select * from tiendas where idTiendas = ${idTiendas}";
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
                
               
                $array[] = self::crearObjeto($dato);
            }
        }

        $sentencia->closeCursor();

        return $array;
    }
}