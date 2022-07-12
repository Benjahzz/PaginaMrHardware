<?php

use App\Usuario;
// Controlador de confirmacion de correo
require '../includes/app.php';
// Si se ha enviado por post el codigo de confirmacion.
if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
    $email = $_POST['email-confirmation']; // Recibimos el email del formulario de confirmacion de correo.
    $usuario = Usuario::find("email", $email); // Buscamos el usuario con el email recibido.
    $codigoEnviado = $_POST["code-confirmation"]; // Recibimos el codigo de confirmacion del formulario de confirmacion de correo.
    // Comprobamos si el codigo de confirmacion es correcto.
    if($usuario[0]->codigo == $codigoEnviado){
        
        $usuario[0]->confirmar(); // Se llama a la funcion confirmar del usuario.
        
    
    }

     // Redirigimos al usuario a la pagina de inicio.
    
}
header("location: /");



