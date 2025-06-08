<link rel="stylesheet" href="/Harvey-s/elementos/css/css_carrito.css">
<div class='divCarrito'>
    <label for="Cerrar" class="back-button-carrito" id="Cerrar">
        <i class="fas fa-times"></i>
    </label>
    <h2>Carrito</h2>
    <p>Total productos: <span id="total-productos">0</span></p>
    <p>Total precio: <span id="total-precio">0€</span></p>
    <button class="ver-carrito" onclick="window.location.href='../secciones/detalle_compra.php'"><i class="fas fa-shopping-cart"></i>    Ver carrito</button>
    <button class="borrar-carrito"><i class="fas fa-trash"></i>    Borrar carrito</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    $(document).ready(function(){
        $('.back-button-carrito').on('click', function(){
            $('.divCarrito').animate({right: '-320px'}, 400);
        });

        function verificarCarrito() {
            let totalProductos = parseInt($("#total-productos").text()) || 0;
            if (totalProductos === 0) {
                $(".ver-carrito").hide();
                $(".borrar-carrito").hide();
            } else {
                $(".ver-carrito").show();
                $(".borrar-carrito").show();
            }
        }

        verificarCarrito();
        $("#total-productos").on('DOMSubtreeModified', verificarCarrito);
    });

    $(".borrar-carrito").on("click", function(){
        fetch('../secciones/carrito/actualizar_carrito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cantidad: 0 }) 
        })
        .then(response => response.json())
        .then(() => {
            $("#total-productos").text("0");
            $("#total-precio").text("0€");
            localStorage.removeItem("carrito");
            verificarCarrito();
        })
        .catch(error => console.error("Error al borrar el carrito:", error));
    });


    function obtenerCarrito() {
        fetch('../secciones/carrito/obtener_carrito.php')
            .then(response => response.json())
            .then(data => {
                console.log("Carrito obtenido:", data);
                let totalProductos = data.reduce((acc, prod) => acc + prod.cantidad, 0);
                let totalPrecio = data.reduce((acc, prod) => acc + prod.cantidad * prod.precio, 0);
                document.getElementById("total-productos").textContent = totalProductos;
                document.getElementById("total-precio").textContent = totalPrecio.toFixed(2) + "€";
            })
            .catch(error => console.error("Error al obtener el carrito:", error));
        }

    document.addEventListener("DOMContentLoaded", function(){
        obtenerCarrito();
    });
</script>
