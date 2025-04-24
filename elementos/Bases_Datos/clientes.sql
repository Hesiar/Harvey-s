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
    telefono VARCHAR(15),
    direccion VARCHAR(255),
    ciudad VARCHAR(50),
    provincia VARCHAR(50),
    codigo_postal VARCHAR(10),
    fecha_contratacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    salario DECIMAL(10,2) NOT NULL,
    puesto_id INT,
    antiguedad INT GENERATED ALWAYS AS (TIMESTAMPDIFF(YEAR, fecha_contratacion, CURRENT_TIMESTAMP)) STORED,
    FOREIGN KEY (puesto_id) REFERENCES puestos(id)
);

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(15),
    direccion VARCHAR(255),
    ciudad VARCHAR(50),
    provincia VARCHAR(50),
    codigo_postal VARCHAR(10),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    empleado_id INT DEFAULT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id)
);

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    categoria VARCHAR(50),
    proveedor_id INT,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id)
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

CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT DEFAULT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
)
PARTITION BY RANGE (YEAR(fecha) * 100 + MONTH(fecha)) (
    PARTITION ventas_202401 VALUES LESS THAN (202402),
    PARTITION ventas_202402 VALUES LESS THAN (202403),
    PARTITION ventas_202403 VALUES LESS THAN (202404),
    PARTITION ventas_202404 VALUES LESS THAN (202405),
    PARTITION ventas_202405 VALUES LESS THAN (202406),
    PARTITION ventas_202406 VALUES LESS THAN (202407),
    PARTITION ventas_202407 VALUES LESS THAN (202408),
    PARTITION ventas_202408 VALUES LESS THAN (202409),
    PARTITION ventas_202409 VALUES LESS THAN (202410),
    PARTITION ventas_202410 VALUES LESS THAN (202411),
    PARTITION ventas_202411 VALUES LESS THAN (202412),
    PARTITION ventas_202412 VALUES LESS THAN (202501)
);

ALTER TABLE empleados 
ADD CONSTRAINT fk_puesto FOREIGN KEY (puesto_id) REFERENCES puestos(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE clientes 
ADD CONSTRAINT fk_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE productos 
ADD CONSTRAINT fk_proveedor FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE ventas 
ADD CONSTRAINT fk_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) 
ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT fk_producto FOREIGN KEY (producto_id) REFERENCES productos(id) 
ON DELETE CASCADE ON UPDATE CASCADE;


