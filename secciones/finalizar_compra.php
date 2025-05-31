<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_submission'])) {
        header('Content-Type: application/json'); 

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

            $totalPrecio = 0;
            foreach ($carrito as $producto) {
                $totalPrecio += $producto['precio'] * $producto['cantidad'];
            }

            $paymentData = [
                'amount' => $totalPrecio,
                'payment_method' => 'Credit Card'
            ];
            $api_url = "https://apitpoint.com/api/payMock.php";
            $ch = curl_init($api_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($paymentData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $paymentResponse = json_decode($response, true);

            if ($paymentResponse && $paymentResponse['status'] === 'Success') {
                $stmt = $pdo->prepare("DELETE FROM carritos WHERE usuario_id = :cart_id");
                $stmt->execute([':cart_id' => $cart_id]);

                foreach ($carrito as $producto) {
                    $stmt = $pdo->prepare("UPDATE productos SET stock = stock - :cantidad WHERE nombre = :producto");
                    $stmt->execute([
                        ':cantidad' => $producto['cantidad'],
                        ':producto' => $producto['producto']
                    ]);
                }

                echo json_encode([
                    'status' => 'ok',
                    'message' => 'Compra finalizada con éxito. ¡Gracias por tu pedido!'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error en el pago.'
                ]);
            }
            exit;

        } catch (PDOException $e) {
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
  <link rel="stylesheet" href="../elementos/css/css_clientes.css">
  <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <title>Harvey's | Plataforma de Pago Ficticia</title>
</head>
<body>
  <h2>Plataforma de Pago Ficticia</h2>
  <form action="finalizar_compra.php" method="POST">
    <h3>Datos de Pago</h3>
    <label for="card_holder">Nombre en la tarjeta:</label>
    <input type="text" id="card_holder" name="card_holder" required>

    <label for="card_number">Número de tarjeta:</label>
    <input type="text" id="card_number" name="card_number" required>

    <label for="expiry">Fecha de expiración (MM/AA):</label>
    <input type="text" id="expiry" name="expiry" required>

    <label for="cvv">CVV:</label>
    <input type="text" id="cvv" name="cvv" required>

    <input type="hidden" name="payment_submission" value="1">
    <button type="submit">Pagar</button>
  </form>
  <script>
     document.addEventListener("DOMContentLoaded", function() {
        const paymentForm = document.querySelector("form");
        
        paymentForm.addEventListener("submit", function(event) {
            event.preventDefault();

            const formData = new FormData(paymentForm);

            fetch("finalizar_compra.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "ok") {
                    alert(data.message); 
                    fetch("verificar_sesion.php") 
                        .then(response => response.json())
                        .then(sessionData => {
                            window.location.href = "/Harvey-s/layout/home.php";
                        });
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error en la solicitud:", error);
                    alert("Ocurrió un error al procesar el pago.");
                });
            });
        });
    </script>
</body>
</html>
