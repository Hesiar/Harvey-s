<?php
    session_start();

    header('Content-Type: application/json');

    if (!isset($_SESSION['empleado'])) {
        echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
        exit;
    }

    $host   = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre       = trim($_POST['nombre'] ?? '');
        $apellido     = trim($_POST['apellido'] ?? '');
        $dni          = trim($_POST['dni'] ?? '');
        $usuario      = trim($_POST['usuario'] ?? '');
        $email_cliente = trim($_POST['email_cliente'] ?? '');
        $telefono     = trim($_POST['telefono'] ?? '');
        $direccion    = trim($_POST['direccion'] ?? '');
        $ciudad       = trim($_POST['ciudad'] ?? '');
        $codigo_postal = trim($_POST['codigo_postal'] ?? '');

        try {
            $stmt = $pdo->prepare("
                UPDATE empleados SET 
                    nombre = :nombre, 
                    apellido = :apellido, 
                    dni = :dni,
                    usuario = :usuario, 
                    email_cliente = :email_cliente,
                    telefono = :telefono,
                    direccion = :direccion,
                    ciudad = :ciudad,
                    codigo_postal = :codigo_postal
                WHERE id = :id
            ");

            $stmt->execute([
                ':nombre'   => $nombre,
                ':apellido' => $apellido,
                ':dni'      => $dni,
                ':usuario'  => $usuario,
                ':email_cliente' => !empty($email_cliente) ? $email_cliente : null,
                ':telefono' => $telefono,
                ':direccion' => !empty($direccion) ? $direccion : null,
                ':ciudad'   => !empty($ciudad) ? $ciudad : null,
                ':codigo_postal' => !empty($codigo_postal) ? $codigo_postal : null,
                ':id'       => $_SESSION['empleado']
            ]);

            echo json_encode(['status' => 'success', 'message' => 'Perfil actualizado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el perfil']);
        }
        exit;
    }


    echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida']);
    exit;
?>
