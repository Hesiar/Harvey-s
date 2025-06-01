<div class="divRegistro">
    <label for="Cerrar" class="back-button-registro" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <form action="../correos/registro.php" method="post" id="formRegistro">
        <h2>Registro de Cliente</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" placeholder="Nombre">
        
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" placeholder="Apellido">
        
        <label for="email">Correo:</label>
        <input type="email" name="email" placeholder="Correo">
        
        <label for="contrasenia">Contraseña:</label>
        <input type="password" id="contrasenia" name="contrasenia" placeholder="Contraseña" autocomplete="off" >

        <label for="confirmar_contrasenia">Confirmar Contraseña:</label>
        <input type="password" id="confirmar_contrasenia" name="confirmar_contrasenia" placeholder="Repite tu contraseña" autocomplete="off">

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" placeholder="Teléfono">

        <label for="direccion">Dirección: </label>
        <input type="text" name="direccion" placeholder="Dirección">

        <label for="ciudad">Ciudad: </label>
        <input type="text" name="ciudad" placeholder="Ciudad">

        <label for="provincia">Provincia: </label>
        <input type="text" name="provincia" placeholder="Provincia">

        <label for="codigo_postal">Código postal: </label>
        <input type="text" name="codigo_postal" placeholder="Código postal">

        <button type="submit">Registrarme</button>
        <div id="registro-response"></div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(document).ready(function(){
        $('.back-button-registro').on('click', function(){
            $('.divRegistro').animate({ right: '-320px' }, 400, function(){
                $("#formRegistro")[0].reset();
                $("#registro-response").empty();
                $("#formRegistro input").each(function() {
                    limpiarError($(this));
                });
                $(".error-message").remove();
                 $("button[type='submit']").prop("disabled", false);
            });
        });

        function limpiarError(input) {
            input.next(".error-message").remove();
        }

        function validarCampoVacio(input, mensajeError) {
            limpiarError(input);
            if (input.val().trim() === "") {
                input.after("<p class='error-message' style='color: red; font-weight: bold;'>" + mensajeError + "</p>");
                return false;
            }
            return true;
        }

        function validarContrasenia() {
            let contrasenia = $("#contrasenia").val().trim();
            let regexContrasenia = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$/;

            limpiarError($("#contrasenia"));

            if (!regexContrasenia.test(contrasenia)) {
                $("#contrasenia").after("<p class='error-message' style='color: red; font-weight: bold;'>Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.</p>");
                return false;
            }
            return true;
        }

        function validarConfirmacion() {
            let contrasenia = $("#contrasenia").val().trim();
            let confirmarContrasenia = $("#confirmar_contrasenia").val().trim();

            limpiarError($("#confirmar_contrasenia"));

            if (contrasenia !== confirmarContrasenia) {
                $("#confirmar_contrasenia").after("<p class='error-message' style='color: red; font-weight: bold;'>Las contraseñas no coinciden.</p>");
                return false;
            }
            return true;
        }

        function validarEmail() {
            let email = $("input[name='email']").val().trim();
            let regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            limpiarError($("input[name='email']"));

            if (!regexCorreo.test(email)) {
                $("input[name='email']").after("<p class='error-message' style='color: red; font-weight: bold;'>Introduce un correo válido.</p>");
                return false;
            }
            return true;
        }

        function validarTelefono() {
            let telefono = $("input[name='telefono']").val().trim();
            let regexTelefono = /^\+?[0-9]{1,3}?[-. ]?\(?\d{1,4}\)?[-. ]?\d{1,4}[-. ]?\d{1,9}$/;

            limpiarError($("input[name='telefono']"));

            if (!regexTelefono.test(telefono)) {
                $("input[name='telefono']").after("<p class='error-message' style='color: red; font-weight: bold;'>Introduce un número de teléfono válido.</p>");
                return false;
            }
            return true;
        }

        function validarCodigoPostal() {
            let codigoPostal = $("input[name='codigo_postal']").val().trim();
            let regexCodigoPostal = /^[0-9]{5}$/;

            limpiarError($("input[name='codigo_postal']"));

            if (!regexCodigoPostal.test(codigoPostal)) {
                $("input[name='codigo_postal']").after("<p class='error-message' style='color: red; font-weight: bold;'>Introduce un código postal válido (5 dígitos).</p>");
                return false;
            }
            return true;
        }

        function validarFormulario() {
            let esValido = true;

            esValido &= validarCampoVacio($("input[name='nombre']"), "Debes ingresar tu nombre.");
            esValido &= validarCampoVacio($("input[name='apellido']"), "Debes ingresar tu apellido.");
            esValido &= validarCampoVacio($("input[name='direccion']"), "Debes ingresar tu dirección.");
            esValido &= validarCampoVacio($("input[name='ciudad']"), "Debes ingresar tu ciudad.");
            esValido &= validarCampoVacio($("input[name='provincia']"), "Debes ingresar tu provincia.");

            esValido &= validarContrasenia();
            esValido &= validarConfirmacion();
            esValido &= validarEmail();
            esValido &= validarTelefono();
            esValido &= validarCodigoPostal();

            return esValido;
        }

        // Activar/desactivar el botón dependiendo de la validación
        $("#formRegistro input").on("input", function(){
            let esValido = validarFormulario();
            $("button[type='submit']").prop("disabled", !esValido);
        });

        $("#formRegistro").on("submit", function(e){
            e.preventDefault();
            $(".error-message").remove();

            if (!validarFormulario()) {
                return;
            }

            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                success: function(response){
                    alert("Registro completado correctamente. Se ha enviado un correo a la dirección proporcionada. No olvides revisar la carpeta de spam.");
                    $("#formRegistro")[0].reset();
                    $('.divRegistro').animate({ right: '-320px' }, 400, function(){
                        $("#registro-response").empty();
                    });
                    $('.divLogin').animate({right: '-320px'}, 400, function(){
                        $("#formLogin")[0].reset();
                        $("#login-response").empty();
                    });
                },
                error: function(){
                    $("#registro-response").html("<p style='color: red; font-weight: bold;'>Ocurrió un error al procesar tu registro.</p>");
                }
            });
        });
    });


</script>