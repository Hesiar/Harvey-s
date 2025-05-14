<?php
session_start();

// Verificar si el empleado ha iniciado sesión
if (!isset($_SESSION['empleado'])) {
    header("Location: /Harvey-s/layput/maqueta.php");
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

// Obtener datos del empleado
$stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['empleado']);
$stmt->execute();
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <title>Zona de Empleados - Harvey's</title>
    <link rel="stylesheet" href="/Harvey-s/estilos.css">
</head>
<body>
    <div class="contenedor-empleados">
        <h1>Bienvenido, <?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']); ?> 👋</h1>
        <p>Esta es la zona exclusiva para empleados.</p>

        <ul>
            <li><a href="/Harvey-s/historial_ventas.php">Historial de Ventas</a></li>
            <li><a href="/Harvey-s/configuracion_perfil.php">Configuración de Perfil</a></li>
        </ul>

        <button type="button" class="btn-salir" onclick="cerrarSesion();">Cerrar sesión</button>

        <script>
            function cerrarSesion() {
                fetch('/Harvey-s/autenticacion/logout_empleados.php', { method: 'POST' })
                    .then(() => window.close());
            }
        </script>
    </div>
</body>
</html>
