-- Archivo: reset_tables.sql
-- Elimina todas las tablas de la base de datos tienda_online
-- ANTES DE EJECUTAR ESTE ARCHIVO SE DEBERÍA HACER UN BACKUP:
-- mysqldump -u sie -p tienda_online > sql/backup_tienda_online.sql


-- Para eliminar la BBDD si existe, descomentar SÓLO si se quiere borrar todo
-- DROP DATABASE IF EXISTS tienda_online;

CREATE DATABASE IF NOT EXISTS tienda_online
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE tienda_online;

-- Desactivar temporalmente las restricciones de clave foránea
SET FOREIGN_KEY_CHECKS = 0;

-- Eliminar tablas en el orden correcto (primero las que tienen dependencias)
DROP TABLE IF EXISTS Historial_Estado_Envio;
DROP TABLE IF EXISTS Opcion_Envio;
DROP TABLE IF EXISTS Transportista;
DROP TABLE IF EXISTS Venta;
DROP TABLE IF EXISTS Detalle_Venta;
DROP TABLE IF EXISTS Envios;
DROP TABLE IF EXISTS Detalle_Carrito;
DROP TABLE IF EXISTS Carrito_Ventas;
DROP TABLE IF EXISTS Productos;
DROP TABLE IF EXISTS Categorias;
DROP TABLE IF EXISTS Clientes;

-- Reactivar las restricciones de clave foránea
SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------
--                Tabla Categorias
-- -----------------------------------------------------
CREATE TABLE Categorias (
  ID_Categoria INT AUTO_INCREMENT PRIMARY KEY,
  Nombre_Categoria VARCHAR(50) NOT NULL UNIQUE
);

-- -----------------------------------------------------
--                  Tabla Productos
-- -----------------------------------------------------
CREATE TABLE Productos(
  ID_Producto INT AUTO_INCREMENT PRIMARY KEY, 
  GTIN VARCHAR(14) UNIQUE,
  Nombre VARCHAR(50) NOT NULL,
  Contenido VARCHAR(255) NULL,
  Edicion VARCHAR(255) NULL,
  Rarezas VARCHAR(255) NULL,
  Stock INT DEFAULT 0, 
  Precio_Compra DECIMAL(10, 2),
  Precio_Venta DECIMAL(10, 2) NOT NULL, 
  ID_Categoria INT NULL, 
  ImagenURL VARCHAR(255) NULL, 
  CONSTRAINT FK_Producto_Categoria FOREIGN KEY (ID_Categoria) REFERENCES Categorias(ID_Categoria)
) ENGINE=InnoDB;

-- -----------------------------------------------------
--                  Tabla Clientes
-- -----------------------------------------------------
CREATE TABLE Clientes(
  ID_Cliente INT AUTO_INCREMENT PRIMARY KEY,
  Nombre VARCHAR(40) NOT NULL, 
  Apellidos VARCHAR(100), 
  Correo VARCHAR(150) NOT NULL UNIQUE, 
  Telefono VARCHAR(12) NULL, 
  Nacimiento DATE NULL,
  Contrasena VARCHAR(255) NOT NULL,
  Datos_Bancarios VARCHAR(150) NULL, 
  Direccion VARCHAR(150) NULL, 
  Razon_Social VARCHAR(100) NULL, 
  NIF VARCHAR(9) NULL UNIQUE 
) ENGINE=InnoDB;

-- -----------------------------------------------------
--                Tabla Carrito_Ventas
-- -----------------------------------------------------
CREATE TABLE Carrito_Ventas (
  ID_Carrito INT AUTO_INCREMENT PRIMARY KEY,
  ID_Cliente INT NULL,
  Cantidad_Productos INT DEFAULT 0,
  Total DECIMAL(10, 2) DEFAULT 0.00,
  Temporal_Token CHAR(32) NULL,
  Fecha_Expiracion DATETIME NULL,
  CONSTRAINT FK_Carrito_Cliente FOREIGN KEY (ID_Cliente) 
    REFERENCES Clientes(ID_Cliente) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -----------------------------------------------------
--                Tabla Opción_Envío
-- -----------------------------------------------------
CREATE TABLE Opcion_Envio (
  ID_Opcion_Envio INT AUTO_INCREMENT PRIMARY KEY,
  Nombre_Opcion VARCHAR(100) NOT NULL, 
  Descripcion VARCHAR(255) NULL, 
  Coste DECIMAL(7, 2) NOT NULL,
  Activa CHAR(1) DEFAULT 'S' CHECK (Activa IN ('S', 'N'))
);

-- -----------------------------------------------------
--                 Tabla Venta
-- -----------------------------------------------------
CREATE TABLE Venta (
  ID_Venta INT AUTO_INCREMENT PRIMARY KEY, 
  ID_Cliente INT NOT NULL, 
  Fecha DATETIME DEFAULT CURRENT_TIMESTAMP, 
  Cantidad_de_Productos INT DEFAULT 0, 
  Subtotal_Productos DECIMAL(10, 2) DEFAULT 0.00, 
  ID_Opcion_Envio INT NULL, 
  Coste_Envio_Aplicado DECIMAL(7, 2) DEFAULT 0.00, 
  Total DECIMAL(10, 2) DEFAULT 0.00, 
  CONSTRAINT FK_Venta_Cliente FOREIGN KEY (ID_Cliente) REFERENCES Clientes(ID_Cliente),
  CONSTRAINT FK_Venta_OpcionEnvio FOREIGN KEY (ID_Opcion_Envio) REFERENCES Opcion_Envio(ID_Opcion_Envio)
) ENGINE=InnoDB;

-- -----------------------------------------------------
--                Tabla Detalle_Venta
-- -----------------------------------------------------
CREATE TABLE Detalle_Venta (
  ID_Venta INT NOT NULL, 
  ID_Producto INT NOT NULL, 
  Nombre VARCHAR(50), 
  Categoria VARCHAR(40), 
  GTIN_Producto VARCHAR(14), 
  Precio DECIMAL(10, 2), 
  Cantidad INT DEFAULT 1 NOT NULL, 
  CONSTRAINT PK_Detalle_Venta PRIMARY KEY (ID_Venta, ID_Producto),
  CONSTRAINT FK_DetalleV_Venta FOREIGN KEY (ID_Venta) REFERENCES Venta(ID_Venta) ON DELETE CASCADE,
  CONSTRAINT FK_DetalleV_Producto FOREIGN KEY (ID_Producto) REFERENCES Productos(ID_Producto) 
);

-- -----------------------------------------------------
--                Tabla Detalle_Carrito
-- -----------------------------------------------------
CREATE TABLE Detalle_Carrito (
  ID_Carrito INT NOT NULL,
  ID_Producto INT NOT NULL,
  Nombre_Producto VARCHAR(50),
  Categoria VARCHAR(40),
  GTIN VARCHAR(14),
  Precio DECIMAL(10, 2) NOT NULL, 
  Cantidad INT DEFAULT 1 NOT NULL,
  PRIMARY KEY (ID_Carrito, ID_Producto), 
  CONSTRAINT FK_Detalle_Carrito_CV FOREIGN KEY (ID_Carrito) 
    REFERENCES Carrito_Ventas(ID_Carrito) ON DELETE CASCADE,
  CONSTRAINT FK_Detalle_Prod FOREIGN KEY (ID_Producto) 
    REFERENCES Productos(ID_Producto)
) ENGINE=InnoDB;

-- -----------------------------------------------------
--                  Tabla Envío
-- -----------------------------------------------------
CREATE TABLE Envios (
  ID_Envio INT AUTO_INCREMENT PRIMARY KEY,
  ID_Venta INT NOT NULL,
  Direccion_Envio VARCHAR(255) NOT NULL,
  Estado_Envio VARCHAR(50) DEFAULT 'Nuevo Pedido', 
  Fecha_Estimada_Entrega DATE NULL,
  Num_Seguimiento VARCHAR(50) NULL, 
  Transportista_Asignado VARCHAR(100) NULL, 
  Fecha_Creacion_Envio DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT FK_Envio_Venta FOREIGN KEY (ID_Venta) REFERENCES Venta(ID_Venta) ON DELETE CASCADE
);

-- -----------------------------------------------------
--             Tabla Historial_Estado_Envío
-- -----------------------------------------------------
CREATE TABLE Historial_Estado_Envio (
  ID_Historial INT AUTO_INCREMENT PRIMARY KEY,
  ID_Envio INT NOT NULL,
  FechaHora DATETIME DEFAULT CURRENT_TIMESTAMP, 
  Estado_Envio VARCHAR(50) NOT NULL, 
  Ubicacion VARCHAR(255) NULL, 
  Notas VARCHAR(500) NULL, 
  CONSTRAINT FK_Historial_Envio FOREIGN KEY (ID_Envio) REFERENCES Envios(ID_Envio) ON DELETE CASCADE
);

-- -----------------------------------------------------
--               Tabla Transportista
-- -----------------------------------------------------
CREATE TABLE Transportista (
  ID_Transportista INT AUTO_INCREMENT PRIMARY KEY, 
  Nombre VARCHAR(100) NOT NULL, 
  InfoContacto VARCHAR(255) NULL, 
  Activo CHAR(1) DEFAULT 'S' CHECK (Activo IN ('S', 'N')) 
);

-- Disparador para actualizar el stock --
DELIMITER //
CREATE TRIGGER actualizar_stock_post_venta
AFTER INSERT ON Detalle_Venta
FOR EACH ROW
BEGIN
    UPDATE Productos
    SET Stock = Stock - NEW.Cantidad 
    WHERE ID_Producto = NEW.ID_Producto; 
END;
//

-- Mensaje de confirmación --
SELECT 'Base de datos reinicializada correctamente' AS Mensaje;
