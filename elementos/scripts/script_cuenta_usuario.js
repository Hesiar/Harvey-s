$(document).ready(function () {
    $("#editar").on("click", function () {
        $(".perfil").hide();
        $(".editar-perfil").show();
    });

    $("#cancelar-edicion").on("click", function () {
        $(".editar-perfil").hide();
        $(".perfil").show();
        $("#formEditar")[0].reset();
        $(".error-message").remove();
        $("button[type='submit']").prop("disabled", true);
    });

    $("#cerrar-sesion").on("click", function () {
        window.location.href = "../autenticacion/cerrar_sesion.php";
    });

    function limpiarError(input) {
        input.next(".error-message").remove();
    }

    function validarCampoVacio(input, mensajeError) {
        limpiarError(input);
        if (input.val().trim() === "") {
            input.after("<p class='error-message' style='color: red;'>" + mensajeError + "</p>");
            return false;
        }
        return true;
    }

    function validarEmail() {
        let email = $("#edit-email").val().trim();
        let regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        limpiarError($("#edit-email"));

        if (!regexCorreo.test(email)) {
            $("#edit-email").after("<p class='error-message' style='color: red;'>Introduce un correo válido.</p>");
            return false;
        }
        return true;
    }

    function validarTelefono() {
        let telefono = $("#edit-telefono").val().trim();
        let regexTelefono = /^\+?[0-9]{1,3}?[-. ]?\(?\d{1,4}\)?[-. ]?\d{1,4}[-. ]?\d{1,9}$/;

        limpiarError($("#edit-telefono"));

        if (!regexTelefono.test(telefono)) {
            $("#edit-telefono").after("<p class='error-message' style='color: red;'>Introduce un teléfono válido.</p>");
            return false;
        }
        return true;
    }

    function validarCodigoPostal() {
        let codigoPostal = $("#edit-codigo_postal").val().trim();
        let regexCodigoPostal = /^[0-9]{5}$/;

        limpiarError($("#edit-codigo_postal"));

        if (!regexCodigoPostal.test(codigoPostal)) {
            $("#edit-codigo_postal").after("<p class='error-message' style='color: red;'>Introduce un código postal válido (5 dígitos).</p>");
            return false;
        }
        return true;
    }

    function validarContrasenia() {
        let contrasenia = $("#edit-contrasenia").val().trim();
        let regexContrasenia = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$/;

        limpiarError($("#edit-contrasenia"));

        if (contrasenia !== "" && !regexContrasenia.test(contrasenia)) {
            $("#edit-contrasenia").after("<p class='error-message' style='color: red;'>Debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, un número y un carácter especial.</p>");
            return false;
        }
        return true;
    }

    function validarConfirmacion() {
        let contrasenia = $("#edit-contrasenia").val().trim();
        let confirmarContrasenia = $("#confirmar-contrasenia").val().trim();

        limpiarError($("#confirmar-contrasenia"));

        if (contrasenia !== "" || confirmarContrasenia !== "") {
            if (contrasenia !== confirmarContrasenia) {
                $("#confirmar-contrasenia").after("<p class='error-message' style='color: red;'>Las contraseñas no coinciden.</p>");
                return false;
            }
        }
        return true;
    }

    function verificarFormulario() {
        let esValido = true;

        esValido &= validarCampoVacio($("#edit-nombre"), "Debes ingresar tu nombre.");
        esValido &= validarCampoVacio($("#edit-apellido"), "Debes ingresar tu apellido.");
        esValido &= validarCampoVacio($("#edit-direccion"), "Debes ingresar tu dirección.");
        esValido &= validarCampoVacio($("#edit-ciudad"), "Debes ingresar tu ciudad.");
        esValido &= validarCampoVacio($("#edit-provincia"), "Debes ingresar tu provincia.");
        esValido &= validarEmail();
        esValido &= validarTelefono();
        esValido &= validarCodigoPostal();
        esValido &= validarContrasenia();
        esValido &= validarConfirmacion();

        $("button[type='submit']").prop("disabled", !esValido);
    }

    $("input").on("input", verificarFormulario);

    $("#formEditar").on("submit", function (e) {
        e.preventDefault();
        $(".error-message").remove();

        verificarFormulario();
        if ($("button[type='submit']").prop("disabled")) return;

        $.ajax({
            url: "../autenticacion/actualizar.php",
            type: "POST",
            data: $("#formEditar").serialize(),
            success: function (response) {
                if (response.trim() === "success") {
                    Swal.fire({
                        title: '¡Datos actualizados!',
                        text: 'Tus cambios se han guardado correctamente.',
                        icon: 'success',
                        iconColor: '#155724',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#155724',
                        allowOutsideClick: false,
                        width: '400px'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error en la actualización',
                        text: response,
                        icon: 'error',
                        iconColor: '#de301d',
                        confirmButtonText: 'Intentar de nuevo',
                        confirmButtonColor: '#155724',
                        allowOutsideClick: false,
                        width: '400px'
                    });
                }
            }
        });
    });
    $("#eliminar-cuenta").off("click").on("click", function () {
        Swal.fire({
            title: '¿Seguro que quieres eliminar tu cuenta?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            iconColor: '#de6d1d',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: '#de301d',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,
            width: '400px'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../autenticacion/eliminar.php",
                    type: "POST",
                    success: function (response) {
                        if (response.trim() === "success") {
                            Swal.fire({
                                title: 'Cuenta eliminada',
                                text: 'Tu cuenta ha sido eliminada correctamente.',
                                icon: 'success',
                                iconColor: '#155724',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#155724',
                                allowOutsideClick: false,
                                width: '400px'
                            }).then(() => {
                                window.location.href = "../layout/home.php";
                            });
                        } else {
                            Swal.fire({
                                title: 'Error al eliminar',
                                text: response,
                                icon: 'error',
                                iconColor: '#de301d',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#155724',
                                allowOutsideClick: false,
                                width: '400px'
                            });
                        }
                    }
                });
            }
        });
    });
});
