BASE DE DATOS:

-- Entro al directorio
CD C:\XAMPP\MYSQL\BIN

-- Inicio sesión a la base de datos
MYSQL -U ROOT -P

--Para crear nuestra base de datos
CREATE DATABASE LIB_DB;

-- Mostrar todas las bases de datos disponibles en el servidor.
SHOW DATABASES;

-- Esocojo mi base de datos creada
USE LIB_DB;

-- cree las tablas
CREATE TABLE USUARIOS (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE VARCHAR(50) NOT NULL,
    CORREO VARCHAR(100) NOT NULL UNIQUE,
    PASSWORD VARCHAR(255) NOT NULL
);

CREATE TABLE LIBROS (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TITULO VARCHAR(100) NOT NULL,
    AUTOR VARCHAR(100) NOT NULL,
    GENERO VARCHAR(50),
    DISPONIBLE BOOLEAN DEFAULT TRUE
);

CREATE TABLE TRANSACCIONES (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ID_LIBRO INT,
    ID_USUARIO INT,
    FECHA_PRESTAMO DATE,
    FECHA_DEVOLUCION DATE,
    FOREIGN KEY (ID_LIBRO) REFERENCES LIBROS(ID),
    FOREIGN KEY (ID_USUARIO) REFERENCES USUARIOS(ID)
);

-- Le añado los libros:
INSERT INTO LIBROS (
    TITULO,
    AUTOR,
    GENERO,
    DISPONIBLE
) VALUES (
    'Cien Años de Soledad',
    'Gabriel García Márquez',
    'Ficción',
    TRUE
),
(
    'Don Quijote de la Mancha',
    'Miguel de Cervantes',
    'Aventura',
    TRUE
),
(
    'Orgullo y Prejuicio',
    'Jane Austen',
    'Romance',
    TRUE
),
(
    '1984',
    'George Orwell',
    'Distopía',
    TRUE
),
(
    'Moby Dick',
    'Herman Melville',
    'Aventura',
    TRUE
),
(
    'Crimen y Castigo',
    'Fiódor Dostoyevski',
    'Drama',
    TRUE
),
(
    'La Odisea',
    'Homero',
    'Épica',
    TRUE
),
(
    'El Gran Gatsby',
    'F. Scott Fitzgerald',
    'Ficción',
    TRUE
),
(
    'Drácula',
    'Bram Stoker',
    'Horror',
    TRUE
),
(
    'El Alquimista',
    'Paulo Coelho',
    'Ficción',
    TRUE
);

-- Verifico las columnas
SHOW COLUMNS FROM usuarios;

SHOW COLUMNS FROM transacciones;

SHOW COLUMNS FROM libros;

-- Mostrar todos los datos de la tabla 'usuarios'.
SELECT
    *
FROM
    USUARIOS;

SELECT
    *
FROM
    TRANSACCIONES;

SELECT
    *
FROM
    LIBROS;