<?php
include 'header.php';
echo "<div class='container'>";

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

// Comprobar si existe una cookie de búsqueda y utilizarla
$lastSearch = isset($_COOKIE['last_search']) ? $_COOKIE['last_search'] : '';

// Función para alquilar un libro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['buscar'])) {
        // Guardar la búsqueda en una cookie que expirará en 1 día
        $lastSearch = $_POST['search_term'];
        setcookie('last_search', $lastSearch, time() + 86400);  // 86400 = 1 día
    }

    if (isset($_POST['alquilar'])) {
        $id_libro = $_POST['id_libro'];
        $id_usuario = $_SESSION['id_usuario'];

        // Verificar disponibilidad
        $check = $conn->prepare("SELECT disponible FROM libros WHERE id = ?");
        $check->bind_param("i", $id_libro);
        $check->execute();
        $check->bind_result($disponible);
        $check->fetch();
        $check->close();

        if ($disponible) {
            $dias = rand(3, 7);
            $stmt = $conn->prepare("INSERT INTO transacciones (id_libro, id_usuario, fecha_prestamo, fecha_devolucion) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY))");
            $stmt->bind_param("iii", $id_libro, $id_usuario, $dias);
            $stmt->execute();
            $stmt->close();

            $update = $conn->prepare("UPDATE libros SET disponible = 0 WHERE id = ?");
            $update->bind_param("i", $id_libro);
            $update->execute();
            $update->close();

            echo "<p>Libro alquilado con éxito. Fecha de devolución en $dias días.</p>";
        } else {
            echo "<p>El libro no está disponible.</p>";
        }
    }
}

// Formulario de búsqueda
echo "<form method='POST'>
    <input type='text' name='search_term' placeholder='Buscar un libro...' value='" . htmlspecialchars($lastSearch) . "'>
    <button type='submit' name='buscar'>Buscar</button>
</form>";

// Mostrar el catálogo de libros, aplicando la búsqueda si existe
$query = "SELECT * FROM libros";
if (!empty($lastSearch)) {
    $query .= " WHERE titulo LIKE ? OR autor LIKE ? OR genero LIKE ?";
    $stmt = $conn->prepare($query);
    $searchParam = '%' . $lastSearch . '%';
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

echo "<h2>Préstamo de Libros</h2>";

while ($libro = $result->fetch_assoc()) {
    echo "<div class='libro'>
        <h3>" . htmlspecialchars($libro['titulo']) . "</h3>
        <p>Autor: " . htmlspecialchars($libro['autor']) . "</p>
        <p>Género: " . htmlspecialchars($libro['genero']) . "</p>";
    echo $libro['disponible'] ? "<p style='color:green;'>Disponible</p>" : "<p style='color:red;'>No disponible</p>";

    if ($libro['disponible']) {
        echo "<form method='POST'>
            <input type='hidden' name='id_libro' value='" . $libro['id'] . "'>
            <button type='submit' name='alquilar'>Alquilar</button>
        </form>";
    } else {
        echo "<p>No disponible para alquilar.</p>";
    }
    echo "</div>";
}

echo "<a href='catalogo.php'>Volver al Catálogo</a>";
$conn->close();
?>