INSERT INTO clientes (nombre, apellido, email, telefono, direccion, ciudad, estado, codigo_postal) VALUES
    ('Juan', 'Pérez', 'jperez@gmail.com', '555-1234', 'Calle Falsa 123', 'Madrid', 'Madrid', '28001', '2023-01-01', 0),
    ('María', 'López', 'mlopez@gmail.com', '555-4321', 'Calle Verdadera 456', 'Barcelona', 'Cataluña', '08002', '2023-01-02', 1),
    ('Pedro', 'García', 'pgarcia@gmail.com', '555-8765', 'Calle Imaginaria 789', 'Valencia', 'Comunidad Valenciana', '46001', '2023-01-03', 1),
    ('Ana', 'Gómez', 'agomez@gmail.com', '555-5678', 'Avenida Siempre Viva 742', 'Barcelona', 'Cataluña', '08001', '2023-01-03', 0),
    ('Laura', 'Fernández', 'lfernandez@gmail.com', '555-6789', 'Calle de la Paz 101', 'Sevilla', 'Andalucía', '41001', '2023-01-04', 0),
    ('Carlos', 'Hernández', 'chernandez@gmail.com', '555-7890', 'Calle del Sol 202', 'Madrid', 'Madrid', '28002', '2023-01-05', 1),
    ('Sofía', 'Martínez', 'smartinez@gmail.com', '555-8901', 'Calle de la Luna 303', 'Valencia', 'Comunidad Valenciana', '46002', '2023-01-06', 0),
    ('Luis', 'Martínez', 'lmartinez@gmail.com', '555-9012', 'Calle de la Tierra 404', 'Madrid', 'Madrid', '28003', '2023-01-07', 1),
    ('Isabel', 'Ruiz', 'iruiz@gmail.com', '555-1111', 'Paseo del Río 12', 'Granada', 'Andalucía', '18001', '2023-01-08', 0),
    ('Javier', 'Ortega', 'jortega@gmail.com', '555-2222', 'Camino del Bosque 34', 'Bilbao', 'País Vasco', '48001', '2023-01-09', 1),
    ('Lucía', 'Navarro', 'lnavarro@gmail.com', '555-3333', 'Calle Real 56', 'Zaragoza', 'Aragón', '50001', '2023-01-10', 0),
    ('Andrés', 'Sánchez', 'asanchez@gmail.com', '555-4444', 'Calle Mayor 78', 'Toledo', 'Castilla-La Mancha', '45001', '2023-01-11', 1);
    ('Elena', 'Moreno', 'emoreno@gmail.com', '555-5555', 'Avenida del Mar 123', 'Málaga', 'Andalucía', '29001', '2023-01-12', 0),
    ('Manuel', 'Ramírez', 'mramirez@gmail.com', '555-6666', 'Plaza Mayor 45', 'Valladolid', 'Castilla y León', '47001', '2023-01-13', 1),
    ('Claudia', 'Torres', 'ctorres@gmail.com', '555-7777', 'Calle Nueva 67', 'Murcia', 'Murcia', '30001', '2023-01-14', 0),
    ('Raúl', 'Vega', 'rvega@gmail.com', '555-8888', 'Camino Viejo 89', 'A Coruña', 'Galicia', '15001', '2023-01-15', 0),
    ('Nuria', 'Silva', 'nsilva@gmail.com', '555-9999', 'Callejón del Agua 101', 'Santander', 'Cantabria', '39001', '2023-01-16', 1),
    ('Álvaro', 'Díaz', 'adiaz@gmail.com', '555-0001', 'Paseo de la Castellana 202', 'Madrid', 'Madrid', '28046', '2023-01-17', 0),
    ('Marta', 'Reyes', 'mreyes@gmail.com', '555-0002', 'Avenida de América 303', 'Madrid', 'Madrid', '28028', '2023-01-18', 0),
    ('David', 'Castro', 'dcastro@gmail.com', '555-0003', 'Calle del Pintor 404', 'Granada', 'Andalucía', '18002', '2023-01-19', 1),
    ('Patricia', 'Iglesias', 'piglesias@gmail.com', '555-0004', 'Calle Jardín 505', 'Valencia', 'Comunidad Valenciana', '46003', '2023-01-20', 0),
    ('Sergio', 'Alonso', 'salonso@gmail.com', '555-0005', 'Camino del Lago 606', 'Zaragoza', 'Aragón', '50002', '2023-01-21', 1);
    ('Beatriz', 'Núñez', 'bnunez@gmail.com', '555-0006', 'Calle del Olivo 707', 'Cádiz', 'Andalucía', '11001', '2023-01-22', 0),
    ('Hugo', 'Santos', 'hsantos@gmail.com', '555-0007', 'Avenida del Puerto 808', 'Valencia', 'Comunidad Valenciana', '46004', '2023-01-23', 1),
    ('Teresa', 'Paredes', 'tparedes@gmail.com', '555-0008', 'Plaza de la Luna 909', 'Salamanca', 'Castilla y León', '37001', '2023-01-24', 0),
    ('Joaquín', 'Delgado', 'jdelgado@gmail.com', '555-0009', 'Calle del Río 1001', 'Logroño', 'La Rioja', '26001', '2023-01-25', 1),
    ('Natalia', 'Vargas', 'nvargas@gmail.com', '555-0010', 'Calle Prado 1102', 'Toledo', 'Castilla-La Mancha', '45002', '2023-01-26', 0),
    ('Iván', 'Ríos', 'irios@gmail.com', '555-0011', 'Avenida del Sur 1203', 'Sevilla', 'Andalucía', '41002', '2023-01-27', 1),
    ('Rosa', 'Gil', 'rgil@gmail.com', '555-0012', 'Paseo del Norte 1304', 'Pamplona', 'Navarra', '31001', '2023-01-28', 0),
    ('Óscar', 'Carmona', 'ocarmona@gmail.com', '555-0013', 'Calle Colón 1405', 'Vigo', 'Galicia', '36201', '2023-01-29', 1),
    ('Inés', 'Morales', 'imorales@gmail.com', '555-0014', 'Calle de las Flores 1506', 'Alicante', 'Comunidad Valenciana', '03001', '2023-01-30', 0),
    ('Pablo', 'Luna', 'pluna@gmail.com', '555-0015', 'Camino del Este 1607', 'Oviedo', 'Asturias', '33001', '2023-01-31', 1);

