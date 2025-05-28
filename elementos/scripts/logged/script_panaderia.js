fetch('/Harvey-s/secciones/conexiones_bd_secciones/conexion_panaderia.php')
    .then(response => response.json())
    .then(productos => {
        const container = document.querySelector('.productos-container');

        productos.forEach(producto => {
            const productoHTML = `
                <div class="producto">
                    <h2>${producto.nombre}</h2>
                    <p>Precio: ${producto.precio}€</p>
                    <img src="${producto.imagen}" alt="${producto.nombre}">
                </div>
            `;
                container.innerHTML += productoHTML;
        });
    })
.catch(error => console.error('Error al cargar los productos:', error));
    