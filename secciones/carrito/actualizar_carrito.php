<?php
    session_start();

    $host   = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    if (isset($_SESSION['usuario_id'])) {
        $cart_id = $_SESSION['usuario_id'];
    } else {
        if (!isset($_SESSION['guest_id'])) {
            $_SESSION['guest_id'] = session_id();
        }
        $cart_id = $_SESSION['guest_id'];
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", 
                    $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = json_decode(file_get_contents("php://input"), true);

         if (isset($data['cantidad']) && intval($data['cantidad']) === 0) {
            $stmt = $pdo->prepare("DELETE FROM carritos WHERE usuario_id = :cart_id");
            $stmt->execute([':cart_id' => $cart_id]);
        } elseif (isset($data['nombre'], $data['cantidad'])) {
            $stmt = $pdo->prepare("UPDATE carritos 
                                SET cantidad = :cantidad 
                                WHERE usuario_id = :cart_id 
                                    AND producto = :producto");
            $stmt->execute([
                ':cantidad' => max(1, intval($data['cantidad'])),
                ':cart_id'  => $cart_id,
                ':producto' => $data['nombre']
            ]);
        }

        echo json_encode(["status" => "ok"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>
