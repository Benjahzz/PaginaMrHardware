<!-- Modal de registro de una cuenta -->
<div class="modal modal-registrar">
    <div class="modal-container">
        <section class="bienvenida-form">
            <div class="wrapper-bienvenida-items">
                <div class="dialogo-container">
                    <p class="dialogo-maquina dialogo-registrar"></p>
                </div>
                <div class="robot-animation">
                    <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_zzjqnepa.json" background="transparent" speed="1" loop autoplay class="robot-animation"></lottie-player>
                </div>
            </div>
        </section>
        
        
        <form action="#" class="container-form container-form-registrar">
            <div class="x close-modal-registrar">
                <i class="fa solid fa-xmark"></i>
            </div>
            <div class="iniciarS">
                Registrar
            </div>
            <div class="email-input-container">
                <input class="txt" type="text" placeholder="Email" class="mail" name="email-register" required>
                <span class="error-email d-n">Ya existe ese email</span>
            </div>
            <input class="txt" type="text" placeholder="Usuario" name="user-register" required>
            <input class="txt" type="password" placeholder="Contraseña" name="pass-register" required>
            <input class="txt" type="password" placeholder="Confirmar Contraseña" name="passConfirm-register" required>
            <div class="chk">
                <input type="checkbox" class="chek" name="check-terminos" id="">
                <div>
                    Aceptar políticas de seguridad,<br> términos y condiciones.
                </div>
            </div>
            <button class="registrar btn-registrarse btn-principales">Regístrate</button>

        </form>
    </div>
</div>