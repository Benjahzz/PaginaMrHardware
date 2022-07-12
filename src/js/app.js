window.addEventListener("load", function () {
  $(".loader").fadeOut(1000);
});
$(function () {
  // Si existe el elemento .glider-carousel se ejecuta la función de crear un Slider
  if (document.querySelector(".glider-carousel")) {
    crearSlider();
  }
  if (document.querySelector(".container-btns-filtrar")) {
    new Glider(document.querySelector(".glider-carousel-filtro"), {
      slidesToShow: 2,
      dots: "#dots",

      arrows: {
        prev: ".glider-prev-filtro",
        next: ".glider-next-filtro",
      },
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
            itemWidth: 300,
            gap: 50,
          },
        },
        {
          breakpoint: 300,
          settings: {
            slidesToShow: "auto",
            itemWidth: 200,
            gap: 20,
          },
        },
      ],
    });
  }

  // Constantes de los modal, para que se puedan usar en cualquier parte
  // Formularios de login y registrar
  // El email

  const modalLogin = document.querySelector(".modal-login");
  const modalRegister = document.querySelector(".modal-registrar");
  const modalSeguidores = document.querySelector(".wrapper-seguidoresProfile");
  const searchComunidad = document.querySelector(".search-comunidad");
  const searchBox = document.querySelector(".search-box");

  const formLogin = document.querySelector(".container-form-login");
  const formRegister = document.querySelector(".container-form-registrar");
  const emailRegister = document.querySelector("input[name='email-register']");
  const megusta = $(".me_gusta-container p").text(); // Parrafo que muestra el numero de me gusta inicial del producto.
  const imagen = $(".wrapper-containerProfile img").attr("src"); // Imagen del producto.

  if (document.querySelector(".header")) {
    $(".btn-login").on("click", function (event) {
      event.preventDefault();
      // Validacion del formulario de login para que no se envie vacio el email y el password, y que sean validos los datos ingresados.
      let isFormValid = $(".container-form-login")[0].checkValidity();
      if (!isFormValid) {
        formLogin.reportValidity();
      } else {
        event.preventDefault();
        let emailLogin = $('input[name="email-login"]').val();

        let passLogin = $('input[name="pass-login"]').val();

        $.ajax({
          type: "POST",
          url: "./Controllers/loginController.php",
          data: { emailLogin, passLogin },

          success: function (response) {
            if (response == "") {
              alertasGenericas(
                "error",
                "Credenciales Incorrectas",
                "Ingrese los datos correctamente"
              );
            } else if (response == 0) {
              alertasGenericas(
                "error",
                "Confirme su Cuenta",
                "Revise su correo Electronico"
              );
            } else {
              alertaLogeado(response);
              console.log(response);
            }
          },
        });
      }
    });

    // Evento del boton Registrar, enviando parametros por ajax hacia registerController
    $(".btn-registrarse").on("click", function (event) {
      event.preventDefault();
      let isFormValid = $(".container-form-registrar")[0].checkValidity();
      if (!isFormValid) {
        formRegister.reportValidity();
      } else {
        event.preventDefault();
        let emailRegister = $('input[name="email-register"]').val();
        let userRegister = $('input[name="user-register"]').val();
        let passRegister = $('input[name="pass-register"]').val();
        let passConfirm = $('input[name="passConfirm-register"]').val();
        let error = $(".error-email");
        if (!$(error).hasClass("d-n")) {
          if (!$(error).hasClass("error-animation")) {
            $(error).addClass("error-animation");
            setTimeout(() => {
              $(error).removeClass("error-animation");
            }, 1000);
          }

          return;
        }
        if (passRegister != passConfirm) {
          alertasGenericas(
            "error",
            "Advertencia",
            "Las contraseñas no coinciden"
          );
          return;
        }
        if (!$("input[name='check-terminos']").is(":checked")) {
          alertasGenericas(
            "error",
            "Advertencia",
            "Debe aceptar los terminos y condiciones"
          );
          return;
        }
        Swal.fire({
          title: "Cargando...",
        });
        Swal.showLoading();
        $.ajax({
          type: "POST",
          url: "./Controllers/registerController.php",
          data: { emailRegister, userRegister, passRegister },

          success: function (response) {
            Swal.close();
            console.log(response);
            var jsonData = JSON.parse(response);
            console.log(jsonData)

            if (jsonData.success == "1") {
              alertaRegistrado();
            } else {
              alertasGenericas("error", "Error", "Error en el registro");
            }
          },
        });
      }
    });

    // Evento del input del registro, llama por ajax a el controlador validarController para validar el email ingresado.
    // Si el email ya existe, se va a ir mostrando en tiempo real un mensaje de error.
    emailRegister.addEventListener("focusout", function () {
      let email = emailRegister.value;

      $.ajax({
        type: "POST",
        url: "./Controllers/validarController.php",
        data: { email },

        success: function (response) {
          if (response == "") {
            if (!$(".error-email").hasClass("d-n")) {
              $(".error-email").addClass("d-n");
            }
          } else {
            if ($(".error-email").hasClass("d-n")) {
              $(".error-email").removeClass("d-n");
            }
          }
        },
      });
    });
    // Si se presiona fuera de la ventana al tener el modal login o el modal registrar, se cierra el modal.
    window.onclick = function (event) {
      if (event.target === modalLogin) {
        $(".modal-login").removeClass("mostrarModal");

        setTimeout(() => {
          $("body").removeClass("body-modalform");
          $("body").css("overflow", "auto");
          $(".fa-xmark").click();
        }, 500);
      } else if (event.target === modalRegister) {
        $(".modal-registrar").removeClass("mostrarModal");

        setTimeout(() => {
          $("body").removeClass("body-modalform");
          $("body").css("overflow", "auto");
          $(".fa-xmark").click();
        }, 500);
      } else if (event.target === modalSeguidores) {
        $(".wrapper-seguidoresProfile").css("visibility", "hidden");
        $(".wrapper-seguidoresProfile").css("opacity", "0");
        $(".wrapper-seguidoresProfile").css("pointer-events", "none");

        setTimeout(() => {
          $("body").removeClass("body-modalform");
          $("body").css("overflow", "auto");
          $(".fa-xmark").click();
        }, 500);
      } else if (
        event.target != document.querySelector(".search-box") &&
        event.target != document.querySelector(".search-comunidad") &&
        event.target != document.querySelector(".search-box p")
      ) {
        $(".search-box").addClass("d-n");
      }
    };
    if ($(".wrapper-seguidoresProfile")) {
      $(".selected-heart").on("click", function () {
        $(".wrapper-seguidoresProfile").css("visibility", "visible");
        $(".wrapper-seguidoresProfile").css("opacity", "1");
        $(".wrapper-seguidoresProfile").css("pointer-events", "all");
        $("body").addClass("body-modalform");
        $("body").css("overflow", "hidden");
      });
    }
    // Evento del boton de abrir del modal login, para que se abra  el modal del login. Se valida si hay otros modal abiertos para cerrarlos.
    $(".container-icon-login").on("click", function () {
      if ($(".modal-setup").hasClass("aparecer-modal")) {
        cerrarModalSetup();

        let timeout2 = setTimeout(() => {
          $(".dialogo-login").html("");

          $(".modal-login").addClass("mostrarModal");
          $(".modal-login .modal-container").addClass("animacion-modal");
        }, 500);
      } else {
        $("body").css("overflow", "hidden");
        $("body").addClass("body-modalform");
        console.log("hola");
        $(".dialogo-login").html("");

        $(".modal-login").addClass("mostrarModal");
        $(".modal-login .modal-container").addClass("animacion-modal");
      }

      let timeout = setTimeout(() => {
        maquinaEscribir("Buenas, Inicia tu sesión", 90, $(".dialogo-login"));
      }, 600);
    });

    // Evento del boton de cerrar del modal setup, para que inicie la funcion cerrarModalSetup() para que se cierre el modal.
    $(".close-setup").on("click", function () {
      cerrarModalSetup();
    });
    // Evento del boton X de los modal de login y registro, para que se cierre el modal abierto.
    $(".x").on("click", function () {
      if ($(".modal-login").hasClass("mostrarModal")) {
        $(".modal-login").removeClass("mostrarModal");

        setTimeout(() => {
          $("body").removeClass("body-modalform");
          $("body").css("overflow", "auto");
        }, 500);
      } else if ($(".modal-registrar").hasClass("mostrarModal")) {
      }
      $(".modal-registrar").removeClass("mostrarModal");
      setTimeout(() => {
        $("body").removeClass("body-modalform");
        $("body").css("overflow", "auto");
      }, 500);
    });

    // Evento del boton de abrir del modal registrar cuando se encuentra en el modal Login, para que se abra  el modal del registro.
    $(".btn-registrar").on("click", function () {
      $(".modal-login .modal-container").removeClass("animacion-modal");
      $(".modal-login").removeClass("mostrarModal");
      $(".modal-login .modal-container").addClass("moverContenedor");
      $(".modal-registrar").addClass("colorModal");

      $(".modal-registrar").addClass("mostrarModal");

      maquinaEscribir("Sé parte de la comunidad", 110, $(".dialogo-registrar"));
    });
    // Evento del boton de abrir el setup, para que se abra  el modal del setup.
    $(".btn-mostrar-setup").on("click", function () {
      $(".modal-setup").removeClass("animacion-modal-setup");
      $(".modal-setup").addClass("animacion-modal-setup-exit");
      $(".modal-setup").css("display", "block");

      setTimeout(() => {
        $(".modal-setup").addClass("aparecer-modal");
      }, 300);
    });

    // Evento Click del boton agregar + setup que abre un sweetalert para que se agregue un nuevo setup.
    // Este sweet alert tiene como cuerpo un formulario con el campo nombre del setup.

    $(".profile-setup .fa-plus").on("click", function () {
      Swal.fire({
        title: "Agregar Setup",
        html: `<form action='Controllers/Usuario/perfilController.php?action=setup' method='POST' class="form-agregarSetup">
        <input type="text" id="setup-nombre" class="swal2-input" placeholder="Ingrese el nombre" name='setup-nombre'>
        
       
        </form>`,
        confirmButtonText: "Agregar",
        preConfirm: () => {
          let setupNombre =
            Swal.getPopup().querySelector("#setup-nombre").value;

          if (!setupNombre) {
            Swal.showValidationMessage(`Ingresa un nombre para tu setup`);
          } else {
            $(".form-agregarSetup").submit();
          }
        },
      });
    });
    $("select[name='ordenar']").on("change", function () {
      let ordenar = $(this).val();
      if(ordenar == 0){
        return;
      }
      let urlParameters = window.location.search;
      const urlParams = new URLSearchParams(urlParameters);
      
      if (urlParams.has("search")) {
        console.log("aa")
        if (urlParams.has("filtroTienda")) {
          window.location.href = `?search=${urlParams.get(
            "search"
          )}&filtroTienda=${urlParams.get("filtroTienda")}&ordenar=${ordenar}`;
        }else{
          window.location.href = `?search=${urlParams.get(
            "search"
          )}&ordenar=${ordenar}`;
        }
      } else if (urlParams.has("tipoProducto")) {
        
        if (urlParams.has("filtroTienda")) {
          window.location.href = window.location.pathname+`?tipoProducto=${urlParams.get(
            "tipoProducto"
          )}&filtroTienda=${urlParams.get("filtroTienda")}&ordenar=${ordenar}`;
          console.log(window.location.pathname+`?tipoProducto=${urlParams.get(
            "tipoProducto"
          )}&filtroTienda=${urlParams.get("filtroTienda")}&ordenar=${ordenar}`)
        }else{
          window.location.href = window.location.pathname+`?tipoProducto=${urlParams.get("tipoProducto")}&ordenar=${ordenar}`;
        }
      }
    });
  }
  // Evento del botón login, enviando parametros por ajax hacia loginController

  // Evento Click del contenedor que abre una lista con los setup para agregar un producto al setup seleccionado

  $(".container-addItem").on("click", function () {
    $(".dropdown-menuAdd").toggleClass("mostrarDropdown");
    $(".downArrow_AddSetup").toggleClass("rotarArrow");
  });
  // Lista de los setup que se muestran en el dropdown de agregar producto al setup.
  // Cuando se presiona un setup de la lista, este llamará por ajax al controlador setupController para que se actualice el setup del usuario.
  $(".dropdown-menuAdd button").on("click", function () {
    let setupNombre = $(this).val();
    let productoId = $(this).attr("data-productoId");
    console.log(productoId);
    $.ajax({
      type: "POST",
      url: "./Controllers/Setup/setupController.php?action=actualizarSetup",
      data: { setupNombre, productoId },

      success: function (response) {
        Swal.fire({
          toast: true,
          icon: "success",
          title: "Se agregó el producto al setup",
          position: "top-right",
          showConfirmButton: false,
          timer: 2000,
        });
        setTimeout(() => {
          location.reload();
        }, 1500);
      },
    });
  });

  // Muestra el textarea para que el usuario escriba una respuesta a un comentario.
  $(".responder-comentario").on("click", function () {
    let comentarioId = $(this).prop("id");
    let args = comentarioId.split("-");
    $("#form-respuesta-" + args[args.length - 1]).toggleClass("hidden");
  });
  // Evento click del boton de votar un comentario, para que se envie una peticion ajax al controlador comentarioController para que se actualice el voto del comentario.
  $(".container-votos .fa-caret-up").on("click", function () {
    if ($(this).hasClass("votar-noLogeado")) {
      $(".container-icon-login").click();
      return;
    }
    $(this).toggleClass("comentario-votado");
    let spanVotos = $(this).siblings("span");
    let cantidad = $(spanVotos).text();
    if ($(this).hasClass("comentario-votado")) {
      cantidad = parseInt(cantidad) + 1;
    } else {
      cantidad = parseInt(cantidad) - 1;
    }
    console.log(cantidad);
    $(spanVotos).text(cantidad);
    let idComentario = $(this).attr("data-idComentario");
    $.ajax({
      type: "POST",
      url: "./Controllers/Comentario/comentarioController.php?action=votar",
      data: { idComentario },
      success: function (response) {
        console.log(response);
      },
    });
  });
  // Evento del boton search (lupa) del buscador principal, este buscará el producto con la variable search que se ingrese en el input.
  $(".search-button").on("click", function () {
    let textoBuscar = $(this).siblings().val();
    window.location.href = `buscarProducto.php?search=${textoBuscar}`;
  });

  // Opcion de rating en perfil de usuario, para mostrar el rating del usuario y remover los comentarios

  $("div#option-rating").on("click", function () {
    if ($("section#stats-rating").hasClass("d-n")) {
      $("section#stats-comments").addClass("d-n");
      $("section#stats-rating").removeClass("d-n");
    }
  });
  // Opcion de comentarios en perfil de usuario, para mostrar el comentarios del usuario y remover los rating
  $("div#option-comments").on("click", function () {
    if ($("section#stats-comments").hasClass("d-n")) {
      $("section#stats-rating").addClass("d-n");
      $("section#stats-comments").removeClass("d-n");
    }
  });
  // Select de los setup del usuario, para que se muestre el seleccionado y se muestre el precio.
  $(".select-setup__profile").on("change", function () {
    let index = this.value;
    $(".container-setup__table").addClass("d-n");
    let totalSetup = 0;
    let setup = $(`.container-setup__table#setup-${index}`);
    let trs = setup.find("tr");
    trs.each(function (index) {
      let precioProducto = $(this).find("td").eq(2).text().replace("$", "");
      if (precioProducto != 0) {
        totalSetup += parseInt(precioProducto);
      }
    });
    console.log(totalSetup);
    setup.removeClass("d-n");
    $(".setup-profile-total span").html("$" + totalSetup);
  });
  // Select de los setup flotantes del usuario, para que se muestre el seleccionado y se muestre el precio.
  $(".select-setup__profile-float").on("change", function () {
    let index = this.value;
    $(".container-setup__table-float").addClass("d-n");
    let totalSetup = 0;
    let setup = $(`.container-setup__table-float#setup-${index}`);
    let trs = setup.find("tr");
    trs.each(function (index) {
      let precioProducto = $(this).find("td").eq(2).text().replace("$", "");
      if (precioProducto != 0) {
        totalSetup += parseInt(precioProducto);
      }
    });
    console.log(totalSetup);
    setup.removeClass("d-n");
    $(".setup-profile-total span").html("$" + totalSetup);
  });
  // Evento del icono editar del perfil del usuario para mostrar el formulario de editar perfil.
  $(".editarPerfil-container .fa-edit").on("click", function () {
    if ($(".container-editProfile").hasClass("d-n")) {
      $(".container-editProfile").removeClass("d-n");
      $(".img-container__profile").css("filter", "contrast(.8)");

      $(".wrapper-containerProfile i").removeClass("d-n");
      $(".container-informationProfile").addClass("d-n");
    } else {
      $(".container-editProfile").addClass("d-n");
      $(".wrapper-containerProfile i").addClass("d-n");
      $(".wrapper-containerProfile img").attr("src", imagen);
      $(".img-container__profile").css("filter", "initial");
      $(".container-informationProfile").removeClass("d-n");
    }
  });

  // Mostrar en tiempo real la imagen que se sube al perfil del usuario.

  $(".editProfile__form #getAvatarFile").on("change", function () {
    let input = $(this).get(0).files[0];
    if (input) {
      const reader = new FileReader();
      reader.addEventListener("load", function () {
        $(".wrapper-containerProfile img").attr("src", reader.result);
      });
      reader.readAsDataURL(input);
    }
  });

  //  Evento de la calificacion de estrellas para calificar un producto. Se llama a la funcion calificarProducto.
  $("button.star-calification").on("click", function () {
    calificarProducto($(this).val());
  });
  // Si existe una estrella con la clase selected, se les agrega a sus hermanos inferiores la clase selected.
  if ($(".star-calification .selected")) {
    let value = $(".star-calification.selected").val();
    console.log(value);
    $(".star-calification").each(function (index) {
      if (index < value) {
        $(this).html("&#9733");
      }
    });
  }
  // Si se selecciona una estrella se le asigna el contenido &#9733 que es una estrella completa, para que se muestren las estrellas seleccionadas.
  $(".star-calification").on("click", function () {
    let value = $(this).val();
    $(".calificacion-producto__puntaje").html(value + ".0/5.0");
    $(".star-calification").each(function (index) {
      if (index < value) {
        $(this).html("&#9733");
      } else {
        $(this).html("&#9734");
      }
    });
  });

  // Evento click del icono del basurero para eliminar un setup del usuario
  // Se llama por ajax a el controlador setupController para eliminar el setup.
  $(".item-header .fa-trash-can").on("click", function () {
    Swal.fire({
      icon: "warning",
      title: "¿Eliminar Setup?",
      text: "¿Seguro que quieres eliminar el Setup?",

      showConfirmButton: true,
      confirmButtonText: "Eliminar",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      cancelButtonColor: "#E57979",
      confirmButtonColor: "#3085d6",
    }).then((result) => {
      if (result.isConfirmed) {
        let bloqueElemento = $(".select-setup__profile option:selected");
        let bloqueElementoSetupFloat = $(
          ".select-setup__profile-float option:selected"
        );
        let nombreSetup = $(bloqueElemento).text();
        let index = $(".select-setup__profile").val();
        console.log(index);
        console.log($(`#setup-${index}`));
        $(`#setup-${index}`).remove();
        $(`div#setup-${index}`).remove();
        bloqueElemento.remove();
        bloqueElementoSetupFloat.remove();
        $(".setup-profile-total span").html("$0");
        let setupIndex = $(".select-setup__profile option:selected").val();
        $(`#setup-${setupIndex}`).removeClass("d-n");
        $(`div#setup-${setupIndex}`).removeClass("d-n");

        $.ajax({
          type: "POST",
          url: "./Controllers/Setup/setupController.php?action=eliminarSetup",
          data: { nombreSetup },
          success: function (response) {
            console.log(response);
          },
        });
      }
    });
  });

  // Evento que cada vez que se escribe en el search principal de comunidad, buscará a un usuario con el nombre que se escribe en tiempo real.
  // retornará una lista de usuarios que coincidan con el nombre que se escribe.
  $(".search-comunidad").on("keyup", function () {
    let nombreUsuario = $(this).val();

    if (nombreUsuario == "") {
      $(".search-box").addClass("d-n");
      return;
    }
    console.log(nombreUsuario);
    $.ajax({
      type: "POST",
      url: "./Controllers/Comunidad/comunidadController.php?action=buscarUsuario",
      data: { nombreUsuario },
      success: function (response) {
        let usuario = JSON.parse(response);

        $(".search-box").removeClass("d-n");

        if (usuario.length != 0) {
          $(".search-box").html(
            usuario
              .map(function (usuario) {
                return `<a href="perfil.php?idUsuario=${usuario.idUsuario}" class="search-box-container">
              <div class="search-box-container-icon">
                <i class="fa-solid fa-user"></i>
              </div>
              <div class="search-box-container-content">
                <p >${usuario.username}</p>
                
              </div>
            </a>`;
              })
              .join("")
          );
        } else {
          $(".search-box").html(
            `<p style="text-align: center">No se encontraron resultados</p>`
          );
        }
      },
    });
  });

  // Cuando se muestra el contenedor con los resultados de los usuarios a buscar y se clickea fuera de este, se ocultará.

  // Evento al hacer focus en el input de buscador principal, si este no tiene ningun valor, desaparecerá el contenedor con los antiguos resultados.

  // Evento click del icono de megusta (Corazon) para darle like a un perfil, este sumará 1 a la cantidad de likes del perfil o restará 1 si ya lo había dado.
  // Se llama por ajax a el controlador perfilController para darle like a un perfil.
  $(".me_gusta-container .icon-meGusta").click(function () {
    containerMegusta = $(".me_gusta-container p");
    let contador = parseInt(containerMegusta.text());
    idUsuario = $(this).parent().attr("data-idUsuario");
    if ($(this).hasClass("icon-meGusta__noLog")) {
      $(".container-icon-login").click();

      return;
    }
    if (
      $(this).hasClass("selected-heart") ||
      contador == parseInt(megusta) + 1
    ) {
      containerMegusta.html(contador - 1);
      $(this).removeClass("selected-heart");
    } else {
      $(this).addClass("selected-heart");
      containerMegusta.html(contador + 1);
    }
    $.ajax({
      type: "POST",
      url: "./Controllers/Usuario/perfilController.php?action=seguirUsuario",
      data: { idUsuario: idUsuario },
      success: function (response) {},
    });
  });

  $(".input-confirmar").on("focus", function () {
    $(this).toggleClass("active");
  });
  $(".input-confirmar").on("focusout", function () {
    $(this).toggleClass("active");
  });

  $(".container-opcionesComentario .fa-ellipsis-vertical").click(function () {
    $(this).siblings(".dropbox-menuDots").toggleClass("d-n");
  });
  $("span[name='span-reportarComentario']").click(function () {
    let idComentario = $(this).attr("data-id");
    swal
      .fire({
        title: "Selecciona el motivo",
        input: "select",
        inputOptions: {
          1: "Inapropiado",
          2: "Ofensivo",
          3: "Spam",
          4: "Otro",
        },
        inputPlaceholder: "No seleccionado",
        inputValidator: (value) => {
          return new Promise((resolve) => {
            if (value !== "") {
              resolve();
            } else {
              resolve("Debes seleccionar un motivo");
            }
          });
        },
        confirmButtonText: "Reportar",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.value) {
          let idTipoReporte = result.value;
          $.ajax({
            type: "POST",
            url: "./Controllers/Comentario/comentarioController.php?action=reportarComentario",
            data: { idComentario, idTipoReporte },
            success: function (response) {
              console.log(response);
            },
          });
        }
      });
  });
  $(".container-setup__table .fa-close").on("click", function () {
    let bloqueElemento = $(".select-setup__profile option:selected");
    let nombreSetup = bloqueElemento.text();
    let idProducto = $(this).attr("data-idProducto");
    let bloquePadre = $(this).parent().parent();
    $.ajax({
      type: "POST",
      url: "./Controllers/Setup/setupController.php?action=eliminarProducto",
      data: { idProducto, nombreSetup },
      success: function (response) {
        let componente = $(bloquePadre).find("td");
        let nombreProducto = $(componente)[0].innerText;
        $(componente)[1].innerText = nombreProducto;
        let precio = $(componente)[2].innerText;
        $(componente)[2].innerText = "$0";

        let precioTotal = $(".setup-profile-total span").html();
        let precioTotalDot = precioTotal.replace("$", "");
        let precioTotalInt = parseInt(precioTotalDot.replace(".", ""));
        let precioDot = precio.replace("$", "");
        let precioInt = parseInt(precioDot.replace(".", ""));
        let precioTotalNuevo = precioTotalInt - precioInt;
        $(".setup-profile-total span").html(`$${precioTotalNuevo}`);
      },
    });
  });
});

