<?php
    $host   = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    header('Content-Type: application/json'); 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query("SELECT nombre, precio, imagen FROM productos WHERE categoria = 'Dulces'");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($productos);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
?>
