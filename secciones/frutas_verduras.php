<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../elementos/css/css_clientes.css">
    <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <title>Harvey's | Frutas y verduras</title>
</head>
<body>
    <?php 
        include '../layout/header.php';
        include '../divs/div_login.php';
        include '../divs/div_registro.php';
        include '../divs/div_carrito.php';
        include '../divs/div_secciones.php';
        include '../divs/div_empleados.php';
    ?>
    <div class="productos-container">
        <script src="\Harvey-s\elementos\scripts\script_frutas_verduras.js"></script>
    </div>
    <?php
        include '../layout/footer.php';
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="\Harvey-s\elementos\scripts\scripts_home.js"></script>
    <script src="../elementos/scripts/switch_secciones.js"></script>

</body>
</html>
