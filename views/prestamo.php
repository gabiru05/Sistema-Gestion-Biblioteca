<?php

include '../includes/header.php';

echo "<div class='container'>";

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../includes/Database.php';
$db = new Database();
$conn = $db->connect();

// Comprobar si existe una cookie de búsqueda y utilizarla
$lastSearch = isset($_COOKIE['last_search']) ? $_COOKIE['last_search'] : '';

// Inicializa la variable para evitar el error de undefined variable
$resultTransaccion = null;

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

        // Verificar si el usuario ya ha alquilado este libro
        $checkTransaccion = $conn->prepare("SELECT * FROM transacciones WHERE id_libro = ? AND id_usuario = ? AND fecha_devolucion IS NULL");
        $checkTransaccion->bind_param("ii", $id_libro, $id_usuario);
        $checkTransaccion->execute();
        $resultTransaccion = $checkTransaccion->get_result();
        
        if ($resultTransaccion->num_rows > 0) {
            echo "<div class='alert alert-warning'>Ya has alquilado este libro. No puedes alquilarlo de nuevo hasta devolverlo.</div>";
        } else {
            // Verificar la cantidad de ejemplares disponibles
            $check = $conn->prepare("SELECT cantidad FROM libros WHERE id = ?");
            $check->bind_param("i", $id_libro);
            $check->execute();
            $check->bind_result($cantidad);
            $check->fetch();
            $check->close();

            if ($cantidad > 0) {
                // Si hay ejemplares disponibles, proceder con el préstamo

                $dias = rand(3, 7);  // Número aleatorio entre 3 y 7 días para la fecha de devolución
                $stmt = $conn->prepare("INSERT INTO transacciones (id_libro, id_usuario, fecha_prestamo, fecha_devolucion) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY))");
                $stmt->bind_param("iii", $id_libro, $id_usuario, $dias);
                $stmt->execute();
                $stmt->close();

                // Disminuir la cantidad de ejemplares disponibles
                $update = $conn->prepare("UPDATE libros SET cantidad = cantidad - 1 WHERE id = ?");
                $update->bind_param("i", $id_libro);
                $update->execute();
                $update->close();

                echo "<div class='alert alert-success'>Libro alquilado con éxito. Fecha de devolución en $dias días.</div>";
            } else {
                // Si no hay ejemplares disponibles
                echo "<div class='alert alert-danger'>El libro no está disponible.</div>";
            }
        }
    }
}

echo "<div class='w-100 text-center my-2 py-2'>
        <h2 class='display-6'>Préstamo de Libros</h2>
      </div>";

// Formulario de búsqueda
echo "<form method='POST' class='d-flex justify-content-center mb-4'>
        <div class='input-group w-100'>
            <input type='text' name='search_term' placeholder='Buscar un libro...' value='" . htmlspecialchars($lastSearch) . "' class='form-control' />
            <div class='input-group-append'>
                <button type='submit' name='buscar' class='btn btn-primary'>Buscar</button>
            </div>
        </div>
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

echo "<div class='row'>";  // Comienza la fila de tarjetas

while ($libro = $result->fetch_assoc()) {
    // Determinar la disponibilidad del libro
    $disponibilidad = $libro['cantidad'] > 0 ? 'Disponible' : 'No disponible';
    $borderClass = $libro['cantidad'] > 0 ? 'border-success' : 'border-danger'; // Borde verde si está disponible, rojo si no
    $statusBackground = $libro['cantidad'] > 0 ? 'bg-success text-white' : 'bg-danger text-white'; // Fondo verde o rojo solo para el estado

    // Comenzamos la tarjeta
    echo "<div class='col-md-4 mb-4'>
            <div class='card shadow-sm $borderClass'>"; // Aplicar borde según disponibilidad
    echo "<div class='card-body'>";
    echo "<h5 class='card-title font-weight-bold'>" . htmlspecialchars($libro['titulo']) . "</h5>";
    echo "<p class='card-text'>Autor: " . htmlspecialchars($libro['autor']) . "</p>";
    echo "<p class='card-text'>Género: " . htmlspecialchars($libro['genero']) . "</p>";
    // Mostrar estado con el fondo de color solo en esa sección
    echo "<p class='card-text'><strong>Estado: </strong><span class='px-2 py-1 $statusBackground'>$disponibilidad</span></p>";

    if ($libro['cantidad'] > 0 && ($resultTransaccion == null || $resultTransaccion->num_rows == 0)) {
        echo "<form method='POST'>
                <input type='hidden' name='id_libro' value='" . $libro['id'] . "'>
                <button type='submit' name='alquilar' class='btn btn-success'>Alquilar</button>
              </form>";
    } else {
        echo "<p class='alert alert-warning'>No disponible para alquilar</p>";  // Estilo diferente para cuando no está disponible
    }
    echo "  </div>
          </div>
        </div>";
}

echo "</div>";  // Cierre de la fila de tarjetas

echo "<a href='catalogo.php' class='btn btn-link'>Volver al Catálogo</a>";
$conn->close();

echo "</div>";
?>
