<?php
    session_start();

    $host   = 'localhost';
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
            $_SESSION['nombre']     = $usuario['nombre'];
            $_SESSION['email']      = $usuario['email'];       
            
            echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Harvey\'s | Verificación de cuenta</title>
        <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/Harvey-s/elementos/css/estilos.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Asegúrate de incluir jQuery para usar $.ajax -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: "¡Hola, ' . htmlspecialchars($usuario["nombre"]) . '!",
                text: "Tu cuenta ha sido verificada exitosamente.",
                icon: "success",
                iconColor: "#155724",
                confirmButtonColor: "#155724",
                confirmButtonText: "Ir a mi cuenta",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/Harvey-s/autenticacion/iniciar_sesion_verificados.php",
                        type: "POST",
                        data: {
                            usuario_id: "' . $usuario["id"] . '",
                            nombre: "' . addslashes($usuario["nombre"]) . '",
                            email: "' . $usuario["email"] . '"
                        },
                        success: function(response) {
                            if (response.trim() === "success") {
                                window.location.href = "/Harvey-s/secciones/cuenta.php";
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: "Hubo un problema iniciando la sesión.",
                                    icon: "error",
                                    iconColor: "#de301d",
                                    confirmButtonText: "Intentar de nuevo",
                                    confirmButtonColor: "#155724"
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error en el servidor",
                                text: "Ocurrió un problema inesperado. Inténtalo más tarde.",
                                icon: "error",
                                iconColor: "#de301d",
                                confirmButtonText: "OK",
                                confirmButtonColor: "#155724"
                            });
                        }
                    });
                }
            });
        </script>
    </body>
    </html>';
        } else {
            echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Harvey\'s | Verificación de cuenta</title>
        <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/Harvey-s/elementos/css/estilos.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: "Se ha producido un error",
                text: "El token de verificación es inválido o ya ha sido usado.",
                icon: "error",
                iconColor: "#de301d",
                confirmButtonColor: "#de301d",
                confirmButtonText: "Salir",
                allowOutsideClick: false
            }).then((result) => {
                window.location.href = "/Harvey-s/layout/home.php";
            });
        </script>
    </body>
    </html>';
        }
    }
?>
