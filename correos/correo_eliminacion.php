<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use Dotenv\Dotenv;

    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../elementos/credenciales', 'credenciales_gmail_Harveys.env');
    $dotenv->load();

    function enviarCorreoEliminacion($destinatario, $nombre) {
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

            $mail->isHTML(true);
            $mail->Subject = 'Cuenta eliminada - Harvey\'s';
            $mail->addEmbeddedImage(__DIR__ . '/../elementos/pics/Harveys_logo.png', 'logo_harveys');
            $mail->Body    = '
                <html lang="es">
                    <head>
                        <meta charset="utf-8">
                        <title>Cuenta eliminada</title>
                    </head>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                        <div style="background-color: #8B0000; padding: 10px;">
                            <h1 style="margin: 0; color: white;">Tu cuenta en Harvey\'s ha sido eliminada</h1>
                        </div>
                        <p><strong>Hola ' . htmlspecialchars($nombre) . ',</strong></p>
                        <p>Lamentamos que ya no formes parte de nuestra gran familia. Tal y como solicitaste tu cuenta ha sido eliminada con éxito.</p>
                        <p>Pero si en algun momento deseas volver en el futuro, no dudes en registrarte nuevamente.</p>
                        <p>Saludos,<br>El equipo de Harvey\'s</p>
                        <p><img src="cid:logo_harveys" alt="Logo de Harvey\'s" style="width: 40px; height: 40px;"></p>
                    </body>
                </html>
            ';
            $mail->AltBody = "Hola $nombre,\n\nTu cuenta en Harvey's ha sido eliminada. que ya no formes parte de nuestra gran familia.\nSi deseas volver en el futuro, regístrate nuevamente.\n\nSaludos,\nEl equipo de Harvey's";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo de eliminación: {$mail->ErrorInfo}");
            return false;
        }
    }
?>
