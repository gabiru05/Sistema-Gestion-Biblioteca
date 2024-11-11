<?php
require_once '../includes/Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Este correo ya está registrado. ¿Desea <a href='login.php'>iniciar sesión</a>?";
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $password);
        $stmt->execute();
        echo "Usuario registrado correctamente";
        header("Location: login.php");
        exit();
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label>Correo:</label>
        <input type="email" name="correo" required><br>
        <label>Contraseña:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Registrar</button>
    </form>
</body>
<footer>
    <h2>Ya estas registrado? Inicia sesion</h2>
    <nav>
        <a href="login.php">Iniciar Sesión</a>
    </nav>
</footer>
</html>
