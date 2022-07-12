<?php

use App\Megusta;
use App\Setup;
use App\Usuario;

require '../../includes/app.php';

session_start();
// Controlador de un Perfil

$perfilAutenticado = $_SESSION["login"] ?? null;

$action = $_GET["action"] ?? null;


switch ($action) {


        // Agrega un setup a un usuario.

    case "setup":
        $nombreSetup =  $_POST["setup-nombre"] ?? null;
        // Comprobamos si el setup-nombre viene vacio, si es asi, no se crea el setup y te redirige a el perfil.
        if ($nombreSetup == null) {
            header("location: ../../perfil.php");
        }
        // Comprobamos si el usuario esta autenticado, si es asi, se crea el setup.
        if ($perfilAutenticado != null) {
            Setup::registrarSetup($perfilAutenticado->idUsuario, $nombreSetup);
        }
        header("location: ../../perfil.php");

        break;


        // Actualiza los datos de el perfil de un usuario(nombre,descripcion,instagram,pais y avatar).
    case "actualizarDatos":

        $imagen = $_FILES["input-image"] ?? null;
        $carpetaImagenes = '../../build/imagenesUsuarios/';


        // Comprobamos si ya existe la carpeta para las imagenes de usuarios, si no existe, la creamos.
        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }
        // si la imagen viene vacia, no se actualiza el avatar.
        if ($imagen['name']) {
            // Si el perfil tiene un avatar, se elimina el avatar antiguo.
            if ($perfilAutenticado->avatar) {
                unlink($carpetaImagenes . $perfilAutenticado->avatar);
            }
            // se crea un nombre aleatorio para la imagen.

            $nombreImagen = md5(uniqid(rand(), true)) . '.jpg';


            $perfilAutenticado->avatar = $nombreImagen;

            // se guarda la imagen en la carpeta de imagenes de usuarios.
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
        } else {

            $nombreImagen = $perfilAutenticado->avatar;
        }
        // se actualiza el perfil con los datos POST.
        $perfilAutenticado->nombre = $_POST["input-nombre"] ?? null;
        $perfilAutenticado->descripcion = $_POST["description-textProfile"] ?? null;
        $perfilAutenticado->instagram = $_POST["input-instagram"] ?? null;
        $pais = $_POST["input-pais"] ?? null;

        $perfilAutenticado->Pais_idPais = $pais == 0 ? null : $pais;
        // se actualiza el perfil en la base de datos llamando al metodo ActualizarUser.
        Usuario::ActualizarUser($perfilAutenticado);
        header("location: ../../perfil.php");
        break;

        // El usuario sigue a un usuario.
    case "seguirUsuario":

        $idUsuario = $_POST["idUsuario"] ?? null; // id del usuario que se va a seguir.

        // Si viene vacio, te devuelve al perfil.
        if ($idUsuario == null) {
            header("location: ../../perfil.php");
        }
        // Si el usuario esta autenticado, seguimos al usuario.
        if ($perfilAutenticado != null) {
            // Si encuentra un registro de seguimiento, se elimina el registro.
            if (Megusta::find("Usuario_idUsuario", $idUsuario, $perfilAutenticado->idUsuario)) {
                Megusta::unfollowUsuario($idUsuario);
            } else {
                // Si no encuentra un registro de seguimiento, se crea uno.
                Megusta::seguirUsuario($idUsuario);
            }
        }
        header("location: ../../perfil.php");
        break;
    case "enviarCorreo":
        $correo = $_POST["correo"] ?? null;
        $nombre = $_POST["nombre"] ?? null;
        $asunto = $_POST["asunto"] ?? null;
        $mensaje = $_POST["mensaje"] ?? null;
        // Si el correo viene vacio, no se envia el correo.
        if ($correo == null || $nombre == null || $asunto == null || $mensaje == null) {
            header("location: ../../contactanos.php");
        }
        // Si el usuario esta autenticado, se envia el correo.
        // Se envia el correo.
        Usuario::enviarCorreo($correo, $nombre, $asunto, $mensaje);

        header("location:../../contactanos.php?mensaje=1");
        break;
}
