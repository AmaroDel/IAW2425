<?php
// Iniciar sesión antes que cualquier otra cosa
session_start();

// Habilitar la visualización de errores en PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

// Conexión a la base de datos
$servername = "*******";
$username = "*******";
$password = "*******";
$database = "*******";
$enlace = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$enlace) {
    die("Conexión fallida: " . mysqli_connect_error());
}

mysqli_set_charset($enlace, "utf8mb4");

// Procesar formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["email"]) || empty($_POST["password"])) {
        die("Error: Todos los campos son obligatorios.");
    }

    $email = htmlspecialchars(trim($_POST["email"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    $query = "SELECT * FROM registrados WHERE email=?";
    $stmt = mysqli_prepare($enlace, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) === 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        if (hash_equals($usuario["password"], crypt($password, $usuario["password"]))) {
            $_SESSION["user_id"] = $usuario["id"];
            $_SESSION["username"] = $usuario["nombre"];
            $_SESSION["rol"] = $usuario["rol"];

            // Guardamos la última conexión antes de actualizarla
            $_SESSION["ultima_conexion"] = $usuario["ultima_conexion"];
            
            // Obtenemos la fecha y hora actual en formato Día-Mes-Año Hora:Minutos:Segundos
            date_default_timezone_set('Europe/Madrid');
            $date = date('d-m-Y H:i:s');

            // Actualizar la última conexión a la fecha y hora actual
            mysqli_query($enlace, "SET time_zone = 'Europe/Madrid'");
            $sql_update = "UPDATE registrados SET ultima_conexion = NOW() WHERE id = ?";
            $stmt_update = mysqli_prepare($enlace, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "i", $usuario["id"]);
            mysqli_stmt_execute($stmt_update);
            mysqli_stmt_close($stmt_update);

            // Formateamos el registro que se guardará en el archivo
            $logEntry = "[$date] Usuario ID: " . ($usuario['nombre'] ?? 'Desconocido') . " accedió al sistema\n";
            
            // Escribimos el registro en el archivo logs.txt
            file_put_contents('logs.txt', $logEntry, FILE_APPEND);
            header("Location: dashboard.php");
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
    <!-- Incluir Bootstrap 5 desde un CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Iniciar Sesión</h2>
                        <form method="POST" action="loginproyecto.php">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                        </form>
                        <p class="mt-3 text-center">
                            <a href="registro.php">¿No estás registrado?</a>
                        </p>
                        <p class="mt-2 text-center">
                            <a href="recuperar.php" class="text-decoration-none">¿Has olvidado tu contraseña?</a>
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