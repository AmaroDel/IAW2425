<?php
// Configuración de conexión a la base de datos
$servername = "sql308.thsite.top";
$username = "thsi_38097484";
$password = "*******";
$database = "thsi_38097484_ejemplo";

// Establecer la conexión
$enlace = mysqli_connect($servername, $username, $password, $database);

// Verificar si la conexión fue exitosa
if (!$enlace) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Verificar si el método de la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que los campos no estén vacíos
    if (empty($_POST['email']) || empty($_POST['password'])) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Saneamiento de las entradas del usuario
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Verificar si el usuario existe en la base de datos
    $query = "SELECT id, password FROM usuarios WHERE email='$email'";
    $resultado = mysqli_query($enlace, $query);

    // Si se encontró al menos un usuario con el email proporcionado
    if (mysqli_num_rows($resultado) > 0) {
        // Obtener los datos del usuario
        $usuario = mysqli_fetch_assoc($resultado);
        $password_de_la_bd = $usuario['password']; // Contraseña almacenada en la base de datos

        // Verificar si la contraseña proporcionada coincide con la almacenada
        if ($password === $password_de_la_bd) {
            echo "Ha iniciado sesión";
        } else {
            echo "Error en el inicio de sesión";
        }
    } else {
        echo "Usuario no encontrado";
    }
}

// Cerrar la conexión con la base de datos
mysqli_close($enlace);
?>

<!-- HTML para el formulario de inicio de sesión -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password"><br>

        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>