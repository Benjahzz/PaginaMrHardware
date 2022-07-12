<?php

namespace App;

use ConexionPDO;
use PDO;



class Pais
{
    public static ConexionPDO $db;
    public $idPais;
    public $nombre;
    


   



    public function __construct($args = [])
    {
        $this->idPais = $args["idPais"] ?? null;
        $this->nombre = $args["nombre"] ?? '';
    }

    // Funcion para obtener todos los paises.
    public static function all()
    {

        $query = "select * from pais";
        return self::consultarSQL($query);
    }
    // Funcion para encontrar un pais por cualquier campo o po. 
    public static function find($idPais,$where = null)
    {   if($where != null){
            $query = "select * from pais where {$where} = ${idPais}";
            $resultado = self::consultarSQL($query);
            return $resultado;
        }else{
            $query = "select * from pais where idPais = ${idPais}";
            $resultado = self::consultarSQL($query);
            return array_shift($resultado);
        }
        
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
    // Funcion para crear un objeto de la clase.
    public static function crearObjeto($args)
    {
        $objeto = new self($args);
        return $objeto;
    }
    


}
