<div class='divSecciones'>
    <label for="Cerrar" class="back-button_secciones" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <h1>Alimentación</h1>
    <h1>Droguería</h1>
    <h1>Parafarmacia</h1>
    <h1>Hogar</h1>
    <h1>Ferretería</h1>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(document).ready(function(){
        $('.back-button_secciones').on('click', function(){
            $('.divSecciones').animate({left: '-320px'}, 400);
        });
    });
</script>

