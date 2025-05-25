$(document).ready(function(){
    $('.btn-login').on('click', function(e){
        e.preventDefault();
        window.location.href = '../secciones/cuenta.php';
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