
# Sistema de Préstamo de Libros

Este proyecto implementa un sistema de gestión de préstamos de libros utilizando **PHP** y una base de datos **MySQL** (MariaDB). Los usuarios pueden registrarse, iniciar sesión y alquilar libros. El sistema también utiliza **cookies** para recordar la última búsqueda realizada y **sesiones** para manejar la autenticación de usuarios.

## Tecnologías Utilizadas

- **PHP**
- **MySQL (MariaDB)**
- **XAMPP** (para el servidor Apache y base de datos MySQL)
- **HTML / CSS** (para la interfaz de usuario)
- Bootstrap para la interfaz

## Instrucciones de Instalación

1. **Instalar XAMPP**:  
   Descarga e instala [XAMPP](https://www.apachefriends.org/index.html) en tu máquina local.

2. **Configurar la Base de Datos**:  
   Abre la terminal de comandos (o `cmd` en Windows) y navega al directorio de MySQL en XAMPP:

   ```bash
   cd C:\xampp\mysql\bin

## Luego, accede a MySQL e ingresa los comandos para crear la base de datos y las tablas necesarias:
    

## Funcionalidades

- **Búsqueda de Libros**: Los usuarios pueden buscar libros por título, autor o género.
- **Alquiler de Libros**: Los usuarios pueden alquilar libros si están disponibles.
- **Transacciones de Alquiler**: Registra el libro alquilado, el usuario y las fechas de préstamo y devolución.
- **Cookies**: Recordar la última búsqueda realizada.
- **Sesiones**: Manejo de la autenticación de los usuarios.

## Cómo Funciona

### Registro de Usuarios
Los usuarios pueden registrarse desde la página `registro.php`, y sus datos se guardan en la base de datos.

### Inicio de Sesión
Una vez registrados, los usuarios pueden iniciar sesión desde `login.php` y, una vez autenticados, acceden a las funcionalidades de alquiler.

### Alquiler de Libros
En `catalogo.php`, los usuarios ven el catálogo de libros disponibles y, en `prestamo.php`, pueden realizar el alquiler. Las sesiones verifican si el usuario está autenticado, y las cookies recuerdan la última búsqueda realizada.

### Transacciones
Cada alquiler crea un registro en la tabla `transacciones`, incluyendo la fecha de préstamo y la fecha de devolución del libro.

### Verificación de Disponibilidad
El estado de disponibilidad de los libros cambia a "No disponible" en la base de datos cuando un libro es alquilado.

## Mejoras Futuras

- **Mejorar la Interfaz**: Diseño atractivo y responsive.
- **Funcionalidad de Devolución**: Permitir la devolución y ver el historial de alquileres.
- **Calificación de Libros**: Sistema para que los usuarios califiquen los libros.

## Contribuciones

Si deseas contribuir a este proyecto, haz un fork del repositorio, realiza tus cambios y envía un pull request.
