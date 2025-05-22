-- -----------------------------------------------------
--                  Tabla Productos
-- -----------------------------------------------------
CREATE TABLE Productos(
  ID_Producto INT GENERATED AS IDENTITY PRIMARY KEY, 
  GTIN VARCHAR(14) UNIQUE,
  Nombre VARCHAR(50) NOT NULL,
  Stock INT DEFAULT 0, 
  Precio_compra DECIMAL(7, 2),
  Precio_venta DECIMAL(7, 2) NOT NULL, 
  Categoria VARCHAR(20), 
  ImagenURL VARCHAR(255) NULL 
);

DESC Productos;

-- -----------------------------------------------------
--                  Tabla Clientes
-- -----------------------------------------------------
CREATE TABLE Clientes(
  ID_Cliente INT GENERATED AS IDENTITY PRIMARY KEY,
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
);

DESC Clientes;

-- -----------------------------------------------------
--                Tabla Carrito_Ventas
-- -----------------------------------------------------
CREATE TABLE Carrito_Ventas (
  ID_Carrito INT GENERATED AS IDENTITY PRIMARY KEY,
  ID_Cliente INT NULL,
  Cantidad_Productos INT DEFAULT 0,
  Total NUMBER(6, 2) DEFAULT 0.00,
  CONSTRAINT FK_Carrito_Cliente FOREIGN KEY (ID_Cliente) REFERENCES Clientes(ID_Cliente) ON DELETE SET NULL
);

DESC Carrito_Ventas;

-- -----------------------------------------------------
--                Tabla Detalle_Carrito
-- -----------------------------------------------------
CREATE TABLE Detalle_Carrito (
  ID_Carrito INT NOT NULL,
  ID_Producto INT NOT NULL,
  Nombre_Producto VARCHAR2(50),
  Categoria VARCHAR2(40),
  GTIN VARCHAR2(14),
  Precio NUMBER(5, 2) NOT NULL, 
  Cantidad INT DEFAULT 1 NOT NULL,
  CONSTRAINT PK_Detalle_Carrito PRIMARY KEY (ID_Carrito, ID_Producto), 
  CONSTRAINT FK_Detalle_Carrito_CV FOREIGN KEY (ID_Carrito) REFERENCES Carrito_Ventas(ID_Carrito) ON DELETE CASCADE,
  CONSTRAINT FK_Detalle_Prod FOREIGN KEY (ID_Producto) REFERENCES Productos(ID_Producto)
);

DESC Detalle_Carrito;