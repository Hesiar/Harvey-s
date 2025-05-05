<div class='divLogin'>
    <label for="Cerrar" class="back-button" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <form action="login.php" method="post" id="formLogin">
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
        <p><a href="/Harvey-s/recuperar_contrasena.php">¿Olvidaste tu contraseña?</a></p>
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
                            window.location.href = "maqueta.php";
                        });
                    } else {
                        $("#login-response").html(response);
                    }
                },
                error: function() {
                    $("#login-response").html("Ocurrió un error. Inténtalo de nuevo.");
                }
            });
        });

        $('#btn-registro').on('click', function(e) {
            e.preventDefault();
            $('.divRegistro').animate({ right: '1rem' }, 400);
        });
    });
</script>
