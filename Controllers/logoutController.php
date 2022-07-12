<?php
// Controlador de Logout de cuenta de usuario.
session_start();
// Si hay una sesion iniciada, se cierra la sesion.
if ($_SESSION["login"]) {
    
    session_destroy();
    
}
header("location: ../index.php");
