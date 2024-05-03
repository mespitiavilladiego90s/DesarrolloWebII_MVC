-- Creamos la tabla Cliente
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60),
    apellido VARCHAR(60),
    email VARCHAR(30),
    password VARCHAR(60),
    telefono VARCHAR(10),
    admin TINYINT(1),
    confirmado TINYINT(1),
    token VARCHAR(15)
);




