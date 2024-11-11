<?php
include 'header.php';
echo "<div class='container my-5'>"; // Añadimos un margen superior e inferior para un diseño más espacioso

$conn = new mysqli("localhost", "root", "", "lib_db");
$result = $conn->query("SELECT transacciones.id, libros.titulo, usuarios.nombre, transacciones.fecha_prestamo, transacciones.fecha_devolucion 
                        FROM transacciones 
                        JOIN libros ON transacciones.id_libro = libros.id 
                        JOIN usuarios ON transacciones.id_usuario = usuarios.id");

echo "<h2 class='text-center mb-4'>Registro de Transacciones</h2>";

if ($result->num_rows > 0) {
    echo "<div class='row'>";  // Usamos una fila de Bootstrap para las tarjetas

    while ($transaccion = $result->fetch_assoc()) {
        // Cada transacción se presentará como una tarjeta con estilo mejorado
        echo "<div class='col-md-4 mb-4'>
                <div class='card shadow-lg border-light'>
                    <div class='card-body'>
                        <h5 class='card-title text-primary'>" . htmlspecialchars($transaccion['titulo']) . "</h5>
                        <p class='card-text'><strong>Usuario:</strong> " . htmlspecialchars($transaccion['nombre']) . "</p>
                        <p class='card-text'><strong>Fecha de Préstamo:</strong> " . $transaccion['fecha_prestamo'] . "</p>
                        <p class='card-text'><strong>Fecha de Devolución:</strong> " . ($transaccion['fecha_devolucion'] ?? '<span class="text-warning">Pendiente</span>') . "</p>
                    </div>
                </div>
              </div>";
    }
    echo "</div>";  // Cierra la fila de tarjetas
} else {
    echo "<p class='alert alert-info text-center'>No hay transacciones registradas.</p>";
}

$conn->close();
echo "</div>";
?>
