<?php
    session_start();
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    $carrito = $_SESSION['carrito'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../elementos/css/css_clientes.css">
    <link rel="icon" href="../elementos/pics/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <title>Harvey's | Detalle de la Compra</title>
</head>
<body>
    <?php 
        include '../layout/header.php';
        include '../divs/div_login.php';
        include '../divs/div_registro.php';
        include '../divs/div_carrito.php';
        include '../divs/div_secciones.php';
        include '../divs/div_empleados.php';
        include '../layout/footer.php';
    ?>
    <h2>Detalle del carrito</h2>

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
            <td><?php echo $producto['nombre']; ?></td>
            <td><?php echo $producto['precio']; ?>€</td>
            <td>
                <input type="number" value="<?php echo $producto['cantidad']; ?>" min="1"
                       onchange="actualizarCantidad('<?php echo $producto['nombre']; ?>', this.value)">
            </td>
            <td><?php echo $producto['precio'] * $producto['cantidad']; ?>€</td>
            <td>
                <button onclick="eliminarProducto('<?php echo $producto['nombre']; ?>')">🗑️ Eliminar</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p>Total productos: <span id="total-productos">0</span></p>
    <p>Total precio: <span id="total-precio">0€</span></p>

    <button onclick="finalizarCompra()">Finalizar compra</button>

    <script>
        function actualizarCantidad(nombre, nuevaCantidad) {
            fetch('../secciones/carrito/actualizar_carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre, cantidad: nuevaCantidad })
            }).then(() => location.reload());
        }

        function eliminarProducto(nombre) {
            fetch('../secciones/carrito/eliminar_carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre })
            }).then(() => location.reload());
        }

        function finalizarCompra() {
            alert('Compra finalizada. Procesando pedido...');
        }
    </script>
</body>
</html>
