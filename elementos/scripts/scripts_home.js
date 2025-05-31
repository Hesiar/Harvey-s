$(document).ready(function(){
    let loggedIn = false;

    $.ajax({
        url: '/Harvey-s/autenticacion/login.php',
        method: 'POST',
        data: { checkAuth: true },
        success: function(response) {
            loggedIn = response === "true";
        }
    });

    $(".campo-busqueda").val("");
    $(".campo-busqueda").on("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault(); 
        }
    });

    $('.btn-login').on('click', function(e){
        e.preventDefault();
        
        if (loggedIn) {
            window.location.href = '/Harvey-s/secciones/cuenta.php';
        } else {
            $('.divLogin').animate({right: '1rem'}, 400);
        }
    });

    $('.btn-carrito').on('click', function(e){
        e.preventDefault();
        $('.divCarrito').animate({right: '1rem'}, 400);
    });

    $('.secciones').on('click', function(e){
        $('.divSecciones').animate({left: '1rem'}, 400);
    });

    $('.btn-empleados').on('click', function(e){
        e.preventDefault();
        $('.divEmpleados').animate({left: '1rem'}, 400);
    });
});
