<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $genero = $_POST['genero'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO libros (titulo, autor, genero, disponible) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $titulo, $autor, $genero, $disponible);
    $stmt->execute();
    $stmt->close();
    echo "<p>Libro añadido con éxito.</p>";
}

echo "<h2>Añadir Nuevo Libro</h2>
<form method='POST'>
    <label>Título:</label><input type='text' name='titulo' required><br>
    <label>Autor:</label><input type='text' name='autor' required><br>
    <label>Género:</label><input type='text' name='genero' required><br>
    <label>Disponible:</label><input type='checkbox' name='disponible'><br>
    <button type='submit'>Añadir Libro</button>
</form>";
echo "<a href='catalogo.php'>Volver al Catálogo</a>";
$conn->close();
?>
