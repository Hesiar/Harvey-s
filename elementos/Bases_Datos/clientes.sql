CREATE DATABASE IF NOT EXISTS harveys_DB;

USE harveys_DB;

CREATE TABLE IF NOT EXISTS puestos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    salario_base DECIMAL(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    clave VARCHAR(255) NULL,
    telefono VARCHAR(15),
    direccion VARCHAR(255),
    ciudad VARCHAR(50),
    provincia VARCHAR(50),
    codigo_postal VARCHAR(10),
    fecha_contratacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    puesto_id INT,
    antiguedad INT DEFAULT NULL,
    dni VARCHAR(9) NOT NULL UNIQUE,
    usuario VARCHAR(9) NOT NULL,
    FOREIGN KEY (puesto_id) REFERENCES puestos(id)
);

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasenia VARCHAR(255) NULL,
    telefono VARCHAR(15),
    direccion VARCHAR(255),
    ciudad VARCHAR(50),
    provincia VARCHAR(50),
    codigo_postal VARCHAR(10),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    empleado_id INT DEFAULT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(15),
    direccion VARCHAR(255),
    ciudad VARCHAR(50),
    provincia VARCHAR(50),
    codigo_postal VARCHAR(10),
    email VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    categoria VARCHAR(50),
    proveedor_id INT,
    imagen VARCHAR(1024),
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT DEFAULT NULL,
    total DECIMAL(10,2) DEFAULT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS detalle_ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TRIGGER actualizar_total_venta 
AFTER INSERT ON detalle_ventas 
FOR EACH ROW 
UPDATE ventas 
SET total = (
    SELECT SUM(subtotal) 
    FROM detalle_ventas 
    WHERE venta_id = NEW.venta_id
)
WHERE id = NEW.venta_id;

CREATE INDEX idx_empleados_email ON empleados(email);
CREATE INDEX idx_clientes_email ON clientes(email);
CREATE INDEX idx_productos_nombre ON productos(nombre);
CREATE INDEX idx_ventas_fecha ON ventas(fecha);
CREATE INDEX idx_ventas_cliente ON ventas(cliente_id);
CREATE INDEX idx_ventas_producto ON ventas(producto_id);

INSERT INTO puestos (nombre, descripcion, salario_base) VALUES
    ('Gerente', 'Supervisa todas las operaciones del hipermercado.', 2500.00),
    ('Cajero', 'Encargado de cobrar a los clientes en las cajas.', 1500.00),
    ('Reponedor', 'Se encarga de abastecer los estantes de productos.', 1500.00),
    ('Jefe de sección', 'Coordina empleados en un área específica.', 2000.00),
    ('Operario de almacén', 'Gestiona inventario y almacén.', 1600.00),
    ('Marketing y publicidad', 'Encargado de promociones y campañas.', 1800.00),
    ('Administrativo', 'Maneja la documentación y atención al cliente.', 1800.00),
    ('Técnico en mantenimiento', 'Encargado de mantenimiento de equipos.', 1500.00);

-- TODO: En clientes y empleados: Al insertar valores de provincia, hacer un update. Ahora mismo inserta CCAA en vez de provincia.

