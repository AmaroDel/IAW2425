<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Si no ha iniciado sesión, redirigir al usuario a la página de inicio de sesión
    header('Location: loginproyecto.php');
    exit();
}

// Incluir archivos de configuración y funciones
include 'config.php';
include 'funciones.php';

// Generar el token CSRF
csrf();

// Establecer el juego de caracteres a utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Procesar formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar el token CSRF
    if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die('Token CSRF inválido.');
    }

    // Saneamiento de las entradas
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $tipo = mysqli_real_escape_string($conn, $_POST['tipo']);
    $departamento = mysqli_real_escape_string($conn, $_POST['departamento']);
    $profesor_responsable = mysqli_real_escape_string($conn, $_POST['profesor_responsable']);
    $trimestre = mysqli_real_escape_string($conn, $_POST['trimestre']);
    $fecha_inicio = mysqli_real_escape_string($conn, $_POST['fecha_inicio']);
    $hora_inicio = mysqli_real_escape_string($conn, $_POST['hora_inicio']);
    $fecha_fin = mysqli_real_escape_string($conn, $_POST['fecha_fin']);
    $hora_fin = mysqli_real_escape_string($conn, $_POST['hora_fin']);
    $organizador = mysqli_real_escape_string($conn, $_POST['organizador']);
    $acompanantes = mysqli_real_escape_string($conn, $_POST['acompanantes']);
    $ubicacion = mysqli_real_escape_string($conn, $_POST['ubicacion']);
    $coste = mysqli_real_escape_string($conn, $_POST['coste']);
    $total_alumnos = mysqli_real_escape_string($conn, $_POST['total_alumnos']);
    $objetivo = mysqli_real_escape_string($conn, $_POST['objetivo']);

    // Consulta SQL para insertar nueva actividad
    $sql = "INSERT INTO actividades (titulo, tipo, departamento, profesor_responsable, trimestre, fecha_inicio, hora_inicio, fecha_fin, hora_fin, organizador, acompañantes, ubicacion, coste, total_alumnos, objetivo)
            VALUES ('$titulo', '$tipo', '$departamento', '$profesor_responsable', '$trimestre', '$fecha_inicio', '$hora_inicio', '$fecha_fin', '$hora_fin', '$organizador', '$acompanantes', '$ubicacion', '$coste', '$total_alumnos', '$objetivo')";

    // Ejecutar la consulta y verificar si fue exitosa
    if (mysqli_query($conn, $sql)) {
        // Redirigir al dashboard si la inserción fue exitosa
        header('Location: dashboard.php');
        exit();
    } else {
        // Mostrar mensaje de error si la inserción falló
        echo "Error: " . escapar($sql) . "<br>" . mysqli_error($conn);
    }
}

// Obtener datos para los selects
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
</head>
<body>
    <div>
        <div>
            <h2>Crear Actividad</h2>
            <!-- Botón para volver al dashboard -->
            <a href="dashboard.php">Volver al Dashboard</a>
        </div>
        <!-- Formulario para crear una nueva actividad -->
        <form method="POST" action="">
            <!-- Campo oculto para el token CSRF -->
            <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            <div>
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div>
                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" required>
                    <?php while ($row = mysqli_fetch_assoc($result_tipos)): ?>
                        <option value="<?php echo escapar($row['id']); ?>"><?php echo escapar($row['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="departamento">Departamento:</label>
                <select id="departamento" name="departamento" required>
                    <?php mysqli_data_seek($result_departamentos, 0); // Reiniciar el puntero del resultado
                    while ($row = mysqli_fetch_assoc($result_departamentos)): ?>
                        <option value="<?php echo escapar($row['id']); ?>"><?php echo escapar($row['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="profesor_responsable">Profesor Responsable:</label>
                <select id="profesor_responsable" name="profesor_responsable" required>
                    <?php mysqli_data_seek($result_profesores, 0); // Reiniciar el puntero del resultado
                    while ($row = mysqli_fetch_assoc($result_profesores)): ?>
                        <option value="<?php echo escapar($row['id']); ?>"><?php echo escapar($row['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="trimestre">Trimestre:</label>
                <select id="trimestre" name="trimestre" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <div>
                <label for="fecha_inicio">Fecha Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div>
                <label for="hora_inicio">Hora Inicio:</label>
                <input type="time" id="hora_inicio" name="hora_inicio" required>
            </div>
            <div>
                <label for="fecha_fin">Fecha Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required>
            </div>
            <div>
                <label for="hora_fin">Hora Fin:</label>
                <input type="time" id="hora_fin" name="hora_fin" required>
            </div>
            <div>
                <label for="organizador">Organizador:</label>
                <input type="text" id="organizador" name="organizador" required>
            </div>
            <div>
                <label for="acompanantes">Acompañantes:</label>
                <textarea id="acompanantes" name="acompanantes"></textarea>
            </div>
            <div>
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" required>
            </div>
            <div>
                <label for="coste">Coste:</label>
                <input type="number" step="0.01" id="coste" name="coste" required>
            </div>
            <div>
                <label for="total_alumnos">Total Alumnos:</label>
                <input type="number" id="total_alumnos" name="total_alumnos" required>
            </div>
            <div>
                <label for="objetivo">Objetivo:</label>
                <textarea id="objetivo" name="objetivo" required></textarea>
            </div>
            <!-- Botón para enviar el formulario -->
            <button type="submit" name="submit">Crear Actividad</button>
        </form>
    </div>
</body>
</html>