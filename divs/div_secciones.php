<div class='divSecciones'>
    <label for="Cerrar" class="back-button_secciones" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <h1><a href="../secciones/bebidas.php">Bebidas</a></h1>
    <h1><a href="../secciones/carnes.php">Carne y embutidos</h1>
    <h1><a href="../secciones/conservas.php">Conservas</h1>
    <h1><a href="../secciones/desayunos.php">Desayunos, dulces, frutos secos</h1>
    <h1><a href="../secciones/frutas_verduras.php">Frutas y verduras</h1>
    <h1><a href="../secciones/lacteos.php">Lácteos</h1>
    <h1><a href="../secciones/panaderia.php">Panadería</h1>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="\Harvey-s\elementos\scripts\switch_secciones.js"></script>
<script>
    $(document).ready(function(){
        $('.back-button_secciones').on('click', function(){
            $('.divSecciones').animate({left: '-550px'}, 400);
        });
    });
</script>

