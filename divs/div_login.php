<div class='divLogin'>
    <label for="Cerrar" class="back-button" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <form action="../autenticacion/login.php" method="post" id="formLogin">
        <h2>Iniciar sesión</h2>
        <label for="correo">Correo: </label>
        <input type="text" name="correo" placeholder="Correo">
        <label for="contrasenia">Contraseña: </label>
        <input type="password" name="contrasenia" placeholder="Contraseña">
        <label class="recordar-sesion">
            <input type="checkbox" name="recordar_sesion"> Recordar sesión
        </label>
        <button type="submit">Iniciar sesión</button>
        <div id="login-response"></div>
        <p>¿No tienes cuenta? <button type="submit" id="btn-registro">Regístrate aquí</button></p>
        <p>¿Olvidaste tu contraseña? <button type="submit" id="btn-recuperacion">Recuperar contraseña</button></p>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(document).ready(function(){
        $('.back-button').on('click', function(){
            $('.divLogin').animate({right: '-320px'}, 400, function(){
                if ($("#login-response .login-success").length === 0) {
                    $("#formLogin")[0].reset();
                    $("#login-response").empty();
                }
            });
        });

        $("#formLogin").on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.trim() === "success") {
                        $('.divLogin').animate({ right: '-320px' }, 400, function(){
                            window.location.href = "../secciones/cuenta.php";
                        });
                    } else {
                        Swal.fire({
                            title: 'Error de inicio de sesión',
                            text: response,
                            icon: 'error',
                            iconColor: '#de301d',
                            confirmButtonText: 'Intentar de nuevo',
                            confirmButtonColor: '#155724',
                            allowOutsideClick: false,
                            width: '400px'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error en el servidor',
                        text: 'Ocurrió un problema inesperado. Inténtalo más tarde.',
                        icon: 'error',
                        iconColor: '#de301d',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#155724',
                        allowOutsideClick: false,
                        width: '400px'
                    });
                }
            });
        });

        $('#btn-registro').on('click', function(e) {
            e.preventDefault();
            $('.divRegistro').animate({ right: '1rem' }, 400);
        });

        $('#btn-recuperacion').on('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Recuperación de contraseña',
                input: 'email',
                inputPlaceholder: 'Introduce tu correo',
                showCancelButton: true,
                confirmButtonText: 'Enviar correo',
                confirmButtonColor: '#155724',
                cancelButtonText: 'Cancelar',
                cancelButtonColor: '#de301d',
                allowOutsideClick: false,
                width: '400px',
                inputValidator: (value) => {
                    if (!value.trim()) {
                        return 'Debes ingresar un correo';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let correo = result.value;
                    
                    $.ajax({
                        url: "../correos/correo_recuperacion.php",
                        type: "POST",
                        data: { correo: correo },
                        success: function(response) {
                            Swal.fire({
                                title: 'Petición recibida',
                                text: 'Si tienes una cuenta con nosotros, recibirás un email. No olvides revisar la carpeta de spam.',
                                icon: 'success',
                                iconColor: '#155724',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#155724',
                                allowOutsideClick: false,
                                width: '400px'
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error en el servidor',
                                text: 'Ocurrió un error inesperado. Inténtalo más tarde.',
                                icon: 'error',
                                iconColor: '#de301d',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#155724',
                                allowOutsideClick: false,
                                width: '400px'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
