<div class='divCarrito'>
    <label for="Cerrar" class="back-button-carrito" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <form action="#" method="post">
        <h2>Carrito</h2>
        <h3>hola</h3>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(document).ready(function(){
        $('.back-button-carrito').on('click', function(){
            $('.divCarrito').animate({right: '-320px'}, 400);
        });
    });
</script>
