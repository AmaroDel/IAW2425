<?php
// Conexión a la base de datos
$servername = "sql308.thsite.top";
$username = "thsi_38097484";
$password = "A1EbhYLa";
$database = "thsi_38097484_ejemplo";
$enlace = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$enlace) {
    die("Conexión fallida: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar campos vacíos
    if (empty($_POST['nombre']) || empty($_POST['apellidos']) || empty($_POST['email']) || empty($_POST['password'])) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Saneamiento de las entradas
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $apellidos = htmlspecialchars(trim($_POST['apellidos']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    //Ver si el usuario existe
    $query = "SELECT id FROM usuarios WHERE email='$email'";
    $resultado = mysqli_query($enlace, $query);
     //Ponemos la password que nos ha puesto y la que teniamos de la bd en variables
    if (mysqli_num_rows($resultado)> 0){
        $usuario = mysqli_fetch_assoc($resultado);
        $password_de_la_bd = $usuario['password']; // Contraseña almacenada

        // Verificar la contraseña
        if {($password === $password_de_la_bd)
        echo "Ha iniciado sesión";
        }else {
            echo "Error en el inicio de sesión";
        }

    }else {
        echo "Usuario no encontrado";
    }

}

//Cierre de la conexión
mysqli_close($enlace);
?>

<!-- Html del login -->
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
