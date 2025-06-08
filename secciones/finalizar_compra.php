<?php
    session_start();
    ob_start();

    $host = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_SESSION['usuario_id'])) {
            $cart_id = $_SESSION['usuario_id'];
        } else {
            if (!isset($_SESSION['guest_id'])) {
                $_SESSION['guest_id'] = session_id();
            }
            $cart_id = $_SESSION['guest_id'];
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM carritos WHERE usuario_id = :cart_id");
        $stmt->execute([':cart_id' => $cart_id]);
        $totalProductos = $stmt->fetchColumn();

        if ($totalProductos == 0) {
            header("Location: /Harvey-s/layout/home.php");
            exit;
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_submission'])) {
        header('Content-Type: application/json');

        try {
            if (isset($_SESSION['usuario_id'])) {
                $cart_id = $_SESSION['usuario_id'];
                $nombreUsuario = $_SESSION['usuario_nombre'] ?? 'Cliente';

                $stmt = $pdo->prepare("SELECT email FROM clientes WHERE id = :usuario_id");
                $stmt->execute([':usuario_id' => $cart_id]);
                $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

                $destinatario = $cliente['email'] ?? ($_POST['guest_email'] ?? null);
            } else {
                $cart_id = $_SESSION['guest_id'];
                $nombreUsuario = 'Cliente Invitado';
                $destinatario = $_POST['guest_email'] ?? null;
            }

            if (!$destinatario) {
                echo json_encode(['status' => 'error', 'message' => 'Debes ingresar un correo electrónico para recibir el ticket.']);
                exit;
            }

            $stmt = $pdo->prepare("SELECT producto, cantidad, precio FROM carritos WHERE usuario_id = :cart_id");
            $stmt->execute([':cart_id' => $cart_id]);
            $carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalPrecio = 0;
            foreach ($carrito as $producto) {
                $totalPrecio += $producto['precio'] * $producto['cantidad'];
            }

            if ($totalPrecio <= 0) {
                ob_end_clean();
                echo json_encode(['status' => 'error', 'message' => 'El total de la compra no puede ser 0€.']);
                exit;
            }

            $paymentData = ['amount' => $totalPrecio, 'payment_method' => 'Credit Card'];
            $api_url = "https://apitpoint.com/api/payMock.php";
            $ch = curl_init($api_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($paymentData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $paymentResponse = json_decode($response, true);

            if ($paymentResponse && $paymentResponse['status'] === 'Success') {
                $stmt = $pdo->prepare("INSERT INTO ventas (cliente_id, fecha) VALUES (:cliente_id, NOW())");
                $stmt->execute([':cliente_id' => isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null]);
                $venta_id = $pdo->lastInsertId();

                foreach ($carrito as $producto) {
                    $stmt = $pdo->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, subtotal) VALUES (:venta_id, (SELECT id FROM productos WHERE nombre = :producto), :cantidad, :subtotal)");
                    $stmt->execute([
                        ':venta_id' => $venta_id,
                        ':producto' => $producto['producto'],
                        ':cantidad' => $producto['cantidad'],
                        ':subtotal' => $producto['precio'] * $producto['cantidad']
                    ]);
                }

                $stmt = $pdo->prepare("DELETE FROM carritos WHERE usuario_id = :cart_id");
                $stmt->execute([':cart_id' => $cart_id]);

                foreach ($carrito as $producto) {
                    $stmt = $pdo->prepare("UPDATE productos SET stock = stock - :cantidad WHERE nombre = :producto");
                    $stmt->execute([
                        ':cantidad' => $producto['cantidad'],
                        ':producto' => $producto['producto']
                    ]);
                }

                require '../correos/pdf_compra.php';
                require '../correos/correo_ticket.php';
                enviarCorreoTicket($destinatario, $nombreUsuario);

                ob_end_clean();
                echo json_encode(['status' => 'ok', 'message' => 'Compra finalizada con éxito. Ticket enviado al correo.']);
            } else {
                ob_end_clean();
                echo json_encode(['status' => 'error', 'message' => 'Error en el pago.']);
            }
            exit;
        } catch (PDOException $e) {
            ob_end_clean();
            var_dump($response);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../elementos/css/css_pagos.css">
  <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <title>Harvey's | Plataforma de Pago Ficticia</title>
</head>
<body>
    <div class="fondo"></div>
    <h2>Plataforma de Pago Ficticia</h2>
    <div class="contenido">
        <div id="tarjetas_aceptadas">
            <h3>Tarjetas Aceptadas</h3>
            <img src="../elementos/pics/visa-mastercard-discover-american-express-icons.png" alt="Tarjetas aceptadas">
            <img src="../elementos/pics/Harveys_logo.png" alt="Logo de Harvey's" class="logo-harveys">
        </div>
        
        <div class="form-wrapper">
            <form action="finalizar_compra.php" method="POST">
                <h3>Datos de Pago</h3>
                <label for="card_holder">Nombre en la tarjeta:</label>
                <input type="text" id="card_holder" name="card_holder" required>

                <div class="card-container">
                    <label for="card_number">Número de tarjeta:</label>
                    <input type="text" id="card_number" name="card_number" required oninput="showCardIcon()">
                    <span id="card-icon" class="card-icon"></span>
                </div>
                <p class="error-cardNumber"></p>

                <label for="expiry">Fecha de expiración (MM/AA):</label>
                <input type="text" id="expiry" name="expiry" required>

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>

                <?php if (!isset($_SESSION['usuario_id'])): ?>
                    <label for="guest_email">Correo electrónico para recibir el ticket:</label>
                    <input type="email" id="guest_email" name="guest_email" required>
                <?php endif; ?>

                <input type="hidden" name="payment_submission" value="1">
                <button type="submit">Pagar</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showCardIcon() {
            const cardNumber = document.getElementById("card_number").value;
            const cardIcon = document.getElementById("card-icon");

            cardIcon.style.backgroundImage = "";

            let cardType = "";

            switch (true) {
                case /^4/.test(cardNumber):
                    cardType = "visa";
                    break;
                case /^5[1-5]/.test(cardNumber):
                case /^222[1-9]/.test(cardNumber):
                case /^22[3-9]/.test(cardNumber):
                case /^2[3-6]/.test(cardNumber):
                case /^27[0-1]/.test(cardNumber):
                    cardType = "mastercard";
                    break;
                case /^3[47]/.test(cardNumber):
                    cardType = "amex";
                    break;
                case /^6(011|5|4[4-9]|22[1-9]|22[6-9]|62[2-9]|64[4-9]|65)/.test(cardNumber):
                    cardType = "discover";
                    break;
                default:
                    cardType = "";
            }

            switch (cardType) {
                case "visa":
                    cardIcon.style.backgroundImage = "url('/Harvey-s/elementos/pics/visa.png')";
                    break;
                case "mastercard":
                    cardIcon.style.backgroundImage = "url('/Harvey-s/elementos/pics/mastercard.png')";
                    break;
                case "amex":
                    cardIcon.style.backgroundImage = "url('/Harvey-s/elementos/pics/americanexpress.png')";
                    break;
                case "discover":
                    cardIcon.style.backgroundImage = "url('/Harvey-s/elementos/pics/discover.png')";
                    break;
                default:
                    cardIcon.style.backgroundImage = "";
            }

            console.log("Tipo de tarjeta detectado:", cardType);
            console.log("Imagen asignada:", cardIcon.style.backgroundImage);
        }
    </script>
    <script src="/Harvey-s/elementos/scripts/script_plataforma_pago.js"></script>
</body>
</html>
