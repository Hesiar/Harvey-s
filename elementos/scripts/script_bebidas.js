fetch('../secciones/conexiones_bd_secciones/conexion_bebidas.php')
  .then(response => response.json())
  .then(productos => {
    console.log("Productos recibidos:", productos); // Depuración
    const container = document.querySelector('.productos-container');
    container.innerHTML = "";
    productos.forEach(producto => {
      const productoHTML = `
        <div class="producto" id="producto-${producto.nombre}">
          <h2>${producto.nombre}</h2>
          <p>Precio: ${producto.precio}€</p>
          <img src="${producto.imagen}" alt="${producto.nombre}">
          <button id="btn-${producto.nombre}" onclick="agregarAlCarrito('${producto.nombre}', ${producto.precio}, '${producto.imagen}')">
            Añadir al carrito
          </button>
        </div>
      `;
      container.innerHTML += productoHTML;
    });
  })
  .catch(error => console.error('Error al cargar los productos:', error));

function agregarAlCarrito(nombre, precio, imagen) {
  console.log("Agregando al carrito:", nombre, precio, imagen);
  fetch('../secciones/carrito/agregar_carrito.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ nombre: nombre, precio: precio, cantidad: 1, imagen: imagen })
  })
  .then(response => response.json())
  .then(data => {
    console.log("Producto agregado:", data);
    obtenerCarrito();
    let productoDiv = document.getElementById(`producto-${nombre}`);
    productoDiv.innerHTML = `
      <h2>${nombre}</h2>
      <p>Precio: ${precio.toFixed(2)}€</p>
      <img src="${imagen}" alt="${nombre}">
      <div class="control-cantidad">
        <button onclick="eliminarDelCarrito('${nombre}', ${precio}, '${imagen}')" class="btn-eliminar">
          <i class="fas fa-trash"></i>
        </button>
        <button onclick="decrementarCantidad('${nombre}', ${precio}, '${imagen}')" class="btn-disminuir">
          <i class="fas fa-minus"></i>
        </button>
        <input type="number" value="1" id="cantidad-${nombre}" class="custom-number" min="1" 
              onblur="actualizarCantidad('${nombre}', ${precio}, '${imagen}')">
        <button onclick="incrementarCantidad('${nombre}', ${precio}, '${imagen}')" class="btn-aumentar">
          <i class="fas fa-plus"></i>
        </button>
      </div>
    `;
  })
  .catch(error => console.error('Error al agregar al carrito:', error));
}

function incrementarCantidad(nombre, precio, imagen) {
  let cantidadInput = document.getElementById(`cantidad-${nombre}`);
  let currentCantidad = parseInt(cantidadInput.value) || 1;
  let newCantidad = currentCantidad + 1;
  fetch('../secciones/carrito/actualizar_carrito.php', {
       method: 'POST',
       headers: { 'Content-Type': 'application/json' },
       body: JSON.stringify({ nombre: nombre, cantidad: newCantidad })
  })
  .then(response => response.json())
  .then(data => {
         console.log("Cantidad incrementada:", data);
         cantidadInput.value = newCantidad;
         obtenerCarrito();
  })
  .catch(error => console.error('Error al incrementar la cantidad:', error));
}

function actualizarCantidad(nombre, precio, imagen) {
  let nuevaCantidad = parseInt(document.getElementById(`cantidad-${nombre}`).value);
  if (nuevaCantidad > 0) {
    fetch('../secciones/carrito/actualizar_carrito.php', {
       method: 'POST',
       headers: { 'Content-Type': 'application/json' },
       body: JSON.stringify({ nombre: nombre, cantidad: nuevaCantidad })
    })
    .then(response => response.json())
    .then(data => {
       console.log("Cantidad actualizada:", data);
       obtenerCarrito();
    })
    .catch(error => console.error('Error al actualizar la cantidad:', error));
  } else {
    eliminarDelCarrito(nombre, precio, imagen);
  }
}

function decrementarCantidad(nombre, precio, imagen) {
  let input = document.getElementById(`cantidad-${nombre}`);
  let current = parseInt(input.value) || 1;
  
  if (current > 1) {
    let newQuantity = current - 1;
    fetch('../secciones/carrito/actualizar_carrito.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre: nombre, cantidad: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        input.value = newQuantity;
        obtenerCarrito(); 
    })
    .catch(error => console.error('Error al decrementar la cantidad:', error));
  }
}


function eliminarDelCarrito(nombre, precio, imagen) {
  fetch('../secciones/carrito/eliminar_carrito.php', {
       method: 'POST',
       headers: { 'Content-Type': 'application/json' },
       body: JSON.stringify({ nombre: nombre })
  })
  .then(response => response.json())
  .then(data => {
      console.log("Producto eliminado:", data);
      let productoDiv = document.getElementById(`producto-${nombre}`);
      productoDiv.innerHTML = `
          <h2>${nombre}</h2>
          <p>Precio: ${precio.toFixed(2)}€</p>
          <img src="${imagen}" alt="${nombre}">
          <button id="btn-${nombre}" onclick="agregarAlCarrito('${nombre}', ${precio}, '${imagen}')">
              Añadir al carrito
          </button>
      `;
      obtenerCarrito();
  })
  .catch(error => console.error('Error al eliminar el producto:', error));
}

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
