<?php
// Conexión a la base de datos
$servername = "*********";
$username = "*********";
$password = "*********";
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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Incluir Bootstrap 5 desde un CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Registro</h2>

                        <!-- Mostrar errores si los hay -->
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="registro.php">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos:</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password2" class="form-label">Repetir Contraseña:</label>
                                <input type="password" class="form-control" id="password2" name="password2" required>
                            </div>
                            <div class="mb-3">
                                <label for="departamento" class="form-label">Departamento:</label>
                                <select class="form-select" id="departamento" name="departamento" required>
                                    <?php
                                    // Reiniciar el puntero del resultado para asegurarnos de que se recorran todos los registros
                                    mysqli_data_seek($result_departamentos, 0);
                                    while ($row = mysqli_fetch_assoc($result_departamentos)) {
                                        echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Registrar</button>
                        </form>
                        <p class="mt-3 text-center">
                            <a href="loginproyecto.php">¿Ya estás registrado?</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir el script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>