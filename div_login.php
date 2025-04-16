<div class='divLogin'>
    <label for="Cerrar" class="back-button" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <h2>Login div</h2>
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
