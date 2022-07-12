<?php

class ConexionPDO {

    private $dsn = 'mysql:host=localhost;dbname=mrhardware';
    private $username = 'root';
    private $password = '';
    public $mysql = null;

    function __construct() {
        //en el constructor vamos a realizar la conexion con la base de datos de mysql
        try {
            //siempre que queramos obtener datos desde un servicio externo, tenemos que tener un bloque trycatch
            //por posibles errores en el tercero
            $this->mysql = new PDO($this->dsn, $this->username, $this->password);
            //echo "Conexion exitosa";
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }      
}
