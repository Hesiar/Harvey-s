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

        if (isset($data['nombre'], $data['precio'])) {
            $producto = $data['nombre'];
            $precio = floatval($data['precio']);
            $cantidad = isset($data['cantidad']) ? intval($data['cantidad']) : 1;

            $stmt = $pdo->prepare("SELECT cantidad FROM carritos 
                                WHERE usuario_id = :cart_id 
                                    AND producto = :producto");
            $stmt->execute([
                ':cart_id'  => $cart_id,
                ':producto' => $producto
            ]);
            $existe = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existe) {
                $nuevaCantidad = $existe['cantidad'] + $cantidad;
                $stmt = $pdo->prepare("UPDATE carritos 
                                    SET cantidad = :cantidad 
                                    WHERE usuario_id = :cart_id 
                                        AND producto = :producto");
                $stmt->execute([
                    ':cantidad'  => $nuevaCantidad,
                    ':cart_id'   => $cart_id,
                    ':producto'  => $producto
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO carritos (usuario_id, producto, cantidad, precio)
                                    VALUES (:cart_id, :producto, :cantidad, :precio)");
                $stmt->execute([
                    ':cart_id'  => $cart_id,
                    ':producto' => $producto,
                    ':cantidad' => $cantidad,
                    ':precio'   => $precio
                ]);
            }
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Faltan datos."]);
        }
    } catch(PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>
