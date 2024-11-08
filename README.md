Los datos de la base de datos son:

--Para crear nuestra base de datos
CREATE DATABASE lib_db;

-- Mostrar todas las bases de datos disponibles en el servidor.
SHOW DATABASES;

-- Usa la base de datos creada
USE lib_db;

CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(50) NOT NULL,
correo VARCHAR(100) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL
);

CREATE TABLE libros (
id INT AUTO_INCREMENT PRIMARY KEY,
titulo VARCHAR(100) NOT NULL,
autor VARCHAR(100) NOT NULL,
genero VARCHAR(50),
disponible BOOLEAN DEFAULT TRUE
);

CREATE TABLE transacciones (
id INT AUTO_INCREMENT PRIMARY KEY,
id_libro INT,
id_usuario INT,
fecha_prestamo DATE,
fecha_devolucion DATE,
FOREIGN KEY (id_libro) REFERENCES libros(id),
FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

--me añado:
INSERT INTO usuarios (nombre, correo, password) VALUES
('Gaby', 'Gabriel.ruiz1@utp.ac.pa', PASSWORD('Holagaby'));

--Añadimos libros:
INSERT INTO libros (titulo, autor, genero, disponible) VALUES
('Cien Años de Soledad', 'Gabriel García Márquez', 'Ficción', TRUE),
('Don Quijote de la Mancha', 'Miguel de Cervantes', 'Aventura', TRUE),
('Orgullo y Prejuicio', 'Jane Austen', 'Romance', TRUE),
('1984', 'George Orwell', 'Distopía', TRUE),
('Moby Dick', 'Herman Melville', 'Aventura', TRUE),
('Crimen y Castigo', 'Fiódor Dostoyevski', 'Drama', TRUE),
('La Odisea', 'Homero', 'Épica', TRUE),
('El Gran Gatsby', 'F. Scott Fitzgerald', 'Ficción', TRUE),
('Drácula', 'Bram Stoker', 'Horror', TRUE),
('El Alquimista', 'Paulo Coelho', 'Ficción', TRUE);
