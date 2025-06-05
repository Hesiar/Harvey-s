<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use Dotenv\Dotenv;

    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../elementos/credenciales', 'credenciales_gmail_Harveys.env');
    $dotenv->load();

    function enviarCorreoRecuperacion($destinatario, $nombre, $urlRecuperacion) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->setLanguage('es', __DIR__ . '/../vendor/phpmailer/phpmailer/language/');

        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USERNAME'];
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
            $mail->addAddress($destinatario, $nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña - Harvey\'s';
            $mail->addEmbeddedImage(__DIR__ . '/../elementos/pics/Harveys_logo.png', 'logo_harveys');
            $mail->Body = '
                <html lang="es">
                    <head>
                        <meta charset="utf-8">
                        <title>Recuperación de Contraseña</title>
                    </head>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                        <div style="background-color: #005B1C; padding: 10px;">
                            <h3 style="margin: 0; color:rgb(255, 255, 255);">Recuperación de Contraseña</h3>
                        </div>
                        <p><strong>Hola ' . htmlspecialchars($nombre) . ',</strong></p>
                        <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
                        <p>Para proceder, haz clic en el siguiente enlace:</p>
                        <p>
                            <a href="' . $urlRecuperacion . '" style="color:rgb(17, 11, 188); padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                                Restablecer Contraseña
                            </a>
                        </p>
                        <p>Dispones de 15 minutos para completar este proceso.</p>
                        <p>Si no solicitaste el restablecimiento, ignora este correo.</p>
                        <p>Saludos,<br>El equipo de Harvey\'s</p>
                        <p>
                            <img src="cid:logo_harveys" alt="Logo de Harvey\'s" style="width: 40px; height: 40px;">
                        </p>
                    </body>
                </html>
            ';
            $mail->AltBody = 'Hola ' . $nombre . ',
            
            Hemos recibido una solicitud para restablecer tu contraseña. Para hacerlo, visita el siguiente enlace: ' . $urlRecuperacion . '

            Si no solicitaste este cambio, ignora este correo.

            Saludos,
            El equipo de Harvey\'s';

            $mail->send();
                return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
        $correoInput = trim($_POST['correo']);
        
        if (empty($correoInput)) {
            echo "Por favor, ingresa un correo válido.";
            exit;
        }
    
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=harveys_DB;charset=utf8", 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error en la conexión: " . $e->getMessage();
            exit;
        }
    
        $stmt = $pdo->prepare("SELECT id, nombre, email FROM clientes WHERE email = :email");
        $stmt->execute([':email' => $correoInput]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$usuario) {
            echo "No se encontró ningún usuario con ese correo.";
            exit;
        }
    
        $token = bin2hex(random_bytes(16));
        $expiration = date("Y-m-d H:i:s", strtotime("+15 minutes"));
    
        $stmt = $pdo->prepare("UPDATE clientes SET password_reset_token = :token, password_reset_expires = :expires WHERE id = :id");
        $stmt->execute([
            ':token'   => $token,
            ':expires' => $expiration,
            ':id'      => $usuario['id']
        ]);
    
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
            . '://' . $_SERVER['HTTP_HOST'];
        $urlRecuperacion = $baseUrl . '/Harvey-s/autenticacion/pagina_recuperacion.php?token=' . $token;
        
        if (enviarCorreoRecuperacion($usuario['email'], $usuario['nombre'], $urlRecuperacion)) {
            echo "success";
        } else {
            echo "Error al enviar el correo de recuperación.";
        }
    
    } else {
        echo "Método no permitido.";
        exit;
    }
?>
