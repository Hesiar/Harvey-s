<?php
    session_start();

    if (isset($_SESSION['usuario_id'])) {
        $cart_id = $_SESSION['usuario_id'];
    } else {
        if (!isset($_SESSION['guest_id'])) {
            $_SESSION['guest_id'] = session_id(); 
        }
        $cart_id = $_SESSION['guest_id'];
    }

    $host   = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT producto, cantidad, precio FROM carritos WHERE usuario_id = :cart_id");
        $stmt->execute([':cart_id' => $cart_id]);
        $carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($carrito);
    } catch (PDOException $e) {
        echo json_encode([]);
    }
?>