INSERT INTO empleados (nombre, apellido, email, clave, telefono, direccion, ciudad, provincia, codigo_postal, fecha_contratacion, puesto_id) VALUES
    ('Elena', 'Santos', 'esantos@gmail.com', NULL, '555-2001', 'Calle de la Esperanza 12', 'Madrid', 'Madrid', '28015', '2019-06-10', 1),
    ('Roberto', 'López', 'rlopez@gmail.com', NULL, '555-2002', 'Avenida de la Ciencia 34', 'Barcelona', 'Cataluña', '08015', '2021-02-20', 2),
    ('Patricia', 'Ruiz', 'pruiz@gmail.com', NULL, '555-2003', 'Paseo del Río 56', 'Sevilla', 'Andalucía', '41015', '2017-11-30', 3),
    ('Antonio', 'Ramos', 'aramos@gmail.com', NULL, '555-2004', 'Paseo del Río 89', 'Sevilla', 'Andalucía', '41015', '2020-06-12', 4),
    ('Raquel', 'Santos', 'rsantos@gmail.com', NULL, '555-2005', 'Calle Mayor 134', 'Murcia', 'Murcia', '30025', '2019-02-27', 6),
    ('Jorge', 'Domínguez', 'jdominguez@gmail.com', NULL, '555-2006', 'Paseo de la Luz 145', 'Santander', 'Cantabria', '39025', '2016-09-30', 7),
    ('Eva', 'Ortega', 'eortega@gmail.com', NULL, '555-2007', 'Calle de la Luna 156', 'Alicante', 'Comunidad Valenciana', '03025', '2020-11-18', 8),
    ('Samuel', 'Cano', 'scano@gmail.com', NULL, '555-2008', 'Calle del Sol 167', 'Oviedo', 'Asturias', '33025', '2018-07-22', 2),
    ('Carlos', 'Moreno', 'cmoreno@gmail.com', NULL, '555-8888', 'Calle de la Esperanza 12', 'Madrid', 'Madrid', '28015', '2019-06-10', 1),
    ('Beatriz', 'Cano', 'bcano@gmail.com', NULL, '555-9999', 'Calle de la Ciencia 34', 'Barcelona', 'Cataluña', '08015', '2021-02-20', 2),
    ('Fernando', 'Díaz', 'fdiaz@gmail.com', NULL, '555-0001', 'Paseo del Río 56', 'Sevilla', 'Andalucía', '41015', '2017-11-30', 3),
    ('Ana', 'Martínez', 'amartinez@gmail.com', NULL, '555-0002', 'Calle del Mar 78', 'Valencia', 'Comunidad Valenciana', '46015', '2020-04-15', 4),
    ('Lorena', 'González', 'lgonzalez@gmail.com', NULL, '555-0003', 'Camino Viejo 90', 'Bilbao', 'País Vasco', '48015', '2018-09-12', 6),
    ('Javier', 'López', 'jlopez@gmail.com', NULL, '555-9991', 'Calle de los Árboles 101', 'Madrid', 'Madrid', '28015', '2018-06-10', 1),
    ('Beatriz', 'Fernández', 'bfernandez@gmail.com', NULL, '555-9992', 'Avenida del Sol 54', 'Barcelona', 'Cataluña', '08015', '2021-08-20', 2),
    ('Ricardo', 'Martínez', 'rmartinez@gmail.com', NULL, '555-9993', 'Paseo del Río 89', 'Sevilla', 'Andalucía', '41015', '2019-09-15', 3),
    ('Laura', 'Navarro', 'lnavarro@gmail.com', NULL, '555-9994', 'Calle del Comercio 78', 'Valencia', 'Comunidad Valenciana', '46015', '2022-04-30', 6),
    ('Manuel', 'Díaz', 'mdiaz@gmail.com', NULL, '555-9995', 'Camino Viejo 120', 'Bilbao', 'País Vasco', '48015', '2020-11-25', 8);

UPDATE empleados SET antiguedad = TIMESTAMPDIFF(YEAR, fecha_contratacion, NOW());

INSERT INTO clientes (nombre, apellido, email, contrasenia, telefono, direccion, ciudad, provincia, codigo_postal, fecha_registro, empleado_id) VALUES
    ('Juan', 'Pérez', 'jperez@gmail.com', NULL, '555-1234', 'Calle Falsa 123', 'Madrid', 'Madrid', '28001', '2023-01-01', NULL),
    ('María', 'López', 'mlopez@gmail.com', NULL,'555-4321', 'Calle Verdadera 456', 'Barcelona', 'Cataluña', '08002', '2023-01-02', 1),
    ('Pedro', 'García', 'pgarcia@gmail.com', NULL, '555-8765', 'Calle Imaginaria 789', 'Valencia', 'Comunidad Valenciana', '46001', '2023-01-03', 1),
    ('Laura', 'Fernández', 'lfernandez@gmail.com', NULL, '555-5678', 'Calle de la Paz 101', 'Sevilla', 'Andalucía', '41001', '2023-01-04', NULL),
    ('Carlos', 'Hernández', 'chernandez@gmail.com', NULL, '555-6789', 'Calle del Sol 202', 'Madrid', 'Madrid', '28002', '2023-01-05', 1),
    ('Isabel', 'Ruiz', 'iruiz@gmail.com', NULL, '555-7890', 'Paseo del Río 12', 'Granada', 'Andalucía', '18001', '2023-01-08', NULL),
    ('Javier', 'Ortega', 'jortega@gmail.com', NULL, '555-8901', 'Camino del Bosque 34', 'Bilbao', 'País Vasco', '48001', '2023-01-09', 1),
    ('Lucía', 'Navarro', 'lnavarro@gmail.com', NULL, '555-9012', 'Calle Real 56', 'Zaragoza', 'Aragón', '50001', '2023-01-10', NULL),
    ('Antonio', 'Sánchez', 'asanchez@gmail.com', NULL, '555-3333', 'Calle Mayor 45', 'Madrid', 'Madrid', '28012', '2023-01-11', 2),
    ('Laura', 'Méndez', 'lmendez@gmail.com', NULL, '555-4444', 'Avenida del Sol 89', 'Barcelona', 'Cataluña', '08012', '2023-02-12', NULL),
    ('Jorge', 'Herrera', 'jherrera@gmail.com', NULL, '555-5555', 'Paseo del Río 123', 'Valencia', 'Comunidad Valenciana', '46012', '2023-03-15', 4),
    ('Elena', 'Torres', 'etorres@gmail.com', NULL, '555-6666', 'Calle Luna 67', 'Sevilla', 'Andalucía', '41012', '2023-04-20', NULL),
    ('Pablo', 'Navarro', 'pnavarro@gmail.com', NULL, '555-7777', 'Calle Jardín 78', 'Bilbao', 'País Vasco', '48012', '2023-05-25', 6),
    ('Carlos', 'Serrano', 'cserrano@gmail.com', NULL, '555-8881', 'Calle Principal 99', 'Madrid', 'Madrid', '28003', '2023-06-05', NULL),
    ('Raúl', 'Vega', 'rvega@gmail.com', NULL, '555-8882', 'Avenida del Parque 45', 'Barcelona', 'Cataluña', '08003', '2023-06-06', 2),
    ('Sofía', 'Morales', 'smorales@gmail.com', NULL, '555-8883', 'Plaza Mayor 12', 'Bilbao', 'País Vasco', '48003', '2023-06-07', NULL),
    ('Clara', 'Jiménez', 'cjimenez@gmail.com', NULL, '555-8884', 'Paseo de la Estación 58', 'Granada', 'Andalucía', '18003', '2023-06-08', 4),
    ('Hugo', 'Castaño', 'hcastano@gmail.com', NULL, '555-8885', 'Calle de la Industria 77', 'Valencia', 'Comunidad Valenciana', '46003', '2023-06-09', NULL),
    ('Adrián', 'Soler', 'asoler@gmail.com', NULL, '555-1235', 'Calle del Bosque 76', 'Madrid', 'Madrid', '28004', '2023-06-10', NULL),
    ('Paula', 'Molina', 'pmolina@gmail.com', NULL, '555-1236', 'Avenida del Lago 22', 'Barcelona', 'Cataluña', '08004', '2023-06-11', NULL),
    ('Gonzalo', 'López', 'glopez@gmail.com', NULL, '555-1237', 'Paseo del Mar 89', 'Valencia', 'Comunidad Valenciana', '46004', '2023-06-12', NULL),
    ('Carmen', 'Rosales', 'crosales@gmail.com', NULL, '555-1238', 'Plaza de la Luna 33', 'Sevilla', 'Andalucía', '41004', '2023-06-13', NULL),
    ('Vicente', 'Ruiz', 'vruiz@gmail.com', NULL, '555-1239', 'Camino del Sol 45', 'Bilbao', 'País Vasco', '48004', '2023-06-14', NULL),
    ('Lourdes', 'Castro', 'lcastro@gmail.com', NULL, '555-1240', 'Calle Real 101', 'Granada', 'Andalucía', '18004', '2023-06-15', NULL),
    ('Daniel', 'Gómez', 'dgomez@gmail.com', NULL, '555-1241', 'Calle Mayor 77', 'Zaragoza', 'Aragón', '50004', '2023-06-16', NULL),
    ('Verónica', 'Navarro', 'vnavarro@gmail.com', NULL, '555-1242', 'Paseo de la Estación 44', 'Santander', 'Cantabria', '39004', '2023-06-17', NULL),
    ('Ramiro', 'Ortega', 'rortega@gmail.com', NULL, '555-1243', 'Avenida de los Olivos 61', 'Murcia', 'Murcia', '30004', '2023-06-18', NULL),
    ('Julia', 'Díaz', 'jdiaz@gmail.com', NULL, '555-1244', 'Calle Nueva 88', 'Oviedo', 'Asturias', '33004', '2023-06-19', NULL);

INSERT INTO proveedores (nombre, contacto, telefono, direccion, ciudad, provincia, codigo_postal, email) VALUES
    ('Distribuciones Alimarket', 'José Fernández', '555-2001', 'Av. Comercial 78', 'Madrid', 'Madrid', '28020', 'contacto@alimarket.com'),
    ('AgroTienda Distribución', 'Luisa Gómez', '555-2010', 'Calle Principal 78', 'Zaragoza', 'Aragón', '50002', 'info@agrotienda.com'),
    ('EcoMarket Distribución', 'Roberto Fernández', '555-1010', 'Calle Comercial 78', 'Madrid', 'Madrid', '28020', 'ventas@ecomarket.com'),
    ('SuperFresh S.A.', 'Luis Rodríguez', '555-1012', 'Paseo Gourmet 98', 'Valencia', 'Comunidad Valenciana', '46020', 'compras@superfresh.com'),
    ('FreshMarket Distribución', 'Lucía Rodríguez', '555-2013', 'Calle Gourmet 45', 'Madrid', 'Madrid', '28020', 'ventas@freshmarket.com'),
    ('SuperFoods S.L.', 'Raúl Domínguez', '555-2015', 'Plaza Comercial 78', 'Valencia', 'Comunidad Valenciana', '46020', 'compras@superfoods.com');

