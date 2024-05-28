CREATE TABLE USUARIOS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(60) DEFAULT NULL,
    apellido VARCHAR(60) DEFAULT NULL,
    email VARCHAR(60) DEFAULT NULL,
    password VARCHAR(60) DEFAULT NULL,
    telefono VARCHAR(10) DEFAULT NULL,
    rol ENUM('Default', 'Admin', 'Meeting Creator', 'Meeting Assistant', 'Inform Manager') DEFAULT 'Default',
    confirmado TINYINT(1) DEFAULT NULL,
    token VARCHAR(15) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE REUNION (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    lugar VARCHAR(255) NOT NULL,
    asunto VARCHAR(255) NOT NULL,
    estado ENUM('pública', 'privada') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE ASISTENTES (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reunion_id INT NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (reunion_id) REFERENCES REUNION(id),
    FOREIGN KEY (usuario_id) REFERENCES USUARIOS(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE ACTAS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reunion_id INT NOT NULL,
    contenido TEXT NOT NULL,
    FOREIGN KEY (reunion_id) REFERENCES REUNION(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE COMPROMISOS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    acta_id INT NOT NULL,
    descripcion TEXT NOT NULL,
    responsable_id INT NOT NULL,
    fecha_entrega DATE NOT NULL,
    estado ENUM('creado', 'asignado') NOT NULL,
    FOREIGN KEY (acta_id) REFERENCES ACTAS(id),
    FOREIGN KEY (responsable_id) REFERENCES USUARIOS(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
