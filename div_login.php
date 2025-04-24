<div class='divLogin'>
    <label for="Cerrar" class="back-button" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <form action="#" method="post">
        <h2>Iniciar sesión</h2>
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Iniciar sesión</button>
        <p>¿No tienes cuenta? <a href="/Harvey-s/registro.php">Regístrate aquí</a></p>
        <p><a href="/Harvey-s/recuperar_contrasena.php">¿Olvidaste tu contraseña?</a></p>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(document).ready(function(){
        $('.back-button').on('click', function(){
            $('.divLogin').animate({right: '-320px'}, 400);
        });
    });
</script>
