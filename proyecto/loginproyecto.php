<?php
session_start();

// Conexión a la base de datos
$servername = "**********";
$username = "**********";
$password = "**********";
$database = "**********";
$enlace = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$enlace) {
    die("Conexión fallida: " . mysqli_connect_error());
}

mysqli_set_charset($enlace, "utf8mb4");

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
    $query = "SELECT * FROM registrados WHERE email=?";
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
            // Almacenar el rol del usuario en la sesión
            $_SESSION['rol'] = $usuario['rol'];
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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div>
        <h4>Iniciar Sesión</h4>
        <form method="POST" action="loginproyecto.php">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar sesión</button>
        </form>
        <p>
            <a href="registro.php">No estás registrado</a>
        </p>
    </div>
</body>
</html>
