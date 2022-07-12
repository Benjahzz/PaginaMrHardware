<?php

namespace App;

use ConexionPDO;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;

class Usuario
{

    public static ConexionPDO $db;
    public static $errores = [];

    public $idUsuario;
    public $email;
    public $username;
    public $password;
    public $avatar;
    public $descripcion;
    public $id_TipoUsuario;
    public $instagram;
    public $nombre;
    public $Pais_idPais;
    public $confirmacion;
    public $codigo;
    public $estado;
    public $meGusta;




    // Constructor de la clase Usuario.
    public function __construct($args = [])
    {

        $this->idUsuario = $args["idUsuario"] ?? '';
        $this->email = $args["email"] ?? '';
        $this->username = utf8_decode($args["username"]) ?? '';
        $this->password = utf8_decode($args["password"]) ?? '';
        $this->avatar = $args["avatar"] ?? '';
        $this->descripcion = $args["descripcion"] ?? '';
        $this->id_TipoUsuario = $args["id_TipoUsuario"] ?? null;
        $this->instagram = $args["instagram"] ?? '';
        $this->nombre = $args["nombre"] ?? '';
        $this->Pais_idPais = $args["Pais_idPais"] ?? null;
        $this->confirmacion = $args["confirmacion"] ?? null;
        $this->codigo = $args["codigo"] ?? null;
        $this->estado = $args["estado"] ?? null;
        $this->meGusta = $args["meGusta"] ?? null;
    }

    // Funcion para obtener todos los usuario donde su estado sea 1.
    public static function all($extras = false)
    {
        $query = "select * from usuario where id_TipoUsuario = 2 and estado = 1";
        $query .= $extras ? " {$extras}" : "";
        $sentencia = self::$db->mysql->prepare($query);
        return self::consultarSQL($sentencia);
    }

    // Funcion para obtener un usuario espcifico por cualquier campo.
    public static function find($where, $param, $extras = false)
    {
        if (gettype($param) == "integer") {
            $query = "select * from usuario where {$where} = :param ";
            $query .= $extras ? " {$extras}" : "";
        } else {
            $query = "select * from usuario where {$where} = :param ";
            $query .= $extras ? " {$extras}" : "";
        }
        $sentencia = self::$db->mysql->prepare($query);

        $sentencia->bindParam(':param', $param);


        $resultado = self::consultarSQL($sentencia);
        return $resultado;
    }


    public static function autenticarUsuario($email, $pass)
    {
        $query = "select * from usuario where email = :email and password = :pass";

        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(':email', $email);
        $sentencia->bindParam(':pass', $pass);
        $resultado = self::consultarSQL($sentencia);
        return array_shift($resultado);
    }

    // Funcion para autentificar el inicio de sesion de un usuario
    public static function iniciarSesion($user, $pass)
    {
        $query = "select * from usuario where username = :user and password = :pass and estado = 1";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(':user', $user);
        $sentencia->bindParam(':pass', $pass);
        $resultado = self::consultarSQL($sentencia);

        return array_shift($resultado);
    }

    // Funcion para crear un objeto de la clase usuario
    public function registrarUsuario()
    {
        $query = "INSERT INTO usuario (email,username,password,id_TipoUsuario,codigo)
         VALUES(:email,:username,:pass,:tipo,:codigo)";

        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":email", $this->email);
        $sentencia->bindParam(":username", $this->username);
        $sentencia->bindParam(":pass", $this->password);
        $Tipo_Usuario = 2;
        $sentencia->bindParam(":tipo", $Tipo_Usuario);
        $sentencia->bindParam(":codigo", $this->codigo);

        $sentencia->execute();


        return $sentencia;
    }

    // Funcion para consultar una query en la base de datos.
    public static function consultarSQL($sentencia)
    {
        $setNames = "set names utf8";
        $sentenciaNames = self::$db->mysql->prepare($setNames);
        if($sentenciaNames->execute()){
        };

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
    // Funcion para crear un objeto de la clase Usuario.
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
    public function confirmar()
    {
        $query = "update usuario set confirmacion = 1 where email = :email";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":email", $this->email);
        $sentencia->execute();
    }

    //Fucion para actualizar al Usuario
    public static function ActualizarUser($user)
    {

        $query = "update usuario set username = :username, descripcion = :descripcion, nombre = :nombre, email = :email, avatar = :avatar, Pais_idPais = :pais  where idUsuario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":username", $user->username);
        $sentencia->bindParam(":descripcion", $user->descripcion);
        $sentencia->bindParam(":nombre", $user->nombre);
        $sentencia->bindParam(":email", $user->email);
        $sentencia->bindParam(":id", $user->idUsuario);
        $sentencia->bindParam(":avatar", $user->avatar);
        $pais = $user->Pais_idPais ? $user->Pais_idPais : null;
        $sentencia->bindParam(":pais", $pais);
        if ($sentencia->execute()) {
            $datos = true;
        } else {
            $datos = false;
        }
        return $datos;
    }
    //Funcion que permite banear a un usuario por el id del usuario.
    //Retorna true si se pudo banear, false si no.
    public static function banear($id)
    {
        $query = "update usuario set estado = 0 where idUsuario = :id";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":id", $id);
        if ($sentencia->execute()) {
            $datos = true;
        } else {
            $datos = false;
        }
        return $datos;
    }
    public function buscarPais()
    {
        $query = "select * from pais where idPais = :idPais ";
        $sentencia = self::$db->mysql->prepare($query);
        $sentencia->bindParam(":idPais", $this->Pais_idPais);
        if ($sentencia->execute()) {
            $resultado = $sentencia->fetch();
        } else {
            $resultado = "aaaa";
        }

        return $resultado["nombre"];
    }
    public static function enviarCorreo($correo, $nombre, $asunto, $mensajes)
    {

        $mail = new PHPMailer();
        $headers = "Content-Type: text/html; charset=UTF-8";

        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->isHTML();
        $mail->Host = " smtp.office365.com";
        $mail->SMTPAuth = true;
        $mail->Username = "mrhardwareoficial@outlook.com"; // Correo de quien envia el correo.
        $mail->Password = "mrhardware7890"; // Contraseña del correo de quien envia el correo.
        $mail->SMTPSecure = "tls";
        $mail->SMTPAutoTLS = false;
        $mail->Port = 587;

        $mail->setFrom("mrhardwareoficial@outlook.com", "MrHardware"); // Correo de quien envia el correo.

        $mail->addAddress("mrhardwareoficial@outlook.com", "Mailer"); // Correo de quien recibe el correo.

        $mail->isHTML(true);
        $mail->Subject = "Contacto de Usuario " . $correo; // Asunto del correo.
        $mail->Body = $mensajes; // Cuerpo del correo.
        
        $mail->msgHTML($mensajes); // Cuerpo del correo.
        $mail->CharSet = 'UTF-8';
        $mail->AllowEmpty = true;
        $mail->SMTPDebug = 0;
        $mail->send();
    }
}
