<?php
// Incluir el archivo de conexión a la base de datos
include '../includes/Database.php';

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Crear instancia de la base de datos
$db = new Database();
$conn = $db->connect();

// Comenzar una transacción para asegurar que ambas tablas se limpien de manera segura
$conn->begin_transaction();

try {
    // Eliminar todo el contenido de la tabla transacciones
    $stmt = $conn->prepare("DELETE FROM transacciones");
    $stmt->execute();

    // Eliminar todo el contenido de la tabla libros
    $stmt = $conn->prepare("DELETE FROM libros");
    $stmt->execute();

    // Si todo salió bien, hacer commit para confirmar los cambios
    $conn->commit();
    
    echo "<div class='alert alert-success'>Las tablas han sido vaciadas con éxito.</div>";
} catch (Exception $e) {
    // Si ocurrió un error, hacer rollback y mostrar mensaje de error
    $conn->rollback();
    echo "<div class='alert alert-danger'>Hubo un error al vaciar las tablas: " . $e->getMessage() . "</div>";
}

// Cerrar la conexión
$conn->close();
?>
