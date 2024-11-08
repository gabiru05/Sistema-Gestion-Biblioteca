<?php

include 'header.php';
echo "<div class='container'>";

$conn = new mysqli("localhost", "root", "", "lib_db");
$result = $conn->query("SELECT * FROM libros");

echo "<h2>Catálogo de Libros</h2>";
while ($libro = $result->fetch_assoc()) {
    echo "<div class='book-card'>";
    echo "<h3>" . htmlspecialchars($libro['titulo']) . "</h3>";
    echo "<p>Autor: " . htmlspecialchars($libro['autor']) . "</p>";
    echo "<p>Género: " . htmlspecialchars($libro['genero']) . "</p>";

}
$conn->close();
echo "</div>";


?>
