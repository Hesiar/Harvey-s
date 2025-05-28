<?php
    session_start();

    $host   = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../layout/home.php");
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT nombre, apellido, email, telefono, direccion, ciudad, provincia, codigo_postal, empleado_id FROM clientes WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['usuario_id']]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Cuenta</title>
    <link rel="stylesheet" href="../elementos/css/css_clientes.css"> <!-- Ajusta la ruta -->
    <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <title>Harvey's | Cuenta</title>
</head>
<body>
    <?php 
        include '../layout/header_logged.php';
        include '../divs/div_carrito.php';
       include '../divs/div_secciones_logged.php';
        include '../divs/div_empleados.php';
    ?>

    <div class="perfil">
        <h2>Mis Datos</h2>
        <p><strong>Nombre:</strong> <span id="nombre"><?= htmlspecialchars($usuario['nombre']) ?></span></p>
        <p><strong>Apellido:</strong> <span id="apellido"><?= htmlspecialchars($usuario['apellido']) ?></span></p>
        <p><strong>Email:</strong> <span id="email"><?= htmlspecialchars($usuario['email']) ?></span></p>
        <p><strong>Teléfono:</strong> <span id="telefono"><?= htmlspecialchars($usuario['telefono']) ?></span></p>
        <p><strong>Dirección:</strong> <span id="direccion"><?= htmlspecialchars($usuario['direccion']) ?></span></p>
        <p><strong>Ciudad:</strong> <span id="ciudad"><?= htmlspecialchars($usuario['ciudad']) ?></span></p>
        <p><strong>Provincia:</strong> <span id="provincia"><?= htmlspecialchars($usuario['provincia']) ?></span></p>
        <p><strong>Código Postal:</strong> <span id="codigo_postal"><?= htmlspecialchars($usuario['codigo_postal']) ?></span></p>
        <button id="editar">Editar</button>
        <button id="cerrar-sesion">Cerrar sesión</button>
    </div>

    <!-- Formulario de edición -->
    <div class="editar-perfil" style="display: none;">
        <h2>Editar Datos</h2>
        <form id="formEditar">
            <label>Nombre:</label>
            <input type="text" name="nombre" id="edit-nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>">

            <label>Apellido:</label>
            <input type="text" name="apellido" id="edit-apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>">

            <label>Contraseña nueva:</label>
            <input type="password" name="contrasenia" id="edit-contrasenia">
            
            <label>Repetir contraseña:</label>
            <input type="password" name="confirmar-contrasenia" id="confirmar-contrasenia">

            <label>Email:</label>
            <input type="email" name="email" id="edit-email" value="<?= htmlspecialchars($usuario['email']) ?>">

            <label>Teléfono:</label>
            <input type="text" name="telefono" id="edit-telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">

            <label>Dirección:</label>
            <input type="text" name="direccion" id="edit-direccion" value="<?= htmlspecialchars($usuario['direccion']) ?>">

            <label>Ciudad:</label>
            <input type="text" name="ciudad" id="edit-ciudad" value="<?= htmlspecialchars($usuario['ciudad']) ?>">

            <label>Provincia:</label>
            <input type="text" name="provincia" id="edit-provincia" value="<?= htmlspecialchars($usuario['provincia']) ?>">

            <label>Código Postal:</label>
            <input type="text" name="codigo_postal" id="edit-codigo_postal" value="<?= htmlspecialchars($usuario['codigo_postal']) ?>">

            <button type="submit">Guardar cambios</button>
            <button type="button" id="cancelar-edicion">Cancelar</button>

            <button type="button" id="eliminar-cuenta">Eliminar cuenta</button>
        </form>
    </div>

    <?php
        include '../layout/footer.php';
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/Harvey-s/elementos/scripts/scripts_home.js"></script>
    <script src="/Harvey-s/elementos/scripts/script_cuenta_usuario.js"></script>
    <script src="/Harvey-s/elementos/busqueda/script_buscar_categoria_logged.js"></script>

</body>
</html>
