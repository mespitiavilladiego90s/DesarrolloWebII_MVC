CREATE TABLE usuarios (
  id int NOT NULL AUTO_INCREMENT,
  nombre varchar(60) DEFAULT NULL,
  apellido varchar(60) DEFAULT NULL,
  email varchar(60) DEFAULT NULL,
  password varchar(60) DEFAULT NULL,
  telefono varchar(10) DEFAULT NULL,
  rol int DEFAULT 1,
  confirmado tinyint(1) DEFAULT NULL,
  token varchar(15) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE rol (
  id int NOT NULL AUTO_INCREMENT,
  rol varchar(60),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO rol (rol) VALUES
('Default'),
('Admin'),
('Meeting Creator'),
('Meeting Assistant'),
('Inform Manager');

CREATE TABLE actas (
  id int NOT NULL AUTO_INCREMENT,
  asunto varchar(60),
  fecha_inicio datetime,
  encuentro varchar(60),
  fecha_finalizacion datetime,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE compromisos (
  id int NOT NULL AUTO_INCREMENT,
  id_asignador int NOT NULL,
  id_asignado int NOT NULL,
  id_acta int NOT NULL,
  descripcion varchar(60),
  estado tinyint(1),
  PRIMARY KEY (id),
  FOREIGN KEY (id_asignador) REFERENCES usuarios (id) ON DELETE CASCADE,
  FOREIGN KEY (id_asignado) REFERENCES usuarios (id) ON DELETE CASCADE,
  FOREIGN KEY (id_acta) REFERENCES actas (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE participantes (
  id int NOT NULL AUTO_INCREMENT,
  id_participante int NOT NULL,
  id_acta int NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_participante) REFERENCES usuarios (id) ON DELETE CASCADE,
  FOREIGN KEY (id_acta) REFERENCES actas (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE actas
ADD COLUMN id_participantes int,
ADD COLUMN id_compromisos int,
ADD FOREIGN KEY (id_participantes) REFERENCES participantes(id) ON DELETE CASCADE,
ADD FOREIGN KEY (id_compromisos) REFERENCES compromisos(id) ON DELETE CASCADE;



-- Tabla de informes
CREATE TABLE informe (
  id int NOT NULL AUTO_INCREMENT,
  id_usuario int NOT NULL,
  tipo_informe varchar(60),
  fecha_inicial datetime,
  fecha_final datetime,
  PRIMARY KEY (id),
  FOREIGN KEY (id_usuario) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




