<?php

use App\Usuario;

require "../includes/app.php";
// Control de validacio nde email cuando se registra un nuevo usuario.

$email = $_POST["email"];

$Usuario = Usuario::find("email",$email);
// Si el email ya existe en la base de datos, no se puede registrar.
if($Usuario){
    var_dump($Usuario);
}else{
    return "";
}
?>