<?php

use App\Usuario;
use PHPMailer\PHPMailer\PHPMailer;

require '../includes/app.php';
//  Si el request_method es post, se procede a registrar el usuario.
if ($_SERVER["REQUEST_METHOD"] = "POST") {


    // Se recogen los datos del formulario.
    $emailRegister = $_POST["emailRegister"];

    $userRegister = $_POST["userRegister"];
    $passRegister = $_POST["passRegister"];

    
    if (!filter_var($emailRegister, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array('error' => 0));
        return;
    }
    $codigo = rand(1000, 9999); // Se genera un codigo de 4 cifras random.
    $args = [

        "email" => $emailRegister,
        "username" => $userRegister,
        "password" => $passRegister,
        "codigo" => $codigo

    ];
    // Se crea un objeto usuario con los datos recibidos.
    $usuario = Usuario::crearObjeto($args);
    // Se registra el usuario con la funcion registrarUsuario() de la clase Usuario.


    // Partes del correo a enviar. 

    // Encabezado del correo.
    $header = "Confirmacion de cuenta, MRHardware";
    // Cuerpo del correo.
    $mensaje = "Su cuenta ha sido creada correctamente, ingrese a el siguiente enlace para confirmar su cuenta. <br>";
    $url = "<a href='http://localhost/mrHardware/confirmar.php?email={$emailRegister}'>" . "Confirma aquí tu cuenta" . "</a>";
    // Estructura con estilos del correo y el codigo de confirmacion.
    $mensajeCompleto = '
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed;background-color:#f9f9f9" id="bodyTable">
        <tbody>
            <tr>
                <td style="padding-right:10px;padding-left:10px;" align="center" valign="top" id="bodyCell">
                    
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperBody" style="max-width:600px">
                        <tbody>
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tableCard" style="background-color:#fff;border-color:#e5e5e5;border-style:solid;border-width:0 1px 1px 1px;">
                                        <tbody>
                                            <tr>
                                                <td style="background-color:#e57979;font-size:1px;line-height:3px" class="topBorder" height="3">&nbsp;</td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="padding-bottom: 20px;" align="center" valign="top" class="imgHero">
                                                    <a href="#" style="text-decoration:none" target="_blank">
                                                        <img alt="" border="0" src="https://lh3.googleusercontent.com/pw/AM-JKLV1OhVdAkLPjSBKoHAjGM95qfcWqhptkNh-Qy6hsKiFindQbDj1FqYrCnu9KKt8UBuh2h1lQ-NAw3E7vcuY8oEHDQXLekxd4tLzPMLPmQCNR4oMqQmw_YmNGE0HxC0V3t6sLHGUxmzIWIYJy7eLR_CZ=w429-h276-no?authuser=0" style="width:50%;max-width:600px;height:auto;display:block;color: #f9f9f9;" width="600">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 5px; padding-left: 20px; padding-right: 20px;" align="center" valign="top" class="mainTitle">
                                                    <h2 class="text" style="color:#000;font-family:Poppins,Helvetica,Arial,sans-serif;font-size:28px;font-weight:500;font-style:normal;letter-spacing:normal;line-height:36px;text-transform:none;text-align:center;padding:0;margin:0">Hola "' . $userRegister . '"</h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 30px; padding-left: 20px; padding-right: 20px;" align="center" valign="top" class="subTitle">
                                                    <h4 class="text" style="color:#999;font-family:Poppins,Helvetica,Arial,sans-serif;font-size:16px;font-weight:500;font-style:normal;letter-spacing:normal;line-height:24px;text-transform:none;text-align:center;padding:0;margin:0">Verifica tu cuenta de MrHardware</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:20px;padding-right:20px" align="center" valign="top" class="containtTable ui-sortable">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tableDescription" style="">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding-bottom: 20px;" align="center" valign="top" class="description">
                                                                    <p class="text" style="color:#666;font-family:"Open Sans",Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;font-style:normal;letter-spacing:normal;line-height:22px;text-transform:none;text-align:center;padding:0;margin:0">Haga click en el enlace para confirmar su cuenta, Muchas gracias por registrarse. Tu codigo es:' . $codigo . '</p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tableButton" style="">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding-top:20px;padding-bottom:20px" align="center" valign="top">
                                                                    <table border="0" cellpadding="0" cellspacing="0" align="center">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="background-color: #e57979; padding: 12px 35px; border-radius: 50px;" align="center" class="ctaButton"> <a href="http://localhost/mrHardware/confirmar.php?email=' . $emailRegister . '" style=";
                                                                                
                                                                                pointer-events: all;
                                                                                visibility: visible;
                                                                                box-sizing: inherit;
                                                                                background-color: #e57979;
                                                                                width: 60%;
                                                                                border-radius: .8rem;
                                                                                color: #fff;
                                                                                border: none;
                                                                                font-weight: 700;
                                                                                font-size: 1.6rem;
                                                                                height: 12%;" target="_blank" class="text">Confirmar Email</a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:1px;line-height:1px" height="20">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="space">
                                        <tbody>
                                            <tr>
                                                <td style="font-size:1px;line-height:1px" height="30">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>';
    // se crea una clase phpMailer.
    $mail = new PHPMailer();
    
    $header = "Content-Type: text/html; charset=UTF-8";
    try {
        // se configura el servidor de correo.
        
        $mail->isSMTP();
        $mail->isHTML();
        $mail->Host = "smtp.office365.com";
        $mail->SMTPAuth = true;
        $mail->Username = "xxxxxxxxxxxxxxxxxxxxxxxxxxx"; // Correo de quien envia el correo.
        $mail->Password = "xxxxxxxxxxxxxxxxxxxxxxxxxxxx"; // Contraseña del correo de quien envia el correo.
        $mail->SMTPSecure = "tls";
        $mail->SMTPAutoTLS = false;
        $mail->Port = 587;

        $mail->setFrom("xxxxxxxxxxxxxxxxxxxxxxxxxxxx", "MrHardware"); // Correo de quien envia el correo.

        $mail->addAddress($emailRegister, "Mailer"); // Correo de quien recibe el correo.

        $mail->isHTML(true);
        $mail->Subject = "Confirmación de tu cuenta"; // Asunto del correo.
        $mail->Body = $mensajeCompleto; // Cuerpo del correo.
        $mail->AltBody = $mensajeCompleto;
        $mail->msgHTML($mensajeCompleto); // Cuerpo del correo.
        $mail->CharSet = 'UTF-8';
        $mail->AllowEmpty = true;
        $mail->SMTPDebug = 0;
        $mail->send();

        echo json_encode(array('success' => 1));
        
        
        $mail->smtpClose(); // Cierre del servidor de correo.
        $usuario->registrarUsuario();
    } catch (Exception $e) {
        $error = $e;
        ob_clean();
        echo json_encode(array('error' => 0));
    }

    
    
} else {
    header("location: /");
}
