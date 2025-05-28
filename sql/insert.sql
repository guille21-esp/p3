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
--              Inserción de Transportistas
-- -----------------------------------------------------
INSERT INTO Transportista (Nombre, InfoContacto, Activo) VALUES
    ('Correos Express', 'Tel: 900 123 456, Email: info@correosexpress.com', 'S'),
    ('SEUR', 'Tel: 913 228 080, Email: atencioncliente@seur.net', 'S'),
    ('MRW', 'Tel: 902 300 400, Email: clientes@mrw.es', 'S'),
    ('DHL Express', 'Tel: 902 12 24 24, Email: es.customerservice@dhl.com', 'S'),
    ('UPS España', 'Tel: 900 10 24 10, Email: customerservice.es@ups.com', 'N');
    
-- -----------------------------------------------------
--              Inserción de Opciones_Envío
-- -----------------------------------------------------
INSERT INTO Opcion_Envio (Nombre_Opcion, Descripcion, Coste, Activa) VALUES
('Envío Estándar', 'Entrega estimada en 3-5 días laborables', 3.50, 'S'),
('Envío Urgente', 'Entrega estimada en 24/48h laborables', 6.50, 'S'),
('Punto de Recogida', 'Recoge en 2-4 días en punto convenido', 2.00, 'S'),
('Envío Internacional Básico', 'Entrega en 7-15 días (Europa)', 12.00, 'N'); 

-- -----------------------------------------------------
--               Inserción de Venta (Ejemplos)
-- -----------------------------------------------------
-- Luego al realizar las ventas debería de seguir rellenándose esta tabla --
INSERT INTO Venta (Id_Cliente, Fecha, Cantidad_de_Productos, Subtotal_Productos, Id_Opcion_Envio, Coste_Envio_Aplicado, Total) VALUES
(1, TO_DATE('2025-05-20', 'YYYY-MM-DD'), 2, 10.00, 1, 3.50, 13.50),
(2, TO_DATE('2025-05-21', 'YYYY-MM-DD'), 3, 10006.00, 2, 6.50, 10012.50),
(6, TO_DATE('2025-05-22', 'YYYY-MM-DD'), 1, 5.00, 3, 2.00, 7.00);

-- -----------------------------------------------------
--          Inserción de Detalle_Venta (Ejemplos)
-- -----------------------------------------------------
-- Mismo caso que con las ventas --
INSERT INTO Detalle_Venta (Id_Venta, Id_Producto, Nombre, Categoria, GTIN_Producto, Precio, Cantidad) VALUES
-- Detalles para la Venta 1 --
(1, 1, 'Pack de cartas: Chispas Fulgurantes', 'Boosters', '7891234560123', 5.00, 1),
(1, 2, 'Pack de cartas: Silver Tempest', 'Boosters', '7891234560124', 5.00, 1),
-- Detalles para la Venta 2 --
(2, 3, 'Charizard PSA 10', 'Cartas Gradadas', '7891234560125', 10000.00, 1),
(2, 7, 'Fundas para cartas (x100)', 'Accesorios', '7891234560129', 1.00, 5),
(2, 1, 'Pack de cartas: Chispas Fulgurantes', 'Boosters', '7891234560123', 5.00, 1),
-- Detalle para la Venta 3 --
(3, 2, 'Pack de cartas: Silver Tempest', 'Boosters', '7891234560124', 5.00, 1);

-- -----------------------------------------------------
--                Inserción de Envíos
-- -----------------------------------------------------
-- Envio para Venta 1 --
INSERT INTO Envios (Id_Venta, Direccion_Envio, Estado_Envio, Fecha_Estimada_Entrega, Num_Seguimiento, Transportista_Asignado, Fecha_Creacion_Envio) VALUES
(1, 'Calle Primavera 23, Madrid', '¡Preparado!', TO_DATE('2025-05-28', 'YYYY-MM-DD'), 'TRKMARIA001', 'Correos Express', TO_DATE('2025-05-21', 'YYYY-MM-DD')),
-- Envio para Venta 2 --
(2, 'Avenida Libertad 45, Barcelona', 'Enviado', TO_DATE('2025-05-25', 'YYYY-MM-DD'), 'TRKJUAN002', 'SEUR', TO_DATE('2025-05-22', 'YYYY-MM-DD')),
-- Envio para Venta 3 --
(3, 'Paseo Marítimo 89, Málaga', 'Entregado', TO_DATE('2025-05-26', 'YYYY-MM-DD'), 'TRKPRUEBA003', 'MRW', TO_DATE('2025-05-23', 'YYYY-MM-DD'));

-- -----------------------------------------------------
--         Inserción de Historial_Estado_Envío
-- -----------------------------------------------------
INSERT INTO Historial_Estado_Envio (ID_Envio, FechaHora, Estado_Envio, Ubicacion, Notas) VALUES
-- Historial para el Envío 1 --
(1, TO_TIMESTAMP('2025-05-21 10:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Nuevo Pedido', 'Sistema', 'Pedido confirmado por el cliente.'),
(1, TO_TIMESTAMP('2025-05-21 10:30:00', 'YYYY-MM-DD HH24:MI:SS'), 'En preparación', 'Almacén Central', 'Productos siendo recolectados.'),
(1, TO_TIMESTAMP('2025-05-21 18:00:00', 'YYYY-MM-DD HH24:MI:SS'), '¡Preparado!', 'Almacén Central', 'Paquete listo para recogida por transportista.'),
-- Historial para el Envío 2 --
(2, TO_TIMESTAMP('2025-05-22 09:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Nuevo Pedido', 'Sistema', 'Pedido confirmado.'),
(2, TO_TIMESTAMP('2025-05-22 09:30:00', 'YYYY-MM-DD HH24:MI:SS'), 'En preparación', 'Almacén Central', NULL),
(2, TO_TIMESTAMP('2025-05-22 17:00:00', 'YYYY-MM-DD HH24:MI:SS'), '¡Preparado!', 'Almacén Central', 'Listo para envío.'),
(2, TO_TIMESTAMP('2025-05-23 10:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Enviado', 'Centro Logístico SEUR Madrid', 'En tránsito hacia Barcelona.'),
-- Historial para el Envío 3 --
(3, TO_TIMESTAMP('2025-05-23 11:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Nuevo Pedido', 'Sistema', 'Pedido procesado.'),
(3, TO_TIMESTAMP('2025-05-23 11:30:00', 'YYYY-MM-DD HH24:MI:SS'), 'En preparación', 'Almacén Central', 'Empaquetando...'),
(3, TO_TIMESTAMP('2025-05-23 18:00:00', 'YYYY-MM-DD HH24:MI:SS'), '¡Preparado!', 'Almacén Central', 'Paquete listo.'),
(3, TO_TIMESTAMP('2025-05-23 19:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Enviado', 'Plataforma MRW Getafe', 'En tránsito.'),
(3, TO_TIMESTAMP('2025-05-26 10:30:00', 'YYYY-MM-DD HH24:MI:SS'), 'Entregado', 'Paseo Marítimo 89, Málaga', 'Entregado y firmado por el destinatario.');

-- -----------------------------------------------------
--               Mensaje de Confirmación
-- -----------------------------------------------------
SELECT 'Datos de ejemplo insertados correctamente' AS Mensaje;
SELECT COUNT(*) AS Total_Clientes FROM Clientes;
SELECT COUNT(*) AS Total_Productos FROM Productos;
SELECT COUNT(*) AS Total_Transportistas FROM Transportista;
SELECT COUNT(*) AS Total_Opciones_Envio FROM Opcion_Envio;
SELECT COUNT(*) AS Total_Ventas FROM Venta;
SELECT COUNT(*) AS Total_Detalles_Venta FROM Detalle_Venta;
SELECT COUNT(*) AS Total_Envios FROM Envios;
SELECT COUNT(*) AS Total_Historial_Envios FROM Historial_Estado_Envio;