<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            $stmt = $pdo->prepare("DELETE FROM carritos WHERE usuario_id = :cart_id");
            $stmt->execute([':cart_id' => $cart_id]);

            echo json_encode([
                'status'  => 'ok',
                'message' => 'Compra finalizada. Tu pedido se ha procesado correctamente.'
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit; 
    }

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
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    $totalProductos = 0;
    $totalPrecio = 0;
    foreach ($carrito as $producto) {
        $totalProductos += $producto['cantidad'];
        $totalPrecio += $producto['precio'] * $producto['cantidad'];
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
  <title>Harvey's | Detalle de la compra</title>
</head>
<body>
  <?php 
      include '../layout/header.php';
      include '../divs/div_login.php';
      include '../divs/div_registro.php';
      include '../divs/div_secciones.php';
      include '../divs/div_empleados.php';
  ?>

  <h2>Detalle del Carrito</h2>

  <table>
      <tr>
          <th>Producto</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Total</th>
          <th>Acciones</th>
      </tr>
      <?php foreach ($carrito as $producto): ?>
      <tr>
          <td><?php echo htmlspecialchars($producto['producto']); ?></td>
          <td><?php echo number_format($producto['precio'], 2); ?>€</td>
          <td>
              <input type="number" value="<?php echo $producto['cantidad']; ?>" min="1" 
                     onchange="actualizarCantidad('<?php echo htmlspecialchars($producto['producto']); ?>', this.value)"
                     class="custom-number">
          </td>
          <td><?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?>€</td>
          <td>
              <button onclick="eliminarProducto('<?php echo htmlspecialchars($producto['producto']); ?>')">
                  <i class="fas fa-trash"></i> Eliminar
              </button>
          </td>
      </tr>
      <?php endforeach; ?>
  </table>

  <p>Total productos: <span id="total-productos"><?php echo $totalProductos; ?></span></p>
  <p>Total precio: <span id="total-precio"><?php echo number_format($totalPrecio, 2); ?>€</span></p>

  <button onclick="finalizarCompra()">Finalizar compra</button>

  <?php include '../layout/footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <script src="/Harvey-s/elementos/scripts/scripts_home.js"></script>
  <script src="/Harvey-s/elementos/busqueda/script_buscar_categoria.js"></script>
  <script>
    function actualizarCantidad(producto, nuevaCantidad) {
        fetch('../secciones/carrito/actualizar_carrito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre: producto, cantidad: nuevaCantidad })
        }).then(() => location.reload());
    }

    function eliminarProducto(producto) {
        fetch('../secciones/carrito/eliminar_carrito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre: producto })
        }).then(() => location.reload());
    }

    function finalizarCompra() {
        window.location.href = '../secciones/finalizar_compra.php';
    }

  </script>
</body>
</html>