// Se crea un slider con la libreria Glider en todos los elementos con la clase .glider-carousel y .carousel-2.
function crearSlider() {
  new Glider(document.querySelector(".glider-carousel"), {
    slidesToShow: 1,
    dots: "#dots",

    arrows: {
      prev: ".glider-prev",
      next: ".glider-next",
    },
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 5,
          itemWidth: 300,
        },
      },
      {
        breakpoint: 300,
        settings: {
          slidesToShow: "auto",
          itemWidth: 200,
          gap: 20,
        },
      },
    ],
  });
  if (document.querySelector(".carousel-2")) {
    new Glider(document.querySelector(".carousel-2"), {
      slidesToShow: 1,
      dots: "#dots",

      arrows: {
        prev: ".prev-2",
        next: ".next-2",
      },
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 5,
            itemWidth: 300,
          },
        },
        {
          breakpoint: 300,
          settings: {
            slidesToShow: "auto",
            itemWidth: 200,
            gap: 20,
          },
        },
      ],
    });
  }
}

// Funcion que ciera el modal setup.

function cerrarModalSetup() {
  setTimeout(() => {
    $(".modal-setup").removeClass("animacion-modal-setup-exit");
    $(".modal-setup").addClass("animacion-modal-setup");
  }, 100);

  $(".modal-setup").removeClass("aparecer-modal");

  setTimeout(() => {
    $(".modal-setup").css("display", "none");
  }, 500);
}

