<?php
    session_regenerate_id();

    $host   = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    if (!isset($_SESSION['empleado'])) {
        header("Location: ../layout/home.php");
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT nombre, apellido, email, telefono, puesto_id, email_cliente, dni, usuario, fecha_contratacion, antiguedad, direccion, ciudad, codigo_postal FROM empleados WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['empleado']]);
        $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }

    $puestos = [
        1 => "Gerente",
        2 => "Cajero",
        3 => "Reponedor",
        4 => "Jefe de sección",
        5 => "Operario de almacén",
        6 => "Marketing y publicidad",
        7 => "Administrativo",
        8 => "Técnico en mantenimiento"
    ];

    $puesto_nombre = $puestos[$empleado['puesto_id']] ?? "Desconocido";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración de Perfil</title>
    <link rel="stylesheet" href="../elementos/css/css_perfil_empleados.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="perfil">
        <h2>Mis Datos</h2>
        <p><strong>Nombre:</strong> <span id="nombre"><?= htmlspecialchars($empleado['nombre']) ?></span></p>
        <p><strong>Apellido:</strong> <span id="apellido"><?= htmlspecialchars($empleado['apellido']) ?></span></p>
        <p><strong>DNI:</strong> <span id="dni"><?= htmlspecialchars($empleado['dni']) ?></span></p>
        <p><strong>Usuario:</strong> <span id="usuario"><?= htmlspecialchars($empleado['usuario']) ?></span></p>
        <p><strong>Email corporativo:</strong> <span id="email"><?= htmlspecialchars($empleado['email']) ?></span></p>
        <p><strong>Email registrado como Cliente:</strong> <span id="email_cliente"><?= htmlspecialchars($empleado['email_cliente'] ?? 'No registrado') ?></span></p>
        <p><strong>Teléfono:</strong> <span id="telefono"><?= htmlspecialchars($empleado['telefono']) ?></span></p>
        <p><strong>Dirección:</strong> <span id="direccion"><?= htmlspecialchars($empleado['direccion'] ?? 'No registrada') ?></span></p>
        <p><strong>Ciudad:</strong> <span id="ciudad"><?= htmlspecialchars($empleado['ciudad'] ?? 'No registrada') ?></span></p>
        <p><strong>Código Postal:</strong> <span id="codigo_postal"><?= htmlspecialchars($empleado['codigo_postal'] ?? 'No registrado') ?></span></p>
        <p><strong>Puesto:</strong> <span id="puesto"><?= htmlspecialchars($puesto_nombre) ?></span></p>
        <?php setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain', 'es_ES'); ?>
        <p><strong>Fecha de contratación:</strong> <span id="fecha_contratacion"><?= strftime("%d de %B de %Y", strtotime($empleado['fecha_contratacion'])) ?></span></p>
        <p><strong>Antiguedad:</strong> <span id="antiguedad"><?= htmlspecialchars($empleado['antiguedad']) ?> años</span></p>
        
        <button id="editar">Editar</button>
    </div>

    <!-- Formulario de edición -->
    <div class="editar-perfil" style="display: none;">
        <div class="hijo">
            <h2>Editar Datos</h2>
            <form id="formEditar">
                <label>Nombre:</label>
                <input type="text" name="nombre" id="edit-nombre" value="<?= htmlspecialchars($empleado['nombre']) ?>">

                <label>Apellido:</label>
                <input type="text" name="apellido" id="edit-apellido" value="<?= htmlspecialchars($empleado['apellido']) ?>">

                <label>DNI:</label>
                <input type="text" name="dni" id="edit-dni" value="<?= htmlspecialchars($empleado['dni']) ?>" oninput="generarUsuario()">

                <label>Usuario:</label>
                <input type="text" name="usuario" id="edit-usuario" value="<?= htmlspecialchars($empleado['usuario']) ?>" readonly>

                <label>Email registrado como Cliente:</label>
                <input type="email" name="email_cliente" id="edit-email_cliente" value="<?= htmlspecialchars($empleado['email_cliente'] ?? '') ?>">

                <label>Teléfono:</label>
                <input type="text" name="telefono" id="edit-telefono" value="<?= htmlspecialchars($empleado['telefono']) ?>">

                <label>Dirección:</label>
                <input type="text" name="direccion" id="edit-direccion" value="<?= htmlspecialchars($empleado['direccion'] ?? '') ?>">

                <label>Ciudad:</label>
                <input type="text" name="ciudad" id="edit-ciudad" value="<?= htmlspecialchars($empleado['ciudad'] ?? '') ?>">

                <label>Código Postal:</label>
                <input type="text" name="codigo_postal" id="edit-codigo_postal" value="<?= htmlspecialchars($empleado['codigo_postal'] ?? '') ?>">

                <p style="font-weight: bold;" class="aviso">
                    Recuerda, si necesitas una nueva contraseña o modificar tu correo corporativo contacta con RRHH.
                </p>

                <div class="button-container">
                    <button type="submit">Guardar cambios</button>
                    <button id="cancelar-edicion">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dniInput = document.getElementById('edit-dni');
            const emailInput = document.getElementById('edit-email_cliente');
            const usuarioInput = document.getElementById('edit-usuario');
            const guardarBtn = document.querySelector("#formEditar button[type='submit']");
            const telefonoInput = document.getElementById('edit-telefono');
            const codigoPostalInput = document.getElementById('edit-codigo_postal');
            
            telefonoInput.addEventListener("input", function() {
                const telefonoValido = /^[6789]\d{8}$/.test(telefonoInput.value);
                
                if (!telefonoValido) {
                    telefonoInput.style.border = "4px solid red";
                }else {
                    telefonoInput.style.border = "0px solid red";
                }
            });

            codigoPostalInput.addEventListener("input", function() {
                const cpValido = /^[0-5]\d{4}$/.test(codigoPostalInput.value);
                
                if (!cpValido) {
                    codigoPostalInput.style.border = "4px solid red";
                } else {
                    codigoPostalInput.style.border = "0px solid red";
                }
            });

            function validarFormulario() {
                const esDniValido = /^[XYZ]?[0-9]{7,8}[A-Z]$/.test(dniInput.value.toUpperCase());
                const esEmailValido = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(emailInput.value);

                guardarBtn.disabled = !(esDniValido && esEmailValido);
                guardarBtn.style.opacity = guardarBtn.disabled ? "0.5" : "1"; 
            }

            dniInput.addEventListener("input", function() {
                let dniNie = dniInput.value.toUpperCase();

                if (!/^[XYZ]?[0-9]{7,8}[A-Z]$/.test(dniNie)) {
                    dniInput.style.border = "4px solid red";
                } else {
                    dniInput.style.border = "0px solid red";
                }

                if (/^[XYZ]/.test(dniNie)) {
                    const conversion = { X: "0", Y: "1", Z: "2" };
                    dniNie = conversion[dniNie[0]] + dniNie.substring(1);
                }

                if (/^[0-9]{8}[A-Z]$/.test(dniNie)) {
                    usuarioInput.value = dniNie.slice(-1) + dniNie.slice(0, -1);
                }

                validarFormulario();
            });

            emailInput.addEventListener("input", function() {
                const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailPattern.test(emailInput.value)) {
                    emailInput.style.border = "2px solid red";
                } else {
                    emailInput.style.border = "2px solid green";
                }
            });
        });

        function generarUsuario() {
            let dniNie = document.querySelector("[name='dni']").value.toUpperCase();

            if (/^[XYZ]/.test(dniNie)) {
                const letraInicial = dniNie[0];
                const conversion = { X: "0", Y: "1", Z: "2" };
                dniNie = conversion[letraInicial] + dniNie.substring(1);
            }

            if (/^[0-9]{8}[A-Z]$/.test(dniNie)) {
                document.getElementById('usuario').value = dniNie.slice(-1) + dniNie.slice(0, -1);
            }
        }

        document.getElementById('editar').addEventListener('click', function() {
            document.querySelector('.perfil').style.display = "none";
            document.querySelector('.editar-perfil').style.display = "block";
        });

        document.getElementById('cancelar-edicion').addEventListener('click', function() {
            event.preventDefault(); 
            event.stopPropagation();

            document.getElementById('edit-nombre').value = document.getElementById('nombre').textContent;
            document.getElementById('edit-apellido').value = document.getElementById('apellido').textContent;
            document.getElementById('edit-dni').value = document.getElementById('dni').textContent;
            document.getElementById('edit-usuario').value = document.getElementById('usuario').textContent;
            document.getElementById('edit-email_cliente').value = document.getElementById('email_cliente').textContent;
            document.getElementById('edit-telefono').value = document.getElementById('telefono').textContent;
            document.getElementById('edit-direccion').value = document.getElementById('direccion').textContent;
            document.getElementById('edit-ciudad').value = document.getElementById('ciudad').textContent;
            document.getElementById('edit-codigo_postal').value = document.getElementById('codigo_postal').textContent;

            document.querySelector('.perfil').style.display = "block";
            document.querySelector('.editar-perfil').style.display = "none";
        });

        document.getElementById('formEditar').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('../empleados/actualizar_perfil_empleado.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        iconColor: "#155724",
                        title: '¡Perfil actualizado!',
                        text: data.message,
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#155724'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        iconColor: "#fa0505",
                        title: 'Error',
                        text: data.message,
                        allowOutsideClick: false,
                        confirmButtonColor: '#fa0505',
                        confirmButtonText: 'Intentar de nuevo'
                    });
                }
            })
            .catch(error => {
                console.error('Error al actualizar perfil:', error);
                Swal.fire({
                    icon: 'error',
                    iconColor: "#fa0505",
                    allowOutsideClick: false,
                    title: 'Error inesperado',
                    text: 'Hubo un problema con la actualización.',
                    confirmButtonText: 'Cerrar',
                    confirmButtonColor: '#fa0505'
                });
            });
        });


        document.getElementById('cerrar-sesion').addEventListener('click', function() {
            fetch('/Harvey-s/autenticacion/logout_empleados.php', { method: 'POST' })
                .then(() => window.location.href = "/Harvey-s/layout/home.php");
        });
    </script>
</body>
</html>
