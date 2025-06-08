<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> 
    <title>Harvey's | Recuperación de contraseña</title>
    <link rel="stylesheet" href="/Harvey-s/elementos/css/css_recuperacion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="divRecuperacion" id="divRecuperacion">
        <?php
        $pdo = new PDO("mysql:host=localhost;dbname=harveys_DB;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!isset($_GET['token']) || empty($_GET['token'])) {
            echo "<script>
                Swal.fire({
                    title: 'Acceso denegado',
                    icon: 'error',
                    allowOutsideClick: false,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#fa0505',
                    width: '300px',
                    iconColor: '#fa0505'
                }).then(() => {
                    window.location.href = '/Harvey-s/layout/home.php';
                });
            </script>";
            exit;
        }

        $token = $_GET['token'];
        $stmt = $pdo->prepare("SELECT id, password_reset_expires FROM clientes WHERE password_reset_token = :token");
        $stmt->execute([':token' => $token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            echo "<script>
                Swal.fire({
                    title: 'Token inválido o no encontrado',
                    text: 'El token de verificación no es válido o ya ha sido utilizado.',
                    icon: 'error',
                    allowOutsideClick: false,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#fa0505',
                    width: '300px',
                    iconColor: '#fa0505'
                }).then(() => {
                    window.location.href = '/Harvey-s/layout/home.php';
                });
            </script>";
            exit;
        }

        if (strtotime($usuario['password_reset_expires']) < time()) {
            echo "<script>
                Swal.fire({
                    title: 'Token expirado',
                    text: 'Por favor, solicita un nuevo restablecimiento de contraseña.',
                    icon: 'error',
                    allowOutsideClick: false,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#fa0505',
                    width: '300px',
                    iconColor: '#fa0505'
                }).then(() => {
                    window.location.href = '/Harvey-s/layout/home.php';
                });
            </script>";
            exit;
        }
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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function(){
            function limpiarError(input) {
                input.next(".error-message").remove();
            }

            function verificarCampos() {
                let nuevaContrasenia = $("input[name='nueva_contrasenia']");
                let confirmarContrasenia = $("input[name='confirmar_contrasenia']");
                let regexContrasenia = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$/;

                limpiarError(nuevaContrasenia);
                limpiarError(confirmarContrasenia);

                let error = false;

                if (nuevaContrasenia.val().trim() === "") {
                    error = true;
                } else if (!regexContrasenia.test(nuevaContrasenia.val().trim())) {
                    nuevaContrasenia.after("<p class='error-message' style='color: red; font-weight: bold;'>Debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula, un número y un carácter especial.</p>");
                    error = true;
                }

                if (confirmarContrasenia.val().trim() === "") {
                    error = true;
                } else if (nuevaContrasenia.val().trim() !== confirmarContrasenia.val().trim()) {
                    confirmarContrasenia.after("<p class='error-message' style='color: red; font-weight: bold;'>Las contraseñas no coinciden.</p>");
                    error = true;
                }

                let boton = $("button[type='submit']");
                boton.prop("disabled", error);

                if (error) {
                    boton.css({
                        "background-color": "#ccc",
                        "cursor": "not-allowed",
                        "opacity": "0.5"
                    });
                } else {
                    boton.css({
                        "background-color": "#005B1C",
                        "cursor": "pointer",
                        "opacity": "1"
                    });
                }
            }

            verificarCampos();

            $("input[name='nueva_contrasenia'], input[name='confirmar_contrasenia']").on("input", verificarCampos);

            $("#formRecuperacion").on('submit', function(e) {
                e.preventDefault(); 

                verificarCampos(); 

                if ($(".error-message").length > 0) return; 

                $.ajax({
                    url: $(this).attr("action"),
                    type: $(this).attr("method"),
                    data: $(this).serialize(),
                    dataType: "json", 
                    success: function(response) {
                        Swal.fire({
                            title: response.status === "success" ? '¡Actualización exitosa!' : 'Error',
                            text: response.mensaje || 'Ocurrió un error inesperado. Inténtalo más tarde.',
                            icon: response.status === "success" ? 'success' : 'error',
                            allowOutsideClick: false,
                            confirmButtonText: 'OK',
                            confirmButtonColor: response.status === "success" ? '#155724' : '#fa0505',
                            width: '300px',
                            iconColor: response.status === "success" ? '#155724' : '#fa0505'
                        }).then(() => {
                            if (response.status === "success") {
                                window.location.href = "/Harvey-s/layout/home.php";
                            }
                        }).then(() => {
                            if (response.status === "success") {
                                setTimeout(() => {
                                    window.location.href = "/Harvey-s/layout/home.php";
                                }, 5000);
                            }
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error en el servidor',
                            text: 'Ocurrió un error inesperado. Inténtalo más tarde.',
                            icon: 'error',
                            allowOutsideClick: false,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#fa0505',
                            width: '300px',
                            iconColor: '#fa0505'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
