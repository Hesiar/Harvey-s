<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Harvey-s/elementos/css/css_clientes.css">
    <link rel="icon" href="/Harvey-s/elementos/pics/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <title>Harvey's | Carnes y embutidos</title>
</head>
<body>
    <?php 
        include $_SERVER['DOCUMENT_ROOT'] . '/Harvey-s/layout/header_logged.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/Harvey-s/divs/div_carrito.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/Harvey-s/divs/div_secciones_logged.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/Harvey-s/divs/div_empleados.php';
    ?>
    <div class="productos-container">
        <script src="/Harvey-s/elementos/scripts/logged/script_carnes_embutidos.js"></script>
    </div>
    <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/Harvey-s/layout/footer.php';
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/Harvey-s/elementos/scripts/scripts_home_logged.js"></script>
    <script src="/Harvey-s/elementos/scripts/switch_secciones.js"></script>
    <script src="/Harvey-s/elementos/busqueda/script_buscar_categoria_logged.js"></script>

</body>
</html>
