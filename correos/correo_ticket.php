<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use Dotenv\Dotenv;

    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/../correos/pdf_compra.php'; 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../elementos/credenciales', 'credenciales_gmail_Harveys.env');
    $dotenv->load();

    function enviarCorreoTicket($destinatario, $nombreUsuario) {
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
            $mail->addAddress($destinatario, $nombreUsuario);

            $mail->addAttachment(__DIR__ . '/../tickets/ticket_compra.pdf');

            $mail->isHTML(true);
            $mail->Subject = 'Comprobante de Compra - Harvey\'s';
            $mail->addEmbeddedImage(__DIR__ . '/../elementos/pics/Harveys_logo.png', 'logo_harveys');
            $mail->Body    = '
                <html lang="es">
                    <head>
                        <meta charset="utf-8">
                        <title>Recuperación de Contraseña</title>
                    </head>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                        <p><strong>Hola ' . htmlspecialchars($nombreUsuario) . ',</strong></p>
                        <p>Gracias por tu compra en Harvey\'s.</p>
                        <p>A continuación se le adjunta el ticket de su compra.</p>
                        <p>Saludos,<br>El equipo de Harvey\'s</p>
                        <p>
                            <img src="cid:logo_harveys" alt="Logo de Harvey\'s" style="width: 40px; height: 40px;">
                        </p>
                    </body>
                </html>
            ';
            $mail->AltBody = 'Hola ' . $nombreUsuario . ',
                Gracias por tu compra en Harvey\'s.
                A continuación se le adjunta el ticket de su compra.
                Saludos,
                El equipo de Harvey\'s
            ';

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo: {$mail->ErrorInfo}");
            return false;
        }
    }
?>
