<?php
    session_start();

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario_id'])) {
        $_SESSION['usuario_id'] = $_POST['usuario_id'];
        $_SESSION['nombre'] = $_POST['nombre'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['loggedIn'] = true;

        echo "success";
        exit;
    }
?>