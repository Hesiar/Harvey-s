<?php
    session_start();

    $host = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    if (!isset($_SESSION['usuario_id'])) {
        echo "No hay sesión activa.";
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmtUser = $pdo->prepare("SELECT nombre, email FROM clientes WHERE id = :usuario_id");
        $stmtUser->execute([':usuario_id' => $_SESSION['usuario_id']]);
        $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            echo "Error: Usuario no encontrado.";
            exit;
        }

        require_once '../correos/correo_eliminacion.php';

        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :usuario_id");
        $stmt->execute([':usuario_id' => $_SESSION['usuario_id']]);

        if ($stmt->rowCount() > 0) {
            enviarCorreoEliminacion($usuario['email'], $usuario['nombre']);

            session_destroy();
            setcookie("usuario_id", "", time() - 3600, "/");

            echo "success";
        } else {
            echo "Error al eliminar la cuenta.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>
