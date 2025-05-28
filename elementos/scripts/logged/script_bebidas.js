fetch('/Harvey-s/secciones/conexiones_bd_secciones/conexion_bebidas.php')
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
  console.log("Agregando al carrito:", nombre, precio, imagen); // Depuración
  let productoExistente = carrito.find(prod => prod.nombre === nombre);

  if (productoExistente) {
    productoExistente.cantidad++;
    document.getElementById(`cantidad-${nombre}`).value = productoExistente.cantidad;
  } else {
    carrito.push({ nombre, precio, cantidad: 1, imagen });
    let productoDiv = document.getElementById(`producto-${nombre}`);
    productoDiv.innerHTML = `
      <h2>${nombre}</h2>
      <p>Precio: ${precio}€</p>
      <img src="${imagen}" alt="${nombre}">
      <div class="control-cantidad">
        <button onclick="eliminarDelCarrito('${nombre}', ${precio}, '${imagen}')" class="btn-eliminar">🗑️</button>
        <input type="number" value="1" id="cantidad-${nombre}" min="0" onblur="actualizarCantidad('${nombre}', ${precio}, '${imagen}')">
        <button onclick="incrementarCantidad('${nombre}')" class="btn-aumentar">➕</button>
      </div>
    `;
  }
  actualizarCarrito();
}

function incrementarCantidad(nombre) {
  let producto = carrito.find(prod => prod.nombre === nombre);
  if (producto) {
    producto.cantidad++;
    document.getElementById(`cantidad-${nombre}`).value = producto.cantidad;
  }
  actualizarCarrito();
}

function eliminarDelCarrito(nombre, precio, imagen) {
  carrito = carrito.filter(prod => prod.nombre !== nombre);
  let productoDiv = document.getElementById(`producto-${nombre}`);
  productoDiv.innerHTML = `
    <h2>${nombre}</h2>
    <p>Precio: ${precio}€</p>
    <img src="${imagen}" alt="${nombre}">
    <button id="btn-${nombre}" onclick="agregarAlCarrito('${nombre}', ${precio}, '${imagen}')">
      Añadir al carrito
    </button>
  `;
  actualizarCarrito();
}

function actualizarCantidad(nombre, precio, imagen) {
  let nuevaCantidad = parseInt(document.getElementById(`cantidad-${nombre}`).value);
  let producto = carrito.find(prod => prod.nombre === nombre);
  if (producto) {
    if (nuevaCantidad > 0) {
      producto.cantidad = nuevaCantidad;
    } else {
      eliminarDelCarrito(nombre, precio, imagen);
    }
  }
  actualizarCarrito();
}

function actualizarCarrito() {
  let totalProductos = carrito.reduce((acc, prod) => acc + prod.cantidad, 0);
  let totalPrecio = carrito.reduce((acc, prod) => acc + prod.precio * prod.cantidad, 0);
  
  let totalProductosElem = document.getElementById("total-productos");
  let totalPrecioElem = document.getElementById("total-precio");
  if(totalProductosElem) {
    totalProductosElem.textContent = totalProductos;
  }
  if(totalPrecioElem) {
    totalPrecioElem.textContent = totalPrecio.toFixed(2) + "€";
  }
}
