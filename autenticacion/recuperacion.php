<?php
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
        $correo = trim($_POST['correo']);

        if (empty($correo)) {
            echo "Por favor, ingresa tu correo electrónico.";
            exit;
        }
        
        $stmt = $pdo->prepare("SELECT id, nombre, email FROM clientes WHERE email = :correo");
        $stmt->execute([':correo' => $correo]);
        
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cliente) {
            $token = bin2hex(random_bytes(16));
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $stmt = $pdo->prepare(
                "UPDATE clientes SET password_reset_token = :token, password_reset_expires = :expires WHERE id = :id"
            );
            $stmt->execute([
                ':token'   => $token,
                ':expires' => $expires,
                ':id'      => $cliente['id']
            ]);

            $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
                . '://' . $_SERVER['HTTP_HOST'];
            $urlRecuperacion = $baseUrl . '/Harvey-s/autenticacion/pagina_recuperacion.php?token=' . $token;
        
            if (enviarCorreoRecuperacion($cliente['email'], $cliente['nombre'], $urlRecuperacion)) {
                echo "success";
                exit;
            } else {
                echo "En caso de tener una cuenta con nosotros, recibirá un correo. No olvide revisar la carpeta de spam.";
                exit;
            }
        } else {
            echo "En caso de tener una cuenta con nosotros, recibirá un correo. No olvide revisar la carpeta de spam.";
            exit;
        }
    }
?>
