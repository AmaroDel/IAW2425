<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: loginproyecto.php');
    exit();
}

include 'config.php';
include 'funciones.php';
csrf();

mysqli_set_charset($conn, "utf8mb4");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die('Token CSRF inválido.');
    }

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

    $sql = "INSERT INTO actividades (titulo, tipo, departamento, profesor_responsable, trimestre, fecha_inicio, hora_inicio, fecha_fin, hora_fin, organizador, ubicacion, coste, total_alumnos, objetivo)
            VALUES ('$titulo', '$tipo', '$departamento', '$profesor_responsable', '$trimestre', '$fecha_inicio', '$hora_inicio', '$fecha_fin', '$hora_fin', '$organizador', '$ubicacion', '$coste', '$total_alumnos', '$objetivo')";

    if (mysqli_query($conn, $sql)) {
        header('Location: dashboard.php');
        exit();
    } else {
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Fondo claro */
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Crear Actividad</h2>
            <a href="dashboard.php" class="btn btn-primary">Volver al Dashboard</a> <!-- Botón para volver al dashboard -->
        </div>
        <form method="POST" action="">
            <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <?php while ($row = mysqli_fetch_assoc($result_tipos)): ?>
                        <option value="<?php echo escapar($row['id']); ?>"><?php echo escapar($row['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="departamento">Departamento:</label>
                <select class="form-control" id="departamento" name="departamento" required>
                    <?php mysqli_data_seek($result_departamentos, 0); // Reiniciar el puntero del resultado
                    while ($row = mysqli_fetch_assoc($result_departamentos)): ?>
                        <option value="<?php echo escapar($row['id']); ?>"><?php echo escapar($row['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="profesor_responsable">Profesor Responsable:</label>
                <select class="form-control" id="profesor_responsable" name="profesor_responsable" required>
                    <?php mysqli_data_seek($result_profesores, 0); // Reiniciar el puntero del resultado
                    while ($row = mysqli_fetch_assoc($result_profesores)): ?>
                        <option value="<?php echo escapar($row['id']); ?>"><?php echo escapar($row['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="trimestre">Trimestre:</label>
                <select class="form-control" id="trimestre" name="trimestre" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_inicio">Fecha Inicio:</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="form-group">
                <label for="hora_inicio">Hora Inicio:</label>
                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha Fin:</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
            </div>
            <div class="form-group">
                <label for="hora_fin">Hora Fin:</label>
                <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
            </div>
            <div class="form-group">
                <label for="organizador">Organizador:</label>
                <input type="text" class="form-control" id="organizador" name="organizador" required>
            </div>
            <div class="form-group">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
            </div>
            <div class="form-group">
                <label for="coste">Coste:</label>
                <input type="number" step="0.01" class="form-control" id="coste" name="coste" required>
            </div>
            <div class="form-group">
                <label for="total_alumnos">Total Alumnos:</label>
                <input type="number" class="form-control" id="total_alumnos" name="total_alumnos" required>
            </div>
            <div class="form-group">
                <label for="objetivo">Objetivo:</label>
                <textarea class="form-control" id="objetivo" name="objetivo" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Crear Actividad</button>
        </form>
    </div>
</body>
</html>