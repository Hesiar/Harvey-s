<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <title>Harvey's | Recuperación de contraseña</title>
    <link rel="stylesheet" href="/Harvey-s/elementos/css/estilos.css">
</head>
<body>
    <div class="divRecuperacion" id="divRecuperacion">
        <?php

        $pdo = new PDO("mysql:host=localhost;dbname=harveys_DB;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!isset($_GET['token']) || empty($_GET['token'])) {
            echo "<p>Acceso denegado: token no válido.</p>";
        } else {
            $token = $_GET['token'];

            $stmt = $pdo->prepare("SELECT id, password_reset_expires FROM clientes WHERE password_reset_token = :token");
            $stmt->execute([':token' => $token]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                echo "<p>Token inválido o no encontrado.</p>";
            } else {
                if (strtotime($usuario['password_reset_expires']) < time()) {
                    echo "<p>El token ha expirado. Por favor, solicita un nuevo restablecimiento de contraseña.</p>";
                } else {
                    ?>
                    <form action="actualizar_contrasenia.php" method="post" id="formRecuperacion">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <h2>Restablece tu contraseña</h2>
                        <label for="nueva_contrasenia">Nueva contraseña:</label>
                        <input type="password" name="nueva_contrasenia" placeholder="Nueva contraseña" required>
                        <br>
                        <label for="confirmar_contrasenia">Confirmar contraseña:</label>
                        <input type="password" name="confirmar_contrasenia" placeholder="Confirma la contraseña" required>
                        <br>
                        <button type="submit">Actualizar contraseña</button>
                    </form>
                    <?php
                }
            }
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function(){
        $("#formRecuperacion").on('submit', function(e) {
            e.preventDefault(); 
            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                dataType: "json", 
                success: function(response) {
                    if (response.status === "error") {
                        alert(response.mensaje);
                    } else {
                        alert(response.mensaje);
                    }
                },
                error: function() {
                    alert("Ocurrió un error inesperado en el servidor.");
                }
            });
        });
    });
    </script>
</body>
</html>
