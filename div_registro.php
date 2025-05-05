<div class="divRegistro">
    <label for="Cerrar" class="back-button-registro" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <form action="registro.php" method="post" id="formRegistro">
        <h2>Registro de Cliente</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" placeholder="Nombre">
        
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" placeholder="Apellido">
        
        <label for="email">Correo:</label>
        <input type="email" name="email" placeholder="Correo">
        
        <label for="contrasenia">Contraseña:</label>
        <input type="password" name="contrasenia" placeholder="Contraseña">
        
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
        });
    });

    $("#formRegistro").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr("action"),
            type: $(this).attr("method"),
            data: $(this).serialize(),
            success: function(response){
                $("#registro-response").html(response);
                if (response.includes("Este correo ya está registrado")) {
                    $("#formRegistro")[0].reset();
                }
            },
            error: function(){
                $("#registro-response").html("Ocurrió un error. Inténtalo de nuevo.");
                $("#formRegistro")[0].reset();
            }
        });
    });
});
</script>
