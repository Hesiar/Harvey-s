<?php    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once '../fpdf186/fpdf.php';

    if (isset($_SESSION['usuario_id'])) {
        $cart_id = $_SESSION['usuario_id'];

        $host   = 'localhost';
        $dbname = 'harveys_DB';
        $dbuser = 'root';
        $dbpass = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT nombre, empleado_id FROM clientes WHERE id = :usuario_id");
            $stmt->execute([':usuario_id' => $_SESSION['usuario_id']]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            $nombreUsuario = $cliente['nombre'] ?? 'estimado cliente';
            $descuento = ($cliente ? 5 : 0) + (!empty($cliente['empleado_id']) ? 3 : 0);

        } catch (PDOException $e) {
            $descuento = 0; 
        }

    } else {
        if (!isset($_SESSION['guest_id'])) {
            $_SESSION['guest_id'] = session_id();
        }
        $cart_id = $_SESSION['guest_id'];
        $nombreUsuario = 'estimado cliente';
        $descuento = 0; 
    }

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->Image('../elementos/pics/Harveys_logo.png', 10, 10, 30);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Ticket de Compra', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, utf8_decode("Muchas gracias por tu compra, $nombreUsuario."), 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->Cell(190, 10, utf8_decode("Aquí tienes tu ticket de compra:"), 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Producto', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Precio', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Total', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 12);
    $totalPrecio = 0;
    foreach ($carrito as $producto) {
        $subtotal = $producto['precio'] * $producto['cantidad'];
        $totalPrecio += $subtotal;

        $pdf->Cell(100, 10, "      " . utf8_decode(htmlspecialchars($producto['producto'])), 1, 0, 'L');
        $pdf->Cell(30, 10, number_format($producto['precio'], 2) . " " . chr(128), 1, 0, 'C');
        $pdf->Cell(30, 10, $producto['cantidad'], 1, 0, 'C');
        $pdf->Cell(30, 10, number_format($subtotal, 2) . " " . chr(128), 1, 1, 'C');
    }

    $precioFinal = $totalPrecio * ((100 - $descuento) / 100);

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    
    if ($descuento > 0) {
        $pdf->Cell(160, 10, 'Total sin descuentos:', 0, 0, 'R');
        $pdf->Cell(30, 10, number_format($totalPrecio, 2) . " " . chr(128), 0, 1, 'R');
        $pdf->Cell(160, 10, 'Descuento aplicado:', 0, 0, 'R');
        $pdf->Cell(30, 10, $descuento . '%', 0, 1, 'R');
    }

    $pdf->Cell(160, 10, 'Total a pagar:', 0, 0, 'R');
    $pdf->Cell(30, 10, number_format($precioFinal, 2) . " " . chr(128), 0, 1, 'R');

    $fechaHora = date('d/m/Y H:i:s');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(190, 10, "Fecha y hora de la compra: $fechaHora", 0, 1, 'C');

    $pdf->Output('F', __DIR__ . '/../tickets/ticket_compra.pdf');

?>