INSERT INTO productos (nombre, descripcion, precio, stock, categoria, proveedor_id, imagen) VALUES
    ('Leche Entera 1L', 'Leche entera de calidad superior.', 1.20, 500, 'Lácteos', 1, 'https://img.freepik.com/free-photo/close-up-futuristic-soft-drink_23-2151282007.jpg?t=st=1746256562~exp=1746260162~hmac=e4c0737b1ad7535731c3f272e163baf07e6b4411ff0d0a816dac454a0aafb90d&w=740'),
    ('Yogur Natural 500g', 'Yogur cremoso sin azúcares añadidos.', 2.99, 350, 'Lácteos', 1, 'https://img.freepik.com/free-vector/realistic-set-two-blank-pots-with-open-foil-lids-plastic-containers-jars-with-spoons_1441-1852.jpg?t=st=1746256672~exp=1746260272~hmac=c7f4e5c0f59ccb1c12beb91efce408f7cde221989f3e3b5eb8aad03e21e1296b&w=740'),
    ('Queso Manchego Curado 250g', 'Queso de oveja con maduración de 12 meses.', 5.50, 200, 'Lácteos', 1, 'https://img.freepik.com/free-photo/cute-cheese-near-basket_23-2147930106.jpg?t=st=1746256762~exp=1746260362~hmac=1f0b7386e91fab2a77b6b165d503e1306909204ed432e9484cce2d31156d7806&w=740'),
    ('Tomates 1kg', 'Tomates frescos de huerta ecológica.', 2.20, 250, 'Frutas y Verduras', 3, 'https://img.freepik.com/premium-photo/3-red-tomatoes-white-background_1231055-31.jpg?w=826'),
    ('Cebollas 1kg', 'Cebollas doradas con sabor intenso.', 1.50, 300, 'Frutas y Verduras', 3, 'https://img.freepik.com/premium-photo/fresh-bulbs-onion-white-background_461160-11094.jpg?w=740'),
    ('Fresas 500g', 'Fresas frescas de cultivo ecológico.', 3.99, 200, 'Frutas y Verduras', 3, 'https://img.freepik.com/free-photo/closeup-shot-fresh-ripe-strawberries-isolated-white-surface_181624-54939.jpg?t=st=1746256815~exp=1746260415~hmac=9da6c2556771cbc47a43601ea185ef78c928cbc2800b0989230025c4313e5a10&w=740'),
    ('Pan Integral 500g', 'Pan artesanal integral.', 2.50, 300, 'Panadería', 2, 'https://img.freepik.com/free-photo/high-angle-bread-white-cutting-board_23-2148544713.jpg?t=st=1746257012~exp=1746260612~hmac=f58964649f77234ac206fec5624af71621a21960d7f8cd8ca670390f1ae2f66f&w=740'),
    ('Cereales Integral 500g', 'Cereales de avena y frutos secos.', 3.50, 400, 'Panadería', 2, 'https://img.freepik.com/free-photo/composition-delicious-ingredients-kitchen_23-2148882479.jpg?t=st=1746257179~exp=1746260779~hmac=ff153d0e7d5958eebd0076e5adbfa86d9c5c59b99cdab9ab763c4f3519b01d73&w=740'),
    ('Galletas Integrales 500g', 'Galletas sin azúcar añadida.', 4.20, 180, 'Panadería', 2, 'https://img.freepik.com/free-photo/high-view-delicious-cookies-cloth-basket_23-2148432359.jpg?t=st=1746257280~exp=1746260880~hmac=30a98be0e36984a83e964206db028989cb036d15e92ca637e4414373fde64613&w=740'),
    ('Manzanas Fuji 1kg', 'Manzanas frescas de cultivo ecológico.', 2.99, 250, 'Frutas y Verduras', 3, 'https://img.freepik.com/free-photo/bowl-with-apples_1220-198.jpg?t=st=1746257361~exp=1746260961~hmac=e618b91bb97ba9e6cafbd28025f3f5ac6426137a8f592c461037d2e254e2ffab&w=740'),
    ('Zanahorias 1kg', 'Zanahorias frescas de la huerta.', 1.80, 300, 'Frutas y Verduras', 3, 'https://img.freepik.com/free-photo/baby-carrots_1339-7954.jpg?t=st=1746257412~exp=1746261012~hmac=0e671caa8eb238f9d05f419cc72728a0953bec228ee68f806a1390c7f4e4c834&w=740'),
    ('Plátanos 1kg', 'Plátanos maduros y dulces.', 2.50, 400, 'Frutas y Verduras', 3, 'https://img.freepik.com/free-photo/bananas-white-background_1187-1671.jpg?t=st=1746257443~exp=1746261043~hmac=742ac9048c0cab58cf6d02862b19a6a33218f47622a1ea5f4ba8c793bae1b6e7&w=740'),
    ('Jamón Serrano 500g', 'Jamón curado con sabor intenso.', 8.99, 150, 'Carnes y Embutidos', 4, 'https://img.freepik.com/free-photo/spanish-serrano-ham-cutting-board_123827-21522.jpg?t=st=1746257508~exp=1746261108~hmac=1e245316fd97aa99683a8efadf3396c1ee7416d3055148896008d76442287c67&w=740'),
    ('Salchichón Ibérico 300g', 'Embutido tradicional de cerdo ibérico.', 6.50, 120, 'Carnes y Embutidos', 4, 'https://img.freepik.com/premium-photo/close-up-delicious-salami-with-parsley_23-2148439491.jpg?w=740'),
    ('Pechuga de Pollo 1kg', 'Carne de pollo fresca y lista para cocinar.', 5.99, 180, 'Carnes y Embutidos', 4, 'https://img.freepik.com/free-photo/raw-fresh-chicken-meat_74190-2359.jpg?t=st=1746257641~exp=1746261241~hmac=a31cb542b58fa3bc1279bbcb6b70b3bdef194f93b5e2af88cb4e08610d9ab3d9&w=740'),
    ('Aceite de Oliva Extra Virgen 1L', 'Aceite de oliva 100% natural.', 6.50, 150, 'Conservas', 5, 'https://img.freepik.com/free-photo/olive-oil-with-olives-background_62951-15.jpg?t=st=1746257787~exp=1746261387~hmac=fe44d244ed08171491b3962c0ebee92295110d372c895ea2701b55b1e099c83c&w=740'),
    ('Arroz Integral 1kg', 'Arroz integral de cultivo ecológico.', 3.00, 250, 'Conservas', 5, 'https://img.freepik.com/premium-photo/red-rice-white-b_62856-3797.jpg?w=900'),
    ('Pack de 12 Botellas de Agua 0.5L', 'Agua mineral natural de manantial.', 1.99, 500, 'Bebidas', 5, 'https://img.freepik.com/premium-photo/close-up-plastic-bottles-with-drinking-water_380164-293982.jpg?w=740'),
    ('Chocolate Negro 70% Cacao', 'Chocolate premium con alto contenido de cacao.', 3.80, 250, 'Dulces', 6, 'https://img.freepik.com/free-photo/stacked-chocolate-pieces-close-up_23-2148469899.jpg?t=st=1746259193~exp=1746262793~hmac=a9bc4acf51230bd8cffc49b9c8783c110b8d96422e98d4a83a681518efd72284&w=740'),
    ('Bolsa de Frutos Secos 500g', 'Mix de almendras, nueces y avellanas.', 6.50, 180, 'Dulces', 6, 'https://img.freepik.com/premium-photo/roasted-almonds-cardboard-kraft-bag-delicious-peanuts_173815-43959.jpg?w=740'),
    ('Mantequilla Tradicional 250g', 'Mantequilla pura sin aditivos.', 3.50, 150, 'Lácteos', 1, 'https://img.freepik.com/premium-photo/butter-wooden-holder-surrounded-by-bread-milk-wooden-table-closeup_392895-189654.jpg?w=740'),
    ('Queso Gouda 400g', 'Queso semicurado con sabor suave.', 4.99, 180, 'Lácteos', 1, 'https://img.freepik.com/free-photo/close-up-chef-cutting-cheese_23-2148471865.jpg?t=st=1746260011~exp=1746263611~hmac=ab0ce7cd6f5c00d54f0a2598822ed1286eb79e5a29c6fbab1dbeb62be9b17e2d&w=740'),
    ('Chorizo Ibérico 300g', 'Embutido típico con especias.', 5.99, 150, 'Carnes y Embutidos', 4, 'https://img.freepik.com/free-photo/delicious-traditional-chorizo-composition_23-2148980278.jpg?t=st=1746260057~exp=1746263657~hmac=1cb72e21b5f34f2a04c9ae7c38996f6f2fb3b99f22bbe6cbf63e84130783d8b9&w=740'),
    ('Costillas de Cerdo 1kg', 'Costillas frescas para asar.', 7.50, 120, 'Carnes y Embutidos', 4, 'https://img.freepik.com/premium-photo/raw-pork-ribs-white-background_51524-16991.jpg?w=740'),
    ('Ternera Fileteada 500g', 'Filetes de ternera fresca.', 9.99, 100, 'Carnes y Embutidos', 4, 'https://img.freepik.com/premium-photo/red-raw-steak-sirloin-against_538646-6553.jpg?w=826'),
    ('Croissants 6 unidades', 'Croissants mantequilla recién horneados.', 4.50, 150, 'Panadería', 2, 'https://img.freepik.com/free-photo/french-croissants-cardboard-box-cloth_23-2148432404.jpg?t=st=1746260466~exp=1746264066~hmac=07c43ee9340d69a23beefeb5d091240e50749fd8719d51b46b32781507fe7de6&w=740'),
    ('Pan de Centeno 500g', 'Pan rústico con harina de centeno.', 3.00, 200, 'Panadería', 2, 'https://img.freepik.com/free-photo/rye-sliced-bread-table_1112-1253.jpg?t=st=1746260663~exp=1746264263~hmac=d602d5406ec2da2880f533315c703625d8fe3195fded4651b902a99824abebcd&w=740'),
    ('Miel Orgánica 500g', 'Miel pura de abejas de montaña.', 6.99, 100, 'Conservas', 5, 'https://img.freepik.com/premium-photo/honey-yellow-beekeeper-image_1118894-34.jpg?w=740'),
    ('Salsa de Tomate 750ml', 'Salsa de tomate casera con especias.', 2.99, 250, 'Conservas', 5, 'https://img.freepik.com/premium-photo/fresh-tomatoes-with-paste-juice_106006-3530.jpg?w=740'),
    ('Zumo de Naranja 1L', 'Zumo natural sin azúcares añadidos.', 3.50, 300, 'Bebidas', 5, 'https://img.freepik.com/free-photo/sliced-oranges-with-juice-glass-jar-cup_114579-11763.jpg?t=st=1746261328~exp=1746264928~hmac=7568a2066bb9d6bd393e291cd114dc0570108c45151f2630a6f62154b026ab0b&w=740'),
    ('Café Molido 250g', 'Café natural de tueste medio.', 4.99, 200, 'Bebidas', 5, 'https://img.freepik.com/free-photo/close-up-view-coffee-concept_23-2148464793.jpg?t=st=1746261373~exp=1746264973~hmac=419959354844421d65b960631ad5f3b96f8b47a0638aa126fee6809cafc2a481&w=740'),
    ('Uvas 500g', 'Uvas frescas de temporada.', 2.99, 180, 'Frutas y Verduras', 3, 'https://img.freepik.com/premium-photo/some-juicy-grapes-table_561334-1130.jpg?w=740'),
    ('Patatas 2kg', 'Patatas para todo tipo de guisos.', 3.50, 250, 'Frutas y Verduras', 3, 'https://img.freepik.com/free-photo/raw-potatoes-woven-wicker-basket-with-natural-rosemary-leaves-wooden-rustic-table_181624-47259.jpg?t=st=1746261438~exp=1746265038~hmac=b54839095aedabeed58fdec0593f7f928ae57af311e70317cb8c781bd7d3dd22&w=740'),
    ('Naranjas 1kg', 'Naranjas dulces y jugosas.', 2.80, 300, 'Frutas y Verduras', 3, 'https://img.freepik.com/free-photo/orange-juicy-ripe-circle-citrus_1172-203.jpg?t=st=1746261493~exp=1746265093~hmac=0141ed8864dc19e47c88ead60c7447b30838bf93e3a71fe7c0796d9e257555b1&w=740'),
    ('Pimientos Rojos 1kg', 'Pimientos frescos y crujientes.', 2.50, 200, 'Frutas y Verduras', 3, 'https://img.freepik.com/premium-photo/fresh-organic-bell-peppers-wooden-board_90258-702.jpg?w=740'),
    ('Cereales de Maíz 500g', 'Cereales de maíz con miel.', 2.20, 400, 'Panadería', 2, 'https://img.freepik.com/free-photo/bowl-corn-flakes-breakfast-with-milk-wooden-spoon_23-2148417360.jpg?t=st=1746261670~exp=1746265270~hmac=0f4b43a460a983c11ea58056351c476c436684dd6d56daf8b5e4f22584dd8475&w=740'),
    ('Galletas de Chocolate 300g', 'Galletas con trozos de chocolate.', 3.50, 250, 'Dulces', 6, 'https://img.freepik.com/premium-photo/delicious-chocolate-cookies-wooden-table_434193-469.jpg?w=740'),
    ('Tarta de Manzana 1kg', 'Tarta casera de manzana y canela.', 8.50, 100, 'Dulces', 6, 'https://img.freepik.com/free-photo/beautiful-delicious-warm-apple-pie_23-2151940335.jpg?t=st=1746261776~exp=1746265376~hmac=920e93334817dd907d2d671397c1fe50af5a3c2a993afb3b96362616b859d2bf&w=740'),
    ('Helado de Vainilla 1L', 'Helado cremoso de vainilla natural.', 4.99, 150, 'Dulces', 6, 'https://img.freepik.com/premium-photo/balls-delicious-vanilla-ice-cream-isolated-white_392895-285986.jpg?w=740'),
    ('Natillas de Vainilla 125g', 'Postre lácteo con auténtica vainilla.', 3.20, 200, 'Lácteos', 1, 'https://img.freepik.com/premium-photo/pastry-cream-bowl-wooden-table_123827-19069.jpg?w=740'),
    ('Vino Tinto Crianza 750ml', 'Vino crianza con denominación de origen.', 9.99, 100, 'Bebidas', 5, 'https://img.freepik.com/free-photo/wine-glass-bottle-barrel_23-2148214947.jpg?t=st=1746262226~exp=1746265826~hmac=b51f27243f84ec1c99ccea76a371356db81d29bbd226aa99ee2640ae513f486c&w=740'),
    ('Cerveza Artesanal 330ml', 'Cerveza de elaboración artesanal.', 2.50, 200, 'Bebidas', 5, 'https://img.freepik.com/free-photo/glass-beer-foam-with-brown-bottles-beer-wooden-table_23-2148215840.jpg?t=st=1746262373~exp=1746265973~hmac=6c2a13e62547aafe47a44ea3f37098b996bba23b41da37c144f1276e7e259084&w=740');

