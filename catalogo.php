<?php
include 'header.php';
echo "<div class='container mt-4'>";

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "lib_db");
$result = $conn->query("SELECT * FROM libros");

// Título del catálogo
echo "<div class='tittle text-center mb-4'>";
echo "<h2 class='display-5'>Catálogo de Libros</h2>";
echo "</div>";

// Mostrar los libros en tarjetas
echo "<div class='row'>";  // Comienza la fila de tarjetas
while ($libro = $result->fetch_assoc()) {
    // Verificar si el libro está disponible
    $disponibilidad = $libro['disponible'] ? 'Disponible' : 'No disponible';
    $borderClass = $libro['disponible'] ? 'border-success' : 'border-danger'; // Borde verde si está disponible, rojo si no
    $statusBackground = $libro['disponible'] ? 'bg-success text-white' : 'bg-danger text-white'; // Fondo verde o rojo solo para el estado

    // Comenzamos la tarjeta
    echo "<div class='col-md-4 mb-4'>";
    echo "<div class='card shadow-sm $borderClass'>"; // Aplicar borde según disponibilidad
    echo "<div class='card-body'>";
    echo "<h5 class='card-title font-weight-bold'>" . htmlspecialchars($libro['titulo']) . "</h5>";
    echo "<p class='card-text'>Autor: " . htmlspecialchars($libro['autor']) . "</p>";
    echo "<p class='card-text'>Género: " . htmlspecialchars($libro['genero']) . "</p>";
    // Mostrar estado con el fondo de color solo en esa sección
    echo "<p class='card-text'><strong>Estado: </strong><span class='px-2 py-1 $statusBackground'>$disponibilidad</span></p>";
    echo "</div>"; // Cierre del cuerpo de la tarjeta
    echo "</div>"; // Cierre de la tarjeta
    echo "</div>"; // Cierre de la columna
}
echo "</div>";  // Cierre de la fila de tarjetas

$conn->close();
?> 
