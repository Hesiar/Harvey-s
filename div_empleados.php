<div class='divEmpleados'>
        <label for="Cerrar" class="back-button_empleados" id="Cerrar">
            <i class="fas fa-times"></i>
        </label>
        <form action="login_empleados.php" method="post" id="formEmpleados">
        <h2>Entrar a la zona de empleados</h2>
        <label for="user_empleado">Usuario: </label>
        <input type="text" name="usuario" placeholder="ID Empleado">
        <label for="contrasena">Contraseña: </label>
        <input type="password" name="clave" placeholder="Contraseña">
        <div id="login-response-empleados"></div>
        <button type="submit">Entrar</button>
        <p><a href="/Harvey-s/recuperar_contrasena_empleados.php">¿Olvidaste tu contraseña?</a></p>
    </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script>
       $(document).ready(function(){
        $('.back-button_empleados').on('click', function(){
            $('.divEmpleados').animate({left: '-550px'}, 400, function(){
                $("#formEmpleados")[0].reset();
                $("#login-response-empleados").empty();
            });
        });
        
        $("#formEmpleados").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr("action"),  
                type: $(this).attr("method"), 
                data: $(this).serialize(),      
                success: function(response) {
                    $("#login-response-empleados").html(response);
                },
                error: function() {
                    $("#login-response-empleados").html("Ocurrió un error. Inténtalo de nuevo.");
                }
            });
        });
    });
    </script>