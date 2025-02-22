<?php
// Iniciar la sesión
session_start();

// Habilitar la visualización de errores en PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

// Verificar si el usuario está autenticado
if (!isset($_SESSION["user_id"])) {
    // Redirigir al usuario a la página de inicio de sesión si no está autenticado
    header("Location: loginproyecto.php");
    exit();
}

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Generar un token CSRF
csrf();

// Establecer el juego de caracteres de la conexión a la base de datos
mysqli_set_charset($conn, "utf8mb4");

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar el token CSRF
    if (
        isset($_POST["submit"]) &&
        !hash_equals($_SESSION["csrf"], $_POST["csrf"])
    ) {
        die("Token CSRF inválido.");
    }

    // Obtener y escapar los datos del formulario
    $titulo = mysqli_real_escape_string($conn, $_POST["titulo"]);
    $tipo = mysqli_real_escape_string($conn, $_POST["tipo"]);
    $departamento = mysqli_real_escape_string($conn, $_POST["departamento"]);
    $profesor_responsable = mysqli_real_escape_string(
        $conn,
        $_POST["profesor_responsable"]
    );
    $trimestre = mysqli_real_escape_string($conn, $_POST["trimestre"]);
    $fecha_inicio = mysqli_real_escape_string($conn, $_POST["fecha_inicio"]);
    $hora_inicio = mysqli_real_escape_string($conn, $_POST["hora_inicio"]);
    $fecha_fin = mysqli_real_escape_string($conn, $_POST["fecha_fin"]);
    $hora_fin = mysqli_real_escape_string($conn, $_POST["hora_fin"]);
    $organizador = mysqli_real_escape_string($conn, $_POST["organizador"]);
    $ubicacion = mysqli_real_escape_string($conn, $_POST["ubicacion"]);
    $coste = mysqli_real_escape_string($conn, $_POST["coste"]);
    $total_alumnos = mysqli_real_escape_string($conn, $_POST["total_alumnos"]);
    $objetivo = mysqli_real_escape_string($conn, $_POST["objetivo"]);
    $acompanantes = mysqli_real_escape_string($conn, $_POST["acompanantes"]); // Nuevo campo

    // Obtener el ID del usuario autenticado
    $creador = $_SESSION["user_id"];

    // Consulta SQL para insertar los datos en la base de datos
    $sql = "INSERT INTO actividades (titulo, tipo, departamento, profesor_responsable, trimestre, fecha_inicio, hora_inicio, fecha_fin, hora_fin, organizador, ubicacion, coste, total_alumnos, objetivo, acompanantes, creador)
        VALUES ('$titulo', '$tipo', '$departamento', '$profesor_responsable', '$trimestre', '$fecha_inicio', '$hora_inicio', '$fecha_fin', '$hora_fin', '$organizador', '$ubicacion', '$coste', '$total_alumnos', '$objetivo', '$acompanantes', '$creador')";

    $error = "";

    // Verificar que la fecha y hora de inicio sean anteriores a la de fin
    if (
        strtotime($fecha_inicio . " " . $hora_inicio) >=
        strtotime($fecha_fin . " " . $hora_fin)
    ) {
        $error =
            "Error: La fecha y hora de inicio deben ser anteriores a la fecha y hora de fin.";
    }

    // Validar que el coste sea >= 0
    if (!is_numeric($coste) || $coste < 0) {
        $error = "Error: El coste debe ser un número mayor o igual a 0.";
    }

    // Validar que el total de alumnos sea un número entero mayor que 0
    if (
        !filter_var($total_alumnos, FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1],
        ])
    ) {
        $error = "Error: El número de alumnos debe ser un entero mayor que 0.";
    }

    // Si hay un error, no ejecutar la consulta y mostrar el mensaje
    if (!empty($error)) {
        echo "<p style='color: red;'>$error</p>";
    } else {
        // Ejecutar la consulta solo si no hay errores
        if (mysqli_query($conn, $sql)) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . escapar($sql) . "<br>" . mysqli_error($conn);
        }
    }
}

// Obtener datos para los selects del formulario
$sql_departamentos = "SELECT id, nombre FROM departamentos";
$result_departamentos = mysqli_query($conn, $sql_departamentos);

$sql_profesores = "SELECT id, nombre FROM profesores";
$result_profesores = mysqli_query($conn, $sql_profesores);

$sql_tipos = "SELECT id, nombre FROM tipos";
$result_tipos = mysqli_query($conn, $sql_tipos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Actividad</title>
    <!-- Agregar el CDN de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <div class="mt-5">
        <!-- Título de la página y enlace para volver al dashboard -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Crear Actividad</h2>
            <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
        <!-- Formulario para crear una nueva actividad -->
        <form method="POST" action="" class="needs-validation" novalidate>
            <!-- Campo oculto para el token CSRF -->
            <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION["csrf"]); ?>">
            
            <!-- Campos del formulario -->
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    <?php while ($row = mysqli_fetch_assoc($result_tipos)): ?>
                        <option value="<?php echo escapar($row["id"]); ?>"><?php echo escapar($row["nombre"]); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="departamento">Departamento:</label>
                <select id="departamento" name="departamento" class="form-control" required>
                    <?php mysqli_data_seek($result_departamentos, 0); ?>
                    <?php while ($row = mysqli_fetch_assoc($result_departamentos)): ?>
                        <option value="<?php echo escapar($row["id"]); ?>"><?php echo escapar($row["nombre"]); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="profesor_responsable">Profesor Responsable:</label>
                <select id="profesor_responsable" name="profesor_responsable" class="form-control" required>
                    <?php mysqli_data_seek($result_profesores, 0); ?>
                    <?php while ($row = mysqli_fetch_assoc($result_profesores)): ?>
                        <option value="<?php echo escapar($row["id"]); ?>"><?php echo escapar($row["nombre"]); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="trimestre">Trimestre:</label>
                <select id="trimestre" name="trimestre" class="form-control" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>

            <div class="form-group">
                <label for="fecha_inicio">Fecha Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="hora_inicio">Hora Inicio:</label>
                <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="fecha_fin">Fecha Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="hora_fin">Hora Fin:</label>
                <input type="time" id="hora_fin" name="hora_fin" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="organizador">Organizador:</label>
                <input type="text" id="organizador" name="organizador" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="coste">Coste:</label>
                <input type="number" step="0.01" id="coste" name="coste" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="total_alumnos">Total Alumnos:</label>
                <input type="number" id="total_alumnos" name="total_alumnos" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="acompanantes">Acompañantes:</label>
                <input type="text" id="acompanantes" name="acompanantes" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="objetivo">Objetivo:</label>
                <textarea id="objetivo" name="objetivo" class="form-control" required></textarea>
            </div>

            <!-- Botón para enviar el formulario -->
            <button type="submit" name="submit" class="btn btn-primary">Crear Actividad</button>
        </form>
    </div>

    <!-- Agregar los scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>