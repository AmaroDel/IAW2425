<?php
// Conexión a la base de datos
$servername = "*******";
$username = "*********";
$password = "***********";
$database = "*********";
$enlace = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$enlace) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Establecer el juego de caracteres de la conexión a la base de datos
mysqli_set_charset($enlace, "utf8mb4");

// Obtener datos para los selects del formulario
$sql_departamentos = "SELECT id, nombre FROM departamentos";
$result_departamentos = mysqli_query($enlace, $sql_departamentos);

// Verificar si la consulta fue exitosa
if (!$result_departamentos) {
    die("Error al obtener los departamentos: " . mysqli_error($conn));
}

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar campos vacíos
    if (
        empty($_POST["nombre"]) ||
        empty($_POST["apellidos"]) ||
        empty($_POST["email"]) ||
        empty($_POST["password"])
    ) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Saneamiento de las entradas
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $apellidos = htmlspecialchars(trim($_POST["apellidos"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = htmlspecialchars(trim($_POST["password"]));
    $password2 = htmlspecialchars(trim($_POST["password2"]));
    $departamento = htmlspecialchars(trim($_POST["departamento"]));

    // Validar que el email pertenece al dominio @iesamachado.org
    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        substr(strrchr($email, "@"), 1) !== "iesamachado.org"
    ) {
        $error = "El email debe pertenecer al dominio @iesamachado.org.";
    }
    // Verificar que las contraseñas coincidan
    elseif ($password !== $password2) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el usuario ya existe
        $query = "SELECT id FROM registrados WHERE email='$email'";
        $resultado = mysqli_query($enlace, $query);

        if (mysqli_num_rows($resultado) > 0) {
            $error = "Error, el usuario ya está registrado.</p>";
        } else {
            // Cifrar la contraseña
            //$password_encrypted = $password; // Sin cifrar (GRAN ERROR)
            $password_encrypted = crypt(
                $password,
                '$6$rounds=5000$' . uniqid(mt_rand(), true) . '$'
            );

            // Insertar datos en la base de datos
            $query = "INSERT INTO registrados (nombre, apellidos, email, password, departamento) 
        VALUES ('$nombre', '$apellidos', '$email', '$password_encrypted', '$departamento')";

            if (mysqli_query($enlace, $query)) {
                // Enviar correo electrónico de confirmación
                $asunto = "Registro exitoso";
                $mensaje = "Hola $nombre,\n\nGracias por registrarte. Estos son tus datos:\nNombre: $nombre\nApellidos: $apellidos\nEmail: $email\n\nSaludos.";
                $cabeceras = "From: no-reply@mi-sitio.com";
                if (mail($email, $asunto, $mensaje, $cabeceras)) {
                    echo "<p style='color: green;'>Usuario registrado correctamente. Se ha enviado un correo de confirmación.</p>";
                } else {
                    echo "<p style='color: orange;'>Usuario registrado, pero no se pudo enviar el correo.</p>";
                }
            } else {
                $error =
                    "Error al registrar el usuario: " . mysqli_error($enlace);
            }
        }
    }
}

mysqli_close($enlace);
?>


<!-- Formulario de registro -->
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>

    <h2>Registro</h2>

    <?php if (!empty($error)) {
        echo "<p style='color: red;'>$error</p>";
    } ?>

    <form method="POST" action="registro.php">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre"><br>
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos"><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password"><br>
        <label for="password2">Repetir Contraseña:</label>
        <input type="password" id="password2" name="password2"><br>
        <label for="departamento">Departamento:</label>
        <select id="departamento" name="departamento">
            <?php
            // Reiniciar el puntero del resultado para asegurarnos de que se recorran todos los registros
            mysqli_data_seek($result_departamentos, 0);
            while ($row = mysqli_fetch_assoc($result_departamentos)) {
                echo "<option value='" .
                    $row["id"] .
                    "'>" .
                    $row["nombre"] .
                    "</option>";
            }
            ?>
        </select><br>
        <button type="submit">Registrar</button>
    </form>
    <p>
    <a href="loginproyecto.php">¿Ya estás registrado?</a>
    </p>
</body>
</html>