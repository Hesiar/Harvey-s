<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../elementos/css/css_clientes.css">
    <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>Harvey's</title>
    <style>
        .body_div_home {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .container_div_home {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            margin-top: -10px;
        }
        .text-box_div_home {
            background: #005B1C;
            padding: 20px;
            border-radius: 10px;
            margin: 10px;
        }
        .text-box_div_home h1 {
            margin: 0;
            font-size: 2.5rem;
            color: white;
            margin-bottom: 2px;
        }
        .text-box_div_home h5 {
            color: white;
        }
        .text-box_div_home p {
            margin: 0;
            font-size: 1.5rem;
            color: white;
        }
        @media (max-width: 480px) {
            .text-box_div_home {
                padding: 10px;
                margin: 5px;
            }
            .text-box_div_home h1 {
                font-size: 1.5rem;
            }
            .text-box_div_home p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="fondo_home"></div>
    <?php 
        include 'header.php';
        include '../divs/div_login.php';
        include '../divs/div_registro.php';
        include '../divs/div_carrito.php';
        include '../divs/div_secciones.php';
        include '../divs/div_home.php';
        include '../divs/div_empleados.php';
        include 'footer.php';
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/Harvey-s/elementos/scripts/scripts_home.js"></script>
    <script src="/Harvey-s/elementos/busqueda/script_buscar_categoria.js"></script>

</body>
</html>
