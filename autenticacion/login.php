<?php

    session_start();

    if (isset($_POST['checkAuth'])) {
        echo isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] ? "true" : "false";
        exit;
    }

    $host = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';        
    $dbpass = '';            

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }

    if (isset($_COOKIE['usuario_id'])) {
        $_SESSION['usuario_id'] = $_COOKIE['usuario_id'];
    
        $stmt = $pdo->prepare("SELECT nombre, email FROM clientes WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['usuario_id']]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($usuario) {
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['loggedIn'] = true;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'], $_POST['contrasenia'])) {
        
        $correo = trim($_POST['correo']);
        $contrasenia = trim($_POST['contrasenia']);
        $recordar = isset($_POST['recordar_sesion']);

        if (empty($correo) || empty($contrasenia)) {
            echo "Por favor, completa todos los campos.";
            exit;
        }
        
        $stmt = $pdo->prepare("SELECT id, nombre, email, contrasenia FROM clientes WHERE email = :correo");
        $stmt->execute([':correo' => $correo]);
        
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cliente && password_verify($contrasenia, $cliente['contrasenia'])) {
            $_SESSION['usuario_id'] = $cliente['id'];
            $_SESSION['nombre'] = $cliente['nombre'];
            $_SESSION['email'] = $cliente['email'];
            $_SESSION['loggedIn'] = true;

            if ($recordar) {
                setcookie("usuario_id", $cliente['id'], time() + (86400 * 30), "/"); 
            }

            echo "success";
            exit;
        } else {
            $_SESSION['loggedIn'] = false;
            echo "Usuario o contraseña incorrectos.";
        }
    }
?>
