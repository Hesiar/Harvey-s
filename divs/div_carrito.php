<div class='divCarrito'>
    <label for="Cerrar" class="back-button-carrito" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <h2>Carrito</h2>
    <p>Total productos: <span id="total-productos">0</span></p>
    <p>Total precio: <span id="total-precio">0€</span></p>
    <a href="../secciones/detalle_compra.php" class="ver-carrito">Ver carrito</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(document).ready(function(){
        $('.back-button-carrito').on('click', function(){
            $('.divCarrito').animate({right: '-320px'}, 400);
        });
    });

    let carrito = [];

    function actualizarCarrito() {
        let totalProductos = carrito.reduce((acc, prod) => acc + prod.cantidad, 0);
        let totalPrecio = carrito.reduce((acc, prod) => acc + prod.precio * prod.cantidad, 0);

        document.getElementById("total-productos").textContent = totalProductos;
        document.getElementById("total-precio").textContent = totalPrecio.toFixed(2) + "€";
    }
</script>