INSERT INTO ventas (cliente_id) VALUES 
    (1),
    (2),
    (3),
    (4),
    (5),
    (6),
    (7),
    (8),
    (9),
    (10),
    (11),
    (12),
    (13),
    (14),
    (15);

INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, subtotal) VALUES
    (1, 3, 2, 11.00),
    (1, 5, 1, 5.50),
    (1, 10, 3, 8.99),
    (2, 1, 5, 6.00),
    (2, 4, 2, 10.00),
    (2, 7, 1, 3.50),
    (3, 2, 3, 8.97),
    (3, 6, 1, 4.20),
    (4, 8, 2, 7.00),
    (4, 9, 1, 2.50),
    (5, 11, 1, 6.50),
    (5, 12, 2, 13.00),
    (6, 13, 3, 11.99),
    (6, 14, 1, 4.99),
    (7, 15, 2, 9.98),
    (7, 16, 1, 3.50),
    (8, 17, 5, 15.00),
    (8, 18, 1, 4.50),
    (9,19 ,2 ,7.00),
    (9, 20, 1, 2.50),
    (10, 21, 3, 11.99),
    (10, 22, 1, 4.99),
    (11, 23, 2, 9.98),
    (11, 24, 1, 3.50),
    (12, 25, 5, 15.00),
    (12, 26, 1, 4.50),
    (13,27 ,2 ,7.00),
    (13,28 ,1 ,2.50),
    (14,29 ,3 ,11.99),
    (14,30 ,1 ,4.99),
    (15,31 ,2 ,9.98),
    (15,32 ,1 ,3.50);
