<?php
// Incluir la clase Database para la conexión a la base de datos
include '../includes/Database.php';

// Leer el archivo JSON
$file = 'libros.json'; // Ruta del archivo JSON
if (file_exists($file)) {
    $json_data = file_get_contents($file); // Leer el archivo
    $libros = json_decode($json_data, true); // Decodificar el JSON en un array asociativo

    // Verificar si los datos fueron cargados correctamente
    if ($libros === null) {
        die("Error al leer el archivo JSON o los datos no tienen el formato correcto.");
    }

    // Crear una instancia de la clase Database para la conexión
    $database = new Database();
    $conn = $database->connect();

    // Preparar la consulta para insertar los libros
    $stmt = $conn->prepare("INSERT INTO libros (titulo, autor, genero, cantidad, disponible) VALUES (?, ?, ?, ?, ?)");

    // Recorrer el array de libros y ejecutar la consulta
    foreach ($libros as $libro) {
        // Asignar los valores de cada libro
        $titulo = $libro['titulo'];
        $autor = $libro['autor'];
        $genero = $libro['genero'];
        $cantidad = $libro['cantidad'];
        $disponible = $libro['disponible'];  // Asumiendo que 'disponibilidad' en JSON es lo mismo que 'disponible' en la base de datos

        // Ejecutar la consulta
        $stmt->bind_param("sssis", $titulo, $autor, $genero, $cantidad, $disponible);
        $stmt->execute();
    }

    echo "Libros cargados correctamente.";
} else {
    echo "El archivo JSON no existe.";
}
?>
