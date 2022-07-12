<?php

use App\Usuario;

if (isset($_GET["email"])) {
    $email = $_GET["email"];
} else {
    header("location: /");
};

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MrHardware</title>
    <link rel="stylesheet" href="build/css/index.css">
</head>

<body class="body-confirmation">
    <div class="container-confirmar">
        <form action="Controllers/confirmarController.php" method="POST" class="container">
            <img src="build/img/icon.png" alt="a">
            <div class="form-control">
                <input type="text" class="input-confirmar code-confirmation" name="code-confirmation">
                <input type="text" class="input-confirmar email-confirmation" name="email-confirmation" value="<?= $email ?>" readonly>
                <input type="submit" name="btn-enviar" class="btn-confirmar" value="Confirmar">
            </div>
        </form>
    </div>

</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="build/js/bundle.min.js"></script>
</html>