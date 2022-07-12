<?php require "includes/app.php";?>
<?php include 'includes/templates/header.php' ?>
<section class="banner-contact">
    <p class="text-contact">Contáctanos</p>
</section>

<form action="Controllers/Usuario/perfilController.php?action=enviarCorreo" method="POST" class="containerContactos container">
    <div class="inputProfile-contact">
        <input class="txt-contact" type="email" placeholder="Email" name="email">
        <input class="txt-contact" type="text" placeholder="Nombre" name=nombre>
    </div>
    <div class="containerContactAsunto">
        <input class="txtAsunto-contact" type="text" placeholder="Asunto">
    </div>
    <textarea class="txtComentario-contact" rows="10" cols="20" placeholder="Mensaje" name="mensaje"></textarea>
    <div>
        <button class="btnEnviar-contact">Enviar</button>
    </div>
</form>

<section class="bottom-contact">
    <div class="titulo-contact">
        <p class="text-contact">¿Quieres enviarnos un correo personalmente?</p>
    </div>
    <div class="titulont-contact">
        <div>
            <img src="build/img/iconcircle.png" alt="logo">
        </div>
        <div class="text-contact">
            Email: <label class="mail-contact">Mrhardwareoficial@gmail.com</label>
        </div>
    </div>

</section>


<?php include 'includes/templates/footer.php' ?>
</body>

</html>