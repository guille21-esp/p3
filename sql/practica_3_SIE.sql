-- -----------------------------------------------------
--                  Tabla Productos
-- -----------------------------------------------------
CREATE TABLE Productos (
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
CREATE TABLE Clientes (
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

-- -----------------------------------------------------
--                 Tabla Venta
-- -----------------------------------------------------
CREATE TABLE Venta (
  Id_Venta INT GENERATED AS IDENTITY PRIMARY KEY, 
  Id_Cliente INT NOT NULL, 
  Fecha DATE DEFAULT SYSDATE, 
  Cantidad_de_Productos INT DEFAULT 0, 
  Subtotal_Productos NUMBER(7, 2) DEFAULT 0.00, 
  Id_Opcion_Envio INT NULL, 
  Coste_Envio_Aplicado NUMBER(7, 2) DEFAULT 0.00, 
  Total NUMBER(7, 2) DEFAULT 0.00, 
  CONSTRAINT FK_Venta_Cliente FOREIGN KEY (Id_Cliente) REFERENCES Clientes(ID_Cliente),
  CONSTRAINT FK_Venta_OpcionEnvio FOREIGN KEY (Id_Opcion_Envio) REFERENCES Opcion_Envio(ID_Opcion_Envio)
);

ALTER TABLE Venta
RENAME COLUMN Total TO Subtotal_Productos;

ALTER TABLE Venta ADD (
  Id_Opcion_Envio INT NULL,
  Coste_Envio_Aplicado NUMBER(7, 2) DEFAULT 0.00,
  Total NUMBER(7, 2) DEFAULT 0.00
);

ALTER TABLE Venta
ADD CONSTRAINT FK_Venta_OpcionEnvio
FOREIGN KEY (Id_Opcion_Envio) REFERENCES Opcion_Envio(ID_Opcion_Envio);

DESC Venta;

-- -----------------------------------------------------
--                Tabla Detalle_Venta
-- -----------------------------------------------------
CREATE TABLE Detalle_Venta (
  Id_Venta INT NOT NULL, 
  Id_Producto INT NOT NULL, 
  Nombre VARCHAR2(50), 
  Categoria VARCHAR2(40), 
  GTIN_Producto VARCHAR2(14), 
  Precio NUMBER(5, 2), 
  Cantidad INT DEFAULT 1 NOT NULL, 
  CONSTRAINT PK_Detalle_Venta PRIMARY KEY (ID_Venta, ID_Producto),
  CONSTRAINT FK_DetalleV_Venta FOREIGN KEY (Id_Venta) REFERENCES Venta(Id_Venta) ON DELETE CASCADE,
  CONSTRAINT FK_DetalleV_Producto FOREIGN KEY (Id_Producto) REFERENCES Productos(ID_Producto) 
);

DESC Detalle_Venta;

-- -----------------------------------------------------
--                  Tabla Envío
-- -----------------------------------------------------
CREATE TABLE Envios (
  ID_Envio INT GENERATED AS IDENTITY PRIMARY KEY,
  Id_Venta INT NOT NULL,
  Direccion_Envio VARCHAR2(255) NOT NULL,
  Estado_Envio VARCHAR2(50) DEFAULT 'Pendiente de preparación', 
  Fecha_Estimada_Entrega DATE NULL,
  Num_Seguimiento VARCHAR2(50) NULL, 
  Transportista_Asignado VARCHAR2(100) NULL, 
  Fecha_Creacion_Envio DATE DEFAULT SYSDATE,
  CONSTRAINT FK_Envio_Venta FOREIGN KEY (Id_Venta) REFERENCES Venta(Id_Venta) ON DELETE CASCADE
);

DESC Envios;

-- -----------------------------------------------------
--             Tabla Historial_Estado_Envío
-- -----------------------------------------------------
CREATE TABLE Historial_Estado_Envio (
  ID_Historial INT GENERATED AS IDENTITY PRIMARY KEY,
  ID_Envio INT NOT NULL,
  FechaHora TIMESTAMP DEFAULT SYSTIMESTAMP, 
  Estado_Envio VARCHAR2(50) NOT NULL, 
  Ubicacion VARCHAR2(255) NULL, 
  Notas VARCHAR2(500) NULL, 
  CONSTRAINT FK_Historial_Envio FOREIGN KEY (ID_Envio) REFERENCES Envios(ID_Envio) ON DELETE CASCADE
);

DESC Historial_Estado_Envio;

-- -----------------------------------------------------
--                Tabla Opción_Envío
-- -----------------------------------------------------
CREATE TABLE Opcion_Envio (
  ID_Opcion_Envio INT GENERATED AS IDENTITY PRIMARY KEY,
  Nombre_Opcion VARCHAR2(100) NOT NULL, 
  Descripcion VARCHAR2(255) NULL, 
  Coste NUMBER(7, 2) NOT NULL,
  Activa CHAR(1) DEFAULT 'S' CHECK (Activa IN ('S', 'N'))
);

DESC Opcion_Envio;

-- -----------------------------------------------------
--               Tabla Transportista
-- -----------------------------------------------------
CREATE TABLE Transportista (
  ID_Transportista INT GENERATED AS IDENTITY PRIMARY KEY, 
  Nombre VARCHAR(100) NOT NULL, 
  InfoContacto VARCHAR2(255) NULL, 
  Activo CHAR(1) DEFAULT 'S' CHECK (Activo IN ('S', 'N')) 
);

DESC Transportista;
