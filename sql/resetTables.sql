-- Archivo: reset_tables.sql
-- Elimina todas las tablas de la base de datos tienda_online
-- ANTES DE EJECUTAR ESTE ARCHIVO SE DEBERÍA HACER UN BACKUP:
-- mysqldump -u usuario -p tienda_online > backup_tienda_online.sql

USE tienda_online;

-- Desactivar temporalmente las restricciones de clave foránea
SET FOREIGN_KEY_CHECKS = 0;

-- Eliminar tablas en el orden correcto (primero las que tienen dependencias)
DROP TABLE IF EXISTS Detalle_Carrito;
DROP TABLE IF EXISTS Carrito_Ventas;
DROP TABLE IF EXISTS Productos;
DROP TABLE IF EXISTS Clientes;

-- Reactivar las restricciones de clave foránea
SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------
--                  Tabla Productos
-- -----------------------------------------------------
CREATE TABLE Productos(
  ID_Producto INT AUTO_INCREMENT PRIMARY KEY, 
  GTIN VARCHAR(14) UNIQUE,
  Nombre VARCHAR(50) NOT NULL,
  Stock INT DEFAULT 0, 
  Precio_Compra DECIMAL(7, 2),
  Precio_Venta DECIMAL(7, 2) NOT NULL, 
  Categoria VARCHAR(20), 
  ImagenURL VARCHAR(255) NULL 
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
  Total DECIMAL(6, 2) DEFAULT 0.00,
  CONSTRAINT FK_Carrito_Cliente FOREIGN KEY (ID_Cliente) 
    REFERENCES Clientes(ID_Cliente) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -----------------------------------------------------
--                Tabla Detalle_Carrito
-- -----------------------------------------------------
CREATE TABLE Detalle_Carrito (
  ID_Carrito INT NOT NULL,
  ID_Producto INT NOT NULL,
  Nombre_Producto VARCHAR(50),
  Categoria VARCHAR(40),
  GTIN VARCHAR(14),
  Precio DECIMAL(5, 2) NOT NULL, 
  Cantidad INT DEFAULT 1 NOT NULL,
  PRIMARY KEY (ID_Carrito, ID_Producto), 
  CONSTRAINT FK_Detalle_Carrito_CV FOREIGN KEY (ID_Carrito) 
    REFERENCES Carrito_Ventas(ID_Carrito) ON DELETE CASCADE,
  CONSTRAINT FK_Detalle_Prod FOREIGN KEY (ID_Producto) 
    REFERENCES Productos(ID_Producto)
) ENGINE=InnoDB;

-- Mensaje de confirmación
SELECT 'Base de datos reinicializada correctamente' AS Mensaje;