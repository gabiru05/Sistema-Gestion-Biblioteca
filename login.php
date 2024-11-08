<?php
session_start();
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    if ($hashed_password && password_verify($password, $hashed_password)) {
        $_SESSION['id_usuario'] = $id;
        header("Location: catalogo.php");
        exit();
    } else {
        echo "Correo o contraseña incorrectos.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        form { max-width: 300px; margin: auto; }
    </style>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form method="POST">
        <label>Correo:</label>
        <input type="email" name="correo" required><br>
        <label>Contraseña:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Iniciar Sesión</button>
    </form>
    <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
</body>
</html>
