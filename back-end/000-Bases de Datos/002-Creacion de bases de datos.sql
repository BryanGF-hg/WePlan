--sudo mysql -u root -p 
CREATE DATABASE WePlanDB;
USE WePlanDB;


CREATE TABLE usuarios (
    usuario_id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    correo_electronico VARCHAR(150) UNIQUE NOT NULL,
    pais VARCHAR(50),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Registros de ejemplo
INSERT INTO usuarios (nombre, apellidos, telefono, direccion, correo_electronico, pais) VALUES
('María', 'García López', '+34 600 111 222', 'Calle Mayor 10, Madrid', 'maria.garcia@email.com', 'España'),
('Carlos', 'Rodríguez Pérez', '+34 611 222 333', 'Av. Diagonal 25, Barcelona', 'carlos.rodriguez@email.com', 'España'),
('Ana', 'Martínez Sánchez', '+34 622 333 444', 'Calle Gran Vía 45, Valencia', 'ana.martinez@email.com', 'España'),
('David', 'Fernández Ruiz', '+34 633 444 555', 'Plaza España 3, Sevilla', 'david.fernandez@email.com', 'España'),
('Laura', 'Gómez Díaz', '+34 644 555 666', 'Calle Sierpes 15, Sevilla', 'laura.gomez@email.com', 'España'),
('Javier', 'Hernández Castro', '+34 655 666 777', 'Calle Preciados 8, Madrid', 'javier.hernandez@email.com', 'España');

CREATE TABLE grupos (
    grupo_id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    creador_id INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    max_miembros INT DEFAULT 50,
    FOREIGN KEY (creador_id) REFERENCES usuarios(usuario_id)
);

-- Registros de ejemplo
INSERT INTO grupos (nombre, descripcion, creador_id) VALUES
('Fútbol Los Domingos', 'Grupo para jugar al fútbol los domingos por la mañana', 1),
('Club de Café Literario', 'Reuniones para tomar café y comentar libros', 2),
('Pádel Amigos', 'Grupo para jugar al pádel entre semana', 3),
('Conciertos Madrid', 'Salidas a conciertos en Madrid', 4),
('Intercambio de Idiomas', 'Práctica de inglés y español', 5),
('Running Matutino', 'Grupo de running a las 7 AM', 6);

CREATE TABLE categorias_actividades (
    categoria_id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

-- Inserción de categorías principales
INSERT INTO categorias_actividades (nombre, descripcion) VALUES
('Deporte', 'Actividades deportivas y ejercicio físico'),
('Relajación', 'Actividades sociales y de ocio relajado'),
('Música', 'Eventos y actividades musicales'),
('Educación', 'Actividades de aprendizaje y desarrollo');

CREATE TABLE subcategorias_actividades (
    subcategoria_id INT PRIMARY KEY AUTO_INCREMENT,
    categoria_id INT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    FOREIGN KEY (categoria_id) REFERENCES categorias_actividades(categoria_id)
);

-- Inserción de subcategorías
INSERT INTO subcategorias_actividades (categoria_id, nombre) VALUES
-- Deporte
(1, 'Fútbol'), (1, 'Pádel'), (1, 'Baloncesto'), (1, 'Tenis'),
(1, 'Natación'), (1, 'Running'), (1, 'Ciclismo'), (1, 'Yoga'),
-- Relajación
(2, 'Café'), (2, 'Charla'), (2, 'Cena'), (2, 'Paseo'),
(2, 'Cine'), (2, 'Juegos de mesa'), (2, 'Picnic'),
-- Música
(3, 'Conciertos'), (3, 'Karaoke'), (3, 'Club de música'),
(3, 'Taller de instrumentos'), (3, 'Festivales'),
-- Educación
(4, 'Idiomas'), (4, 'Artes'), (4, 'Danza'), (4, 'Cocina'),
(4, 'Fotografía'), (4, 'Literatura'), (4, 'Teatro');

CREATE TABLE aficiones (
    aficion_id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(50)
);

-- Inserción de aficiones comunes
INSERT INTO aficiones (nombre, categoria) VALUES
('Lectura', 'Cultural'), ('Viajes', 'Ocio'), ('Cine', 'Entretenimiento'),
('Cocina', 'Gastronomía'), ('Fotografía', 'Artística'),
('Senderismo', 'Deporte'), ('Jardinería', 'Ocio'),
('Videojuegos', 'Entretenimiento'), ('Pintura', 'Artística'),
('Música', 'Cultural'), ('Baile', 'Artística'), ('Deportes', 'Actividad');

CREATE TABLE usuario_grupo (
    usuario_id INT NOT NULL,
    grupo_id INT NOT NULL,
    PRIMARY KEY (usuario_id, grupo_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(grupo_id)
);

-- Registros de ejemplo para comprobar
INSERT INTO usuario_grupo (usuario_id, grupo_id) VALUES
(1, 1), (1, 3),  -- María en Fútbol y Pádel
(2, 2), (2, 4),  -- Carlos en Café y Conciertos
(3, 3), (3, 5),  -- Ana en Pádel e Idiomas
(4, 1), (4, 4),  -- David en Fútbol y Conciertos
(5, 2), (5, 5),  -- Laura en Café e Idiomas
(6, 6);          -- Javier solo en Running

CREATE TABLE usuario_aficion (
    usuario_id INT NOT NULL,
    aficion_id INT NOT NULL,
    PRIMARY KEY (usuario_id, aficion_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id),
    FOREIGN KEY (aficion_id) REFERENCES aficiones(aficion_id)
);

INSERT INTO usuario_aficion (usuario_id, aficion_id) VALUES
(1, 1), (1, 4),  -- María: Lectura y Cocina
(2, 2), (2, 5),  -- Carlos: Viajes y Fotografía
(3, 3), (3, 6),  -- Ana: Cine y Senderismo
(4, 10), (4, 12), -- David: Música y Deportes
(5, 1), (5, 8),  -- Laura: Lectura y Pintura
(6, 7);          -- Javier: Jardinería

CREATE TABLE grupo_subcategoria (
    grupo_id INT NOT NULL,
    subcategoria_id INT NOT NULL,
    PRIMARY KEY (grupo_id, subcategoria_id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(grupo_id),
    FOREIGN KEY (subcategoria_id) REFERENCES subcategorias_actividades(subcategoria_id)
);

INSERT INTO grupo_subcategoria (grupo_id, subcategoria_id) VALUES
(1, 1),   -- Fútbol Los Domingos -> Fútbol (subcategoria_id 1)
(2, 9),   -- Club de Café -> Café (subcategoria_id 9)
(2, 10),  -- Club de Café -> Charla (subcategoria_id 10)
(3, 2),   -- Pádel Amigos -> Pádel (subcategoria_id 2)
(4, 16),  -- Conciertos Madrid -> Conciertos (subcategoria_id 16)
(5, 21),  -- Intercambio de Idiomas -> Idiomas (subcategoria_id 21)
(6, 6);   -- Running Matutino -> Running (subcategoria_id 6)

CREATE TABLE eventos (
    evento_id INT PRIMARY KEY AUTO_INCREMENT,
    grupo_id INT NOT NULL,
    subcategoria_id INT,
    titulo VARCHAR(100) NOT NULL,
    fecha_hora DATETIME NOT NULL,
    lugar VARCHAR(200),
    FOREIGN KEY (grupo_id) REFERENCES grupos(grupo_id),
    FOREIGN KEY (subcategoria_id) REFERENCES subcategorias_actividades(subcategoria_id)
);

INSERT INTO eventos (grupo_id, subcategoria_id, titulo, fecha_hora, lugar) VALUES
(1, 1, 'Partido Domingo', '2024-03-10 10:00:00', 'Campo Deportivo La Elipa'),
(2, 9, 'Café Literario: Kafka', '2024-03-12 18:00:00', 'Café Gijón'),
(3, 2, 'Torneo Pádel', '2024-03-15 19:00:00', 'Club Pádel Madrid'),
(4, 16, 'Concierto Rock', '2024-03-20 21:00:00', 'Sala Caracol'),
(5, 21, 'Intercambio Inglés-Español', '2024-03-11 20:00:00', 'Biblioteca Municipal'),
(6, 6, 'Running Parque Retiro', '2024-03-13 07:00:00', 'Parque del Retiro');

CREATE TABLE evento_participantes (
    evento_id INT NOT NULL,
    usuario_id INT NOT NULL,
    PRIMARY KEY (evento_id, usuario_id),
    FOREIGN KEY (evento_id) REFERENCES eventos(evento_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id)
);

INSERT INTO evento_participantes (evento_id, usuario_id) VALUES
(1, 1), (1, 4),   -- Partido: María y David
(2, 2), (2, 5),   -- Café: Carlos y Laura
(3, 1), (3, 3),   -- Pádel: María y Ana
(4, 2), (4, 4),   -- Concierto: Carlos y David
(5, 3), (5, 5),   -- Idiomas: Ana y Laura
(6, 6);           -- Running: Javier

---------------------
--USUARIO!!-----------
---------------------

-- Creacion de usuario que podrá acceder a la base de datos
CREATE USER 'usuario-weplan'@'localhost' IDENTIFIED BY 'Usuarioweplan123$';

GRANT USAGE ON *.* TO 'usuario-weplan'@'localhost';

ALTER USER 'usuario-weplan'@'localhost'
REQUIRE NONE
WITH MAX_QUERIES_PER_HOUR 0
MAX_CONNECTIONS_PER_HOUR 0
MAX_UPDATES_PER_HOUR 0
MAX_USER_CONNECTIONS 0;

GRANT ALL PRIVILEGES ON WePlanDB.* TO 'usuario-weplan'@'localhost';

FLUSH PRIVILEGES;

---------------------
--INDICES!!-----------
---------------------

-- Indices para búsquedas frecuentes
CREATE INDEX idx_usuarios_email ON usuarios(correo_electronico);
CREATE INDEX idx_usuarios_nombre ON usuarios(nombre, apellidos);
CREATE INDEX idx_grupos_nombre ON grupos(nombre);
CREATE INDEX idx_eventos_fecha ON eventos(fecha_hora);
CREATE INDEX idx_eventos_grupo ON eventos(grupo_id);

-- Índices para relaciones
CREATE INDEX idx_usuario_grupo_usuario ON usuario_grupo(usuario_id);
CREATE INDEX idx_usuario_grupo_grupo ON usuario_grupo(grupo_id);


---------------------
--VISTAS!!-----------
---------------------

-- Vista para ver grupos con número de miembros
CREATE VIEW vista_grupos_con_miembros AS
SELECT 
    g.grupo_id,
    g.nombre AS grupo,
    COUNT(ug.usuario_id) AS miembros,
    u.nombre AS creador,
    g.descripcion
FROM grupos g
LEFT JOIN usuario_grupo ug ON g.grupo_id = ug.grupo_id
JOIN usuarios u ON g.creador_id = u.usuario_id
GROUP BY g.grupo_id;

-- Vista para eventos próximos
CREATE VIEW vista_eventos_proximos AS
SELECT 
    e.evento_id,
    e.titulo, 
    e.fecha_hora, 
    e.lugar,
    g.nombre AS grupo,
    s.nombre AS actividad
FROM eventos e
JOIN grupos g ON e.grupo_id = g.grupo_id
LEFT JOIN subcategorias_actividades s ON e.subcategoria_id = s.subcategoria_id
WHERE e.fecha_hora > NOW()
ORDER BY e.fecha_hora;

-- Vista para aficiones de usuarios
CREATE VIEW vista_usuario_aficiones AS
SELECT 
    u.usuario_id,
    u.nombre, 
    u.apellidos,
    GROUP_CONCAT(a.nombre SEPARATOR ', ') AS aficiones
FROM usuarios u
LEFT JOIN usuario_aficion ua ON u.usuario_id = ua.usuario_id
LEFT JOIN aficiones a ON ua.aficion_id = a.aficion_id
GROUP BY u.usuario_id;
