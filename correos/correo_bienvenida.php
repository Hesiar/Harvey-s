<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use Dotenv\Dotenv;

    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../elementos/credenciales', 'credenciales_gmail_Harveys.env');
    $dotenv->load();

    function enviarCorreoBienvenida($destinatario, $nombre) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->setLanguage('es', __DIR__ . '/../vendor/phpmailer/phpmailer/language/');

        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USERNAME'];
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
            $mail->addAddress($destinatario, $nombre);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a Harvey\'s';
            $mail->addEmbeddedImage(__DIR__ . '/../elementos/pics/Harveys_logo.png', 'logo_harveys');
            $mail->Body    = '
                <html lang="es">
                    <head>
                        <meta charset="utf-8">
                        <title>Bienvenido a Harvey\'s</title>
                    </head>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                        <div style="background-color: #005B1C; padding: 10px;">
                            <h1 style="margin: 0; color:rgb(255, 255, 255);">¡Bienvenido a nuestra gran familia de Harvey\'s!</h1>
                        </div>
                        <p><strong>Hola ' . htmlspecialchars($nombre) . ',</strong></p>
                        <p>Gracias por registrarte en nuestro club de clientes.<br>Esperamos que disfrutes de nuestras ofertas exclusivas para los miembros del club.</p>
                        <p>Saludos de parte de,<br>El equipo de Harvey\'s</p>
                        <p><img src="cid:logo_harveys" alt="Logo de Harvey\'s" style="width: 40px; height: 40px;"></p>
                    </body>
                </html>
            ';
            $mail->AltBody = 'Hola ' . $nombre . ",\n\nBienvenido a nuestra gran familia de Harvey's. Gracias por registarte en nustro club de clientes. Esperamos que disfrutes de nuestras ofertas exclusivas para los miembros del club.\n\nSaludos de parte de,\nEl equipo de Harvey's";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
?>
