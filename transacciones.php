<?php
include 'header.php';
echo "<div class='container'>";
$conn = new mysqli("localhost", "root", "", "lib_db");
$result = $conn->query("SELECT transacciones.id, libros.titulo, usuarios.nombre, transacciones.fecha_prestamo, transacciones.fecha_devolucion 
                        FROM transacciones 
                        JOIN libros ON transacciones.id_libro = libros.id 
                        JOIN usuarios ON transacciones.id_usuario = usuarios.id");

echo "<h2>Registro de Transacciones</h2>";
if ($result->num_rows > 0) {
    while ($transaccion = $result->fetch_assoc()) {
        echo "<div class='book-card'>";
        echo "<p><strong>Libro:</strong> " . htmlspecialchars($transaccion['titulo']) . "</p>";
        echo "<p><strong>Usuario:</strong> " . htmlspecialchars($transaccion['nombre']) . "</p>";
        echo "<p><strong>Fecha de Préstamo:</strong> " . $transaccion['fecha_prestamo'] . "</p>";
        echo "<p><strong>Fecha de Devolución:</strong> " . ($transaccion['fecha_devolucion'] ?? 'Pendiente') . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No hay transacciones registradas.</p>";
}
$conn->close();
echo "</div>";
?>
