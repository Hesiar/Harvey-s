<?php
    session_start();

    $host   = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario'], $_POST['clave'])) {

        $usuario = trim($_POST['usuario']);
        $clave   = trim($_POST['clave']);

        if (empty($usuario) || empty($clave)) {
            echo "Por favor, completa todos los campos.";
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM empleados WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($empleado && password_verify($clave, $empleado['clave'])) {
            $_SESSION['empleado'] = $empleado['id'];
            echo "success";
        } else {
            echo "<p style='color: red;'><strong>Usuario o contraseña incorrectos.</strong></p>";
        }
    }
?>
