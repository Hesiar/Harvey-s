<?php
    session_start();
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['nombre'], $data['cantidad'])) {
        foreach ($_SESSION['carrito'] as &$producto) {
            if ($producto['nombre'] === $data['nombre']) {
                $producto['cantidad'] = max(1, intval($data['cantidad']));
                break;
            }
        }
    }

    echo json_encode(["status" => "ok"]);
?>
