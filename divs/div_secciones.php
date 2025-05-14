<div class='divSecciones'>
    <label for="Cerrar" class="back-button_secciones" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <h1><a href="../secciones/bebidas.php">Bebidas</a></h1>
    <h1>Carne y embutidps</h1>
    <h1>Conservas</h1>
    <h1>Desayunos, dulces, frutos secos</h1>
    <h1>Frutas y verduras</h1>
    <h1>Lácteos</h1>
    <h1>Panadería</h1>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(document).ready(function(){
        if (window.location.href.includes("bebidas.php")) {
            $('h1:has(a[href="../secciones/bebidas.php"])').hide();
        }
        $('.back-button_secciones').on('click', function(){
            $('.divSecciones').animate({left: '-550px'}, 400);
        });
    });
</script>