// Funcion de efecto de maquina de escribir de los modal login y registrar.
function maquinaEscribir(texto = "", tiempo = 200, etiqueta) {
  let arrayChars = texto.split("");
  $(etiqueta).html("");
  let cont = 0;

  let escribir = setInterval(function () {
    if (etiqueta.find("span")) {
      etiqueta.find("span").remove();
      etiqueta.append(arrayChars[cont - 1]);
    }

    var span = "<span class=border-right>" + arrayChars[cont] + "</span>";
    etiqueta.append(span);

    cont++;
    $(".fa-xmark").on("click", function () {
      clearInterval(escribir);
    });

    if (cont === arrayChars.length) {
      etiqueta.find("span").remove();
      etiqueta.append(arrayChars[cont - 1]);
      clearInterval(escribir);
    }
  }, tiempo);
}

// Alerta al logearse
function alertaLogeado(nombre) {
  Swal.fire({
    icon: "success",
    title: "Logeado Correctamente",
    text: "Bienvenid@ " + nombre,
    timer: 1000,
  }).then((result) => {
    location.href = "index.php";
  });
}

// Alerta al registrarse
function alertaRegistrado(nombre) {
  Swal.fire({
    imageUrl: "build/img/177-envelope-mail-send-outline.gif",
    imageWidth: 150,
    imageHeight: 150,
    title: "Se ha registrado correctamente",
    text: "Hemos enviado un mail de confirmación",
    timer: 2500,
  }).then((result) => {
    location.href = "index.php";
  });
}
// SweetAlert reutilizable para mostrar un mensajes.
function alertasGenericas(icon, title, text) {
  Swal.fire({
    icon: icon,
    title: title,
    text: text,
  });
}
// Funcion de Calificar un producto que llamará por ajax a el controlador productoController para calificar un producto.
function calificarProducto(calificacion) {
  let idProducto = $("button.star-calification").attr("data-id");
  let url =
    "./Controllers/Producto/productoController.php?action=calificarProducto";

  $.ajax({
    url: "./Controllers/Producto/productoController.php?action=calificarProducto",
    type: "POST",
    data: { calificacion, idProducto },
    success: function (response) {
      if (response == "error") {
        $(".container-icon-login").click();
        $(".star-calification").html("&#9734");
      }
    },
  });
}
// Evento click de la hamburguesa para abrir el menu.
$(".open-hamburguer").click(function () {
  $("body").addClass("body-modalform");
  $("body").css("overflow", "hidden");
  $(".wrapper-menuH").addClass("mostrar-menuH");
  $(".wrapper-menuH").removeClass("quitar-menuH");
});

// Eventos del Menu, como mostrar o cerrar el menu, como tambien las animaciones.
$(".close-hamburguer").click(function () {
  $(".wrapper-menuH").addClass("quitar-menuH");
  $("body").removeClass("body-modalform");
  $("body").css("overflow", "visible");
  setTimeout(() => {
    $(".wrapper-menuH").removeClass("mostrar-menu-hard");
    $(".wrapper-menuH").removeClass("mostrar-menuH");
    $(".wrapper-menuH").removeClass("mostrar-menu-perifericos");
  }, 400);
});

$(".abrir-hardwareH").click(function () {
  $(".wrapper-menuH").addClass("quitar-menuH");
  $(".wrapper-menuH").addClass("mostrar-menu-hard");
});
$(".abrir-perifericosH").click(function () {
  $(".wrapper-menuH").addClass("quitar-menuH");
  $(".wrapper-menuH").addClass("mostrar-menu-perifericos");
});

$(".volverH").click(function () {
  $(".wrapper-menuH").removeClass("quitar-menuH");
  $(".wrapper-menuH").removeClass("mostrar-menu-hard");
  $(".wrapper-menuH").removeClass("mostrar-menu-perifericos");
  $(".wrapper-menuH").addClass("mostrar-menuH");
});
