<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <title>Harvey's | Verificación de cuenta</title>
    <link rel="stylesheet" href="/Harvey-s/elementos/css/estilos.css">
</head>
<body>

    <?php
        session_start();

        $host = 'localhost';
        $dbname = 'harveys_DB';
        $dbuser = 'root';
        $dbpass = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
        }

        if (isset($_GET['token'])) {
            $token = $_GET['token'];

            $stmt = $pdo->prepare("SELECT id, nombre, email FROM clientes WHERE verificacion_token = :token");
            $stmt->execute([':token' => $token]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $stmt = $pdo->prepare("UPDATE clientes SET verificado = TRUE, verificacion_token = NULL WHERE id = :id");
                $stmt->execute([':id' => $usuario['id']]);

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['email'] = $usuario['email'];

                echo "<div class='verificacion-container'>";
                echo "<h2>¡Hola, " . htmlspecialchars($usuario['nombre']) . "!</h2>";
                echo "<p>Tu cuenta ha sido verificada correctamente. Ahora puedes acceder a tu perfil.</p>";
                echo "<a href='/Harvey-s/secciones/cuenta.php' style='display: inline-block; padding: 10px 15px; background-color: #005B1C; color: white; text-decoration: none; border-radius: 5px;'>Ir a mi cuenta</a>";
                echo "</div>";
                echo "<script>setTimeout(function() {window.location.href = '/Harvey-s/secciones/cuenta.php';}, 5000);</script>";

            } else {
                echo "<div class='verificacion-container'>";
                echo "<h2>Error</h2><p>El token de verificación es inválido o ya ha sido usado.</p>";
                echo "</div>";            
            }
        }
    ?>
</body>