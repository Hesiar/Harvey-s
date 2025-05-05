<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response = [
            "status"  => "error",
            "mensaje" => "Método no permitido."
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

    if (
        !isset($_POST['token']) || empty($_POST['token']) ||
        !isset($_POST['nueva_contrasenia']) || empty($_POST['nueva_contrasenia']) ||
        !isset($_POST['confirmar_contrasenia']) || empty($_POST['confirmar_contrasenia'])
    ) {
        $response = [
            "status"  => "error",
            "mensaje" => "Todos los campos son obligatorios."
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

    $token = $_POST['token'];
    $nueva_contrasenia = $_POST['nueva_contrasenia'];
    $confirmar_contrasenia = $_POST['confirmar_contrasenia'];

    if ($nueva_contrasenia !== $confirmar_contrasenia) {
        $response = [
            "status"  => "error",
            "mensaje" => "Las contraseñas no coinciden."
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=harveys_DB;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $response = [
            "status"  => "error",
            "mensaje" => "Error en la conexión a la base de datos."
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

    $stmt = $pdo->prepare("SELECT id, password_reset_expires FROM clientes WHERE password_reset_token = :token");
    $stmt->execute([':token' => $token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $response = [
            "status"  => "error",
            "mensaje" => "Token inválido o no encontrado."
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

    if (strtotime($usuario['password_reset_expires']) < time()) {
        $response = [
            "status"  => "error",
            "mensaje" => "El token ha expirado. Solicita un nuevo restablecimiento de contraseña."
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

    $nueva_contrasenia_hash = password_hash($nueva_contrasenia, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE clientes SET contrasenia = :contrasenia WHERE id = :id");
    $result = $stmt->execute([
        ':contrasenia' => $nueva_contrasenia_hash,
        ':id'          => $usuario['id']
    ]);

    if ($result) {
        $stmt = $pdo->prepare("UPDATE clientes SET password_reset_token = NULL, password_reset_expires = NULL WHERE id = :id");
        $stmt->execute([':id' => $usuario['id']]);

        $response = [
            "status"  => "success",
            "mensaje" => "Contraseña actualizada exitosamente. Ya puedes cerrar esta ventana."
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    } else {
        $response = [
            "status"  => "error",
            "mensaje" => "Ocurrió un error al actualizar la contraseña."
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
?>
