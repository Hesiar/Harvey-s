<?php
    session_start();
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['nombre'])) {
        $_SESSION['carrito'] = array_filter($_SESSION['carrito'], function ($producto) use ($data) {
            return $producto['nombre'] !== $data['nombre'];
        });
    }

    echo json_encode(["status" => "ok"]);
?>
