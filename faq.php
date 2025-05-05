<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="elementos/css_clientes.css">
    <link rel="icon" href="elementos/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <title>Harvey's</title>
</head>
<body>
    <?php 
        include 'header.php';
        include 'div_login.php';
        //include 'div_recuperacion.php';
        include 'div_registro.php';
        include 'div_carrito.php';
        include 'div_faq.php';
        include 'div_secciones.php';
        include 'div_empleados.php';
        include 'footer.php';
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function(){
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
    </script>

</body>
</html>
