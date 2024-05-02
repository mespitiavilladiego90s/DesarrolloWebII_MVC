-- Creamos la tabla Cliente
CREATE TABLE IF NOT EXISTS Cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100),
    direccion VARCHAR(200),
    telefono VARCHAR(20),
    ciudad VARCHAR(100),
    fecha_registro DATE
);

-- Creamos la tabla Vendedor
CREATE TABLE IF NOT EXISTS Vendedor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100),
    telefono VARCHAR(20),
    direccion VARCHAR(200),
    departamento VARCHAR(100),
    salario FLOAT,
    fecha_contratacion DATE
);

-- Creamos la tabla OrdenGasolina
CREATE TABLE IF NOT EXISTS OrdenGasolina (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    precio FLOAT,
    hora_orden DATETIME,
    tiempo_llenado FLOAT
);

-- Creamos la tabla Factura
CREATE TABLE IF NOT EXISTS Factura (
    id INT AUTO_INCREMENT,
    id_cliente INT,
    id_vendedor INT,
    id_ordengasolina INT,
    fecha_compra DATETIME,
    PRIMARY KEY (id),
    FOREIGN KEY (id_cliente) REFERENCES Cliente(id),
    FOREIGN KEY (id_vendedor) REFERENCES Vendedor(id),
    FOREIGN KEY (id_ordengasolina) REFERENCES OrdenGasolina(id)
);

-- Insertamos datos de PRUEBA en la tabla Cliente
INSERT INTO Cliente (nombre, email, direccion, telefono, ciudad, fecha_registro)
VALUES
('Cliente 1', 'cliente1@example.com', 'Calle A 123', '123456789', 'Ciudad A', '2024-01-01'),
('Cliente 2', 'cliente2@example.com', 'Calle B 456', '987654321', 'Ciudad B', '2024-02-01'),
('Cliente 3', 'cliente3@example.com', 'Calle C 789', '456123789', 'Ciudad C', '2024-03-01'),
('Cliente 4', 'cliente4@example.com', 'Calle D 012', '321654987', 'Ciudad D', '2024-04-01'),
('Cliente 5', 'cliente5@example.com', 'Calle E 345', '789456123', 'Ciudad E', '2024-05-01');

-- Insertamos datos de PRUEBA en la tabla Vendedor
INSERT INTO Vendedor (nombre, email, telefono, direccion, departamento, salario, fecha_contratacion)
VALUES
('Vendedor 1', 'vendedor1@example.com', '111111111', 'Calle F 123', 'Ventas', 2000.00, '2023-01-01'),
('Vendedor 2', 'vendedor2@example.com', '222222222', 'Calle G 456', 'Ventas', 2200.00, '2023-02-01'),
('Vendedor 3', 'vendedor3@example.com', '333333333', 'Calle H 789', 'Ventas', 2300.00, '2023-03-01'),
('Vendedor 4', 'vendedor4@example.com', '444444444', 'Calle I 012', 'Ventas', 2400.00, '2023-04-01'),
('Vendedor 5', 'vendedor5@example.com', '555555555', 'Calle J 345', 'Ventas', 2500.00, '2023-05-01');

-- Insertamos datos de PRUEBA en la tabla OrdenGasolina
INSERT INTO OrdenGasolina (nombre, precio, hora_orden, tiempo_llenado)
VALUES
('Orden 1', 50.00, '2024-01-01 08:00:00', 10.5),
('Orden 2', 55.00, '2024-01-02 09:00:00', 12.0),
('Orden 3', 60.00, '2024-01-03 10:00:00', 11.2),
('Orden 4', 65.00, '2024-01-04 11:00:00', 9.8),
('Orden 5', 70.00, '2024-01-05 12:00:00', 14.5);

-- Insertamos datos de PRUEBA en la tabla Factura
INSERT INTO Factura (id_cliente, id_vendedor, id_ordengasolina, fecha_compra)
VALUES
(1, 1, 1, '2024-01-01 08:30:00'),
(2, 2, 2, '2024-01-02 09:30:00'),
(3, 3, 3, '2024-01-03 10:30:00'),
(4, 4, 4, '2024-01-04 11:30:00'),
(5, 5, 5, '2024-01-05 12:30:00'),
(4, 4, 4, '2024-01-04 11:31:00');


-- Procedimiento de almacenado para traer 3 campos de cada tabla mediante el id de la tabla de factura:

CREATE PROCEDURE obtenerDatosClienteVendedorOrden(IN factura_id INT)
BEGIN
    -- Declarar la variable fuera del bloque IF
    DECLARE factura_count INT;
    
    -- Buscamos la factura con el ID proporcionado
    SELECT COUNT(*) INTO factura_count FROM Factura WHERE id = factura_id;
    
    -- Verificamos si el parámetro es un entero mayor que 0
    IF factura_id IS NULL OR factura_id <= 0 OR factura_id != FLOOR(factura_id) THEN
        SELECT 'El ID de factura proporcionado no es válido.' AS Mensaje;
    ELSE
        -- Si no se encuentra la factura, devolvemos mensaje de error
        IF factura_count = 0 THEN
            SELECT 'No se encontró información relacionada con la factura.' AS Mensaje;
        ELSE
            -- Obtenemos la información del cliente, vendedor y orden de gasolina
            SELECT
                f.fecha_compra AS FechaCreacionFactura,
                c.nombre AS NombreCliente,
                c.direccion AS DireccionCliente,
                c.telefono AS TelefonoCliente,
                v.nombre AS NombreVendedor,
                v.direccion AS DireccionVendedor,
                v.telefono AS TelefonoVendedor,
                o.nombre AS NombreOrdenGasolina,
                o.precio AS PrecioOrdenGasolina,
                o.hora_orden AS HoraOrdenGasolina
            FROM Factura f
            INNER JOIN Cliente c ON f.id_cliente = c.id
            INNER JOIN Vendedor v ON f.id_vendedor = v.id
            INNER JOIN OrdenGasolina o ON f.id_ordengasolina = o.id
            WHERE f.id = factura_id;
        END IF;
    END IF;
END;

-- Procedimiento de almacenado que me trae los registros de la tabla 'factura' basados en el id proporcionado desde la tabla 'ordengasolina'. Trae 3 atributos de cada una de las tablas.

CREATE PROCEDURE obtenerFacturasAOrden(IN orden_id INT)
BEGIN
    -- Verificamos si el parámetro es un entero mayor que 0
    IF orden_id IS NULL OR orden_id <= 0 OR orden_id != FLOOR(orden_id) THEN
        SELECT 'El ID de orden proporcionado no es válido.' AS Mensaje;
    ELSE
        -- Verificamos si el ID de orden existe en la tabla OrdenGasolina
        IF EXISTS (SELECT 1 FROM OrdenGasolina WHERE id = orden_id) THEN
            -- Obtenemos las facturas relacionadas con el ID de orden proporcionado
            SELECT 
                f.id AS FacturaID,
                f.id_ordengasolina AS OrdenGasolinaID,
                f.fecha_compra AS FechaCompra,
                o.nombre AS NombreOrdenGasolina,
                o.precio AS PrecioOrdenGasolina,
                o.hora_orden AS HoraOrdenGasolina
            FROM Factura f
            INNER JOIN OrdenGasolina o ON f.id_ordengasolina = o.id
            WHERE o.id = orden_id;
        ELSE
            SELECT 'No se encontró información relacionada con la orden proporcionada.' AS Mensaje;
        END IF;
    END IF;
END;