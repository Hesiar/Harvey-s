<div class='divEmpleados'>
        <label for="Cerrar" class="back-button_empleados" id="Cerrar">
            <i class="fas fa-times"></i>
        </label>
        <form action="#" method="post">
        <h2>Entrar a la zona de empleados</h2>
        <input type="text" name="user_empleado" placeholder="ID Empleado" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
        <p><a href="/Harvey-s/recuperar_contrasena_empleados.php">¿Olvidaste tu contraseña?</a></p>
    </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.back-button_empleados').on('click', function(){
                $('.divEmpleados').animate({left: '-400px'}, 400);
            });
        });
    </script>