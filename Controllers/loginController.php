<?php

use App\Usuario;

require '../includes/app.php';
// Controlador de Login de cuenta de usuario.
//  Si el request_method es post, se comprueba si el usuario existe en la base de datos.
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $emailLogin = $_POST['emailLogin'];

    $passLogin = $_POST['passLogin'];
    
    // Se llama a la funcion autenticarUsuario de la clase Usuario. devuelve false si no existe el usuario.
    $usuario = Usuario::autenticarUsuario($emailLogin,$passLogin);
    // Si el email y la contraseña son correctos, se guarda en la sesion el usuario.
    if($usuario){
        // si el usuario esta confirmado, se guarda en la sesion el usuario, en caso contrario, se muestra un mensaje de error y se sale de la funcion.
        if($usuario->confirmacion == 0){
            echo json_encode(0);
            exit;
        }
        // se crea una sesion con el usuario.
        session_start();
        $_SESSION['login'] = $usuario;

        
        
        echo json_encode($usuario->username);
    
    }else{
        
        exit;
    }
}else{
    header('location:/');
}




