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
    ('Laura', 'Sánchez Ruiz', 'laura.sanchez@email.com', '600555666', '1988-09-18', '$2y$10$ejemploHashSeguro', 'Paseo Marítimo 89, Málaga', 'Laura Sánchez e Hijos', '99887766E'),
    ('prueba', 'prueba', 'prueba@gmail.com', '600111222', '1988-09-18', 'pwd', 'Paseo Marítimo 89, Málaga', 'Pruebas y Pruebas SL', '99667766E');

-- -----------------------------------------------------
--               Inserción de Productos
-- -----------------------------------------------------
INSERT INTO Productos (GTIN, Nombre, Contenido, Edicion, Rarezas, Stock, Precio_Compra, Precio_Venta, Categoria, ImagenURL) VALUES
    ('7891234560123', 'Pack de cartas: Chispas Fulgurantes', '10 cartas de juego', 'Pokémon Escarlata y Púrpura', 'Incluye posibilidad de cartas holográficas', 50, 2.50, 5.00, 'Boosters', 'imgs/chispas.jpg'),
    ('7891234560124', 'Pack de cartas: Silver Tempest', '10 cartas de juego', 'Espada y Escudo', '1 carta holográfica garantizada', 45, 2.50, 5.00, 'Boosters', 'imgs/silvertempest.jpeg'),
    ('7891234560125', 'Charizard PSA 10', '1 carta gradada', 'Base Set', 'Holográfica First Edition', 1, 8000.00, 10000.00, 'Cartas Gradadas', 'imgs/charizard.jpeg'),
    ('7891234560126', 'Lote de sobres: Brecha Paradójica', '36 sobres sellados', 'Espada y Escudo', NULL, 30, 15.00, 30.00, 'Lotes', 'imgs/brechaparadojica.jpeg'),
    ('7891234560127', 'Lote de paquetes: Charizard', '24 sobres sellados', 'Evoluciones', 'Posibilidad de Charizard holográfico', 25, 15.00, 30.00, 'Lotes', 'imgs/lotecharizard.jpeg'),
    ('7891234560128', 'Sobre de cartas: Journey Together', '5 cartas de juego', 'Sol y Luna', NULL, 40, 2.50, 5.00, 'Boosters', 'imgs/journeytogether.jpeg'),
    ('7891234560129', 'Fundas para cartas (x100)', NULL, NULL, NULL, 200, 0.50, 1.00, 'Accesorios', 'imgs/fundas.jpeg'),
    ('7891234560130', 'Estuche metálico para cartas', NULL, NULL, NULL, 35, 1.50, 3.00, 'Accesorios', 'imgs/estuche.jpeg'),
    ('7891234560131', 'Pikachu PSA 10', '1 carta gradada', 'Base Set 1ª Edición', 'Holográfica Gem Mint', 1, 60000.00, 80000.00, 'Cartas Gradadas', 'imgs/pikachu.jpeg');

-- -----------------------------------------------------
--               Mensaje de Confirmación
-- -----------------------------------------------------
SELECT 'Datos de ejemplo insertados correctamente' AS Mensaje;
SELECT COUNT(*) AS Total_Clientes FROM Clientes;
SELECT COUNT(*) AS Total_Productos FROM Productos;