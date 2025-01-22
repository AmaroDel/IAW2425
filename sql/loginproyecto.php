<?php
session_start();

// Conexión a la base de datos
$servername = ""; // Nombre del servidor
$username = ""; // Nombre de usuario
$password = ""; // Contraseña
$database = ""; // Nombre de la base de datos
$enlace = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$enlace) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Procesar formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que los campos no estén vacíos
    if (empty($_POST['email']) || empty($_POST['password'])) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Saneamiento de las entradas
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Consultar el usuario por email usando sentencias preparadas
    $query = "SELECT * FROM usuarios WHERE email=?";
    $stmt = mysqli_prepare($enlace, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) === 1) {
        // Recuperar los datos del usuario
        $usuario = mysqli_fetch_assoc($resultado);

        // Verificar la contraseña (comparación estricta)
        if (hash_equals($usuario['password'], crypt($password, $usuario['password']))) {
            // Iniciar sesión y redirigir al usuario
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['nombre'];
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Error: Contraseña incorrecta.";
        }
    } else {
        echo "Error: Usuario no encontrado.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($enlace);
?>

<!-- Formulario de inicio de sesión -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form method="POST" action="loginproyecto.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>
