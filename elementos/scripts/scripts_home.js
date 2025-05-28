$(document).ready(function(){
    $(".campo-busqueda").val("");
    $(".campo-busqueda").on("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault(); 
        }
    });
    
    $('.btn-login').on('click', function(e){
        e.preventDefault();
        $('.divLogin').animate({right: '1rem'}, 400);
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