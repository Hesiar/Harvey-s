<?php
    session_start();

    if (!isset($_SESSION['empleado'])) {
        header("Location: /Harvey-s/layout/home.php");
        die();
    }

    $host   = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }

    $stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['empleado']);
    $stmt->execute();
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    $pagina = $_GET['seccion'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <title>Harvey's | Zona de Empleados</title>
    <link rel="stylesheet" href="/Harvey-s/elementos/css/css_empleados.css">
</head>
<body>
    <div class="fondo-empleados"></div>
    <div class="contenedor-empleados">
        <a href="pagina_empleados.php">
            <h1>Zona de empleados</h1>
        </a>
        <h2>Bienvenido, <?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']); ?></h2>
        <p>Esta es la zona exclusiva para empleados.</p>

        <ul>
            <li><a href="?seccion=configuracion_perfil">Configuración de Perfil</a></li>
            <?php if ($empleado['puesto_id'] == 1): ?>
                <li><a href="?seccion=historial_ventas">Historial de Ventas</a></li>
            <?php endif; ?>
        </ul>

        <div id="secciones_empleados">
            <?php
            $rutas_validas = [
                'configuracion_perfil' => __DIR__ . '/../empleados/configuracion_perfil.php',
                'historial_ventas' => __DIR__ . '/../empleados/historial_ventas.php',
            ];

            if (array_key_exists($pagina, $rutas_validas)) {
                include($rutas_validas[$pagina]);
            } else {
                echo '<img src="/Harvey-s/elementos/pics/Harveys_logo.png" alt="Harvey\'s Logo" class="logo-harvey">';
            }
            ?>
        </div>

        <button type="button" class="btn-salir" onclick="cerrarSesion();">Cerrar sesión</button>

        <script>
            function cerrarSesion() {
                fetch('/Harvey-s/autenticacion/logout_empleados.php', { method: 'POST' })
                    .then(() => {
                        window.close();
                    });
            }
        </script>
    </div>
</body>
</html>
