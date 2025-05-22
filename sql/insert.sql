-- Archivo: insert.sql
-- Inserta datos de prueba en las tablas Clientes y Productos

USE tienda_online;

-- -----------------------------------------------------
--                Inserción de Clientes
-- -----------------------------------------------------
INSERT INTO Clientes (Nombre, Apellidos, Correo, Telefono, Nacimiento, Contrasena, Direccion, Razon_Social, NIF) VALUES
('María', 'García López', 'maria.garcia@email.com', '600111222', '1985-03-15', '$2y$10$ejemploHashSeguro', 'Calle Primavera 23, Madrid', 'María García SL', '12345678A'),
('Juan', 'Martínez Sánchez', 'juan.martinez@email.com', '600222333', '1990-07-22', '$2y$10$ejemploHashSeguro', 'Avenida Libertad 45, Barcelona', NULL, '87654321B'),
('Ana', 'Rodríguez Fernández', 'ana.rodriguez@email.com', '600333444', '1982-11-30', '$2y$10$ejemploHashSeguro', 'Plaza Mayor 12, Valencia', 'Ana Rodríguez CB', '11223344C'),
('Carlos', 'Pérez Gómez', 'carlos.perez@email.com', '600444555', '1995-05-10', '$2y$10$ejemploHashSeguro', 'Calle Sol 67, Sevilla', NULL, '55667788D'),
('Laura', 'Sánchez Ruiz', 'laura.sanchez@email.com', '600555666', '1988-09-18', '$2y$10$ejemploHashSeguro', 'Paseo Marítimo 89, Málaga', 'Laura Sánchez e Hijos', '99887766E');

-- -----------------------------------------------------
--               Inserción de Productos
-- -----------------------------------------------------
INSERT INTO Productos (GTIN, Nombre, Stock, Precio_compra, Precio_venta, Categoria, ImagenURL) VALUES
('12345678901234', 'Smartphone X10', 50, 299.99, 399.99, 'Electrónica', 'img/productos/x10.jpg'),
('23456789012345', 'Zapatillas Running Pro', 30, 59.90, 89.99, 'Deportes', 'img/productos/running.jpg'),
('34567890123456', 'Libro: Aprende PHP en 7 días', 100, 12.50, 19.99, 'Libros', 'img/productos/php7dias.jpg'),
('45678901234567', 'Cafetera Automática', 25, 89.00, 129.99, 'Hogar', 'img/productos/cafetera.jpg'),
('56789012345678', 'Auriculares Bluetooth', 75, 35.75, 59.99, 'Electrónica', 'img/productos/auriculares.jpg');

-- -----------------------------------------------------
--               Mensaje de Confirmación
-- -----------------------------------------------------
SELECT 'Datos de ejemplo insertados correctamente' AS Mensaje;
SELECT COUNT(*) AS Total_Clientes FROM Clientes;
SELECT COUNT(*) AS Total_Productos FROM Productos;