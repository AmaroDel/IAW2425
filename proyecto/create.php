<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no está autenticado
    header('Location: loginproyecto.php');
    exit();
}

// Incluir archivos de configuración y funciones
include 'config.php';
include 'funciones.php';

// Generar un token CSRF
csrf();

// Establecer el juego de caracteres de la conexión a la base de datos
mysqli_set_charset($conn, "utf8mb4");

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar el token CSRF
    if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die('Token CSRF inválido.');
    }

    // Obtener y escapar los datos del formulario
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
    $ubicacion = mysqli_real_escape_string($conn, $_POST['ubicacion']);
    $coste = mysqli_real_escape_string($conn, $_POST['coste']);
    $total_alumnos = mysqli_real_escape_string($conn, $_POST['total_alumnos']);
    $objetivo = mysqli_real_escape_string($conn, $_POST['objetivo']);
    $acompanantes = mysqli_real_escape_string($conn, $_POST['acompanantes']); // Nuevo campo

    // Consulta SQL para insertar los datos en la base de datos
    $sql = "INSERT INTO actividades (titulo, tipo, departamento, profesor_responsable, trimestre, fecha_inicio, hora_inicio, fecha_fin, hora_fin, organizador, ubicacion, coste, total_alumnos, objetivo, acompanantes)
            VALUES ('$titulo', '$tipo', '$departamento', '$profesor_responsable', '$trimestre', '$fecha_inicio', '$hora_inicio', '$fecha_fin', '$hora_fin', '$organizador', '$ubicacion', '$coste', '$total_alumnos', '$objetivo', '$acompanantes')";

    // Ejecutar la consulta y manejar posibles errores
    if (mysqli_query($conn, $sql)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error: " . escapar($sql) . "<br>" . mysqli_error($conn);
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
    <!-- Se han eliminado las referencias a CSS y Bootstrap para simplificar el código -->
</head>
<body>
    <div>
        <!-- Título de la página y enlace para volver al dashboard -->
        <div>
            <h2>Crear Actividad</h2>
            <a href="dashboard.php">Volver al Dashboard</a>
        </div>
        <!-- Formulario para crear una nueva actividad -->
        <form method="POST" action="">
            <!-- Campo oculto para el token CSRF -->
            <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            <!-- Campos del formulario -->
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
                <label for="acompanantes">Acompañantes:</label>
                <input type="text" id="acompanantes" name="acompanantes" required>
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