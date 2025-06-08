<link rel="stylesheet" href="/Harvey-s/elementos/css/css_div_empleados.css">
<div class='divEmpleados'>
        <label for="Cerrar" class="back-button_empleados" id="Cerrar">
            <i class="fas fa-times"></i>
        </label>
        <form action="../autenticacion/login_empleados.php" method="post" id="formEmpleados">
            <h2>Entrar a la zona de empleados</h2>
            <label for="user_empleado">Usuario: </label>
            <input type="text" name="usuario" id="usuario" placeholder="ID Empleado">
            <label for="contrasena">Contraseña: </label>
            <input type="password" name="clave" id="clave" placeholder="Contraseña">
            <div id="login-response-empleados"></div>
            <button type="submit">Entrar</button>
            <p>
                <a href="#" onclick="mostrarAlertaEmpleados()">¿Olvidaste tu contraseña?</a>
            </p>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            function mostrarAlertaEmpleados() {
                Swal.fire({
                    title: 'Recuperación de contraseña',
                    text: 'Por motivos de seguridad, contacta con RRHH para obtener tu contraseña.',
                    icon: 'info',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#155724',
                    allowOutsideClick: false,
                    width: '400px',        
                    customClass: {
                        popup: 'swal-personalizado'
                    }
                }).then(() => {
                    $('.divEmpleados').animate({left: '-550px'}, 400, function(){
                        $("#formEmpleados")[0].reset();
                        $("#login-response-empleados").empty();
                    });
                });
            }
            </script>
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
                        if (response === "success") { 
                            window.open('/Harvey-s/empleados/pagina_empleados.php', '_blank'); 
                            
                            $('.divEmpleados').animate({left: '-550px'}, 400, function(){
                                $("#formEmpleados")[0].reset();
                                $("#login-response-empleados").empty();
                            });
                        } else {
                            $("#login-response-empleados").html(response);
                        }
                    },
                    error: function() {
                        $("#login-response-empleados").html("<p style='color: red;'><strong>Ocurrió un error inesperado.</strong></p>");
                    }
                });
            });

        });
    </script>