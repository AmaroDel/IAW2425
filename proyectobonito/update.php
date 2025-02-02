<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user_id"])) {
    // Si no ha iniciado sesión, redirigir al usuario a la página de inicio de sesión
    header("Location: loginproyecto.php");
    exit();
}

// Verificar si el usuario es administrador
if ($_SESSION["rol"] != 1) {
    // Si el usuario no es administrador, redirigir al dashboard
    header("Location: dashboard.php");
    exit();
}

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Establecer el juego de caracteres de la conexión a la base de datos
mysqli_set_charset($conn, "utf8mb4");

// Generar el token CSRF
csrf();

// Procesar formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar el token CSRF
    if (
        isset($_POST["submit"]) &&
        !hash_equals($_SESSION["csrf"], $_POST["csrf"])
    ) {
        die("Token CSRF inválido.");
    }

    // Saneamiento de las entradas
    $id = mysqli_real_escape_string($conn, $_POST["id"]);
    $titulo = isset($_POST["titulo"])
        ? mysqli_real_escape_string($conn, $_POST["titulo"])
        : "";
    $tipo = isset($_POST["tipo"])
        ? mysqli_real_escape_string($conn, $_POST["tipo"])
        : "";
    $departamento = isset($_POST["departamento"])
        ? mysqli_real_escape_string($conn, $_POST["departamento"])
        : "";
    $profesor_responsable = isset($_POST["profesor_responsable"])
        ? mysqli_real_escape_string($conn, $_POST["profesor_responsable"])
        : "";
    $trimestre = isset($_POST["trimestre"])
        ? mysqli_real_escape_string($conn, $_POST["trimestre"])
        : "";
    $fecha_inicio = isset($_POST["fecha_inicio"])
        ? mysqli_real_escape_string($conn, $_POST["fecha_inicio"])
        : "";
    $hora_inicio = isset($_POST["hora_inicio"])
        ? mysqli_real_escape_string($conn, $_POST["hora_inicio"])
        : "";
    $fecha_fin = isset($_POST["fecha_fin"])
        ? mysqli_real_escape_string($conn, $_POST["fecha_fin"])
        : "";
    $hora_fin = isset($_POST["hora_fin"])
        ? mysqli_real_escape_string($conn, $_POST["hora_fin"])
        : "";
    $organizador = isset($_POST["organizador"])
        ? mysqli_real_escape_string($conn, $_POST["organizador"])
        : "";
    $acompanantes = isset($_POST["acompanantes"])
        ? mysqli_real_escape_string($conn, $_POST["acompanantes"])
        : "";
    $ubicacion = isset($_POST["ubicacion"])
        ? mysqli_real_escape_string($conn, $_POST["ubicacion"])
        : "";
    $coste = isset($_POST["coste"])
        ? mysqli_real_escape_string($conn, $_POST["coste"])
        : "";
    $total_alumnos = isset($_POST["total_alumnos"])
        ? mysqli_real_escape_string($conn, $_POST["total_alumnos"])
        : "";
    $objetivo = isset($_POST["objetivo"])
        ? mysqli_real_escape_string($conn, $_POST["objetivo"])
        : "";

    // Construir la consulta SQL para actualizar solo los campos que han cambiado
    $sql = "UPDATE actividades SET ";
    $params = [];
    $types = "";

    if ($titulo != "") {
        $sql .= "titulo = ?, ";
        $params[] = &$titulo;
        $types .= "s";
    }
    if ($tipo != "") {
        $sql .= "tipo = ?, ";
        $params[] = &$tipo;
        $types .= "s";
    }
    if ($departamento != "") {
        $sql .= "departamento = ?, ";
        $params[] = &$departamento;
        $types .= "s";
    }
    if ($profesor_responsable != "") {
        $sql .= "profesor_responsable = ?, ";
        $params[] = &$profesor_responsable;
        $types .= "s";
    }
    if ($trimestre != "") {
        $sql .= "trimestre = ?, ";
        $params[] = &$trimestre;
        $types .= "s";
    }
    if ($fecha_inicio != "") {
        $sql .= "fecha_inicio = ?, ";
        $params[] = &$fecha_inicio;
        $types .= "s";
    }
    if ($hora_inicio != "") {
        $sql .= "hora_inicio = ?, ";
        $params[] = &$hora_inicio;
        $types .= "s";
    }
    if ($fecha_fin != "") {
        $sql .= "fecha_fin = ?, ";
        $params[] = &$fecha_fin;
        $types .= "s";
    }
    if ($hora_fin != "") {
        $sql .= "hora_fin = ?, ";
        $params[] = &$hora_fin;
        $types .= "s";
    }
    if ($organizador != "") {
        $sql .= "organizador = ?, ";
        $params[] = &$organizador;
        $types .= "s";
    }
    if ($acompanantes != "") {
        $sql .= "acompanantes = ?, ";
        $params[] = &$acompanantes;
        $types .= "s";
    }
    if ($ubicacion != "") {
        $sql .= "ubicacion = ?, ";
        $params[] = &$ubicacion;
        $types .= "s";
    }
    if ($coste != "") {
        $sql .= "coste = ?, ";
        $params[] = &$coste;
        $types .= "d";
    }
    if ($total_alumnos != "") {
        $sql .= "total_alumnos = ?, ";
        $params[] = &$total_alumnos;
        $types .= "s";
    }
    if ($objetivo != "") {
        $sql .= "objetivo = ?, ";
        $params[] = &$objetivo;
        $types .= "s";
    }

    // Eliminar la coma final
    $sql = rtrim($sql, ", ");

    // Añadir la condición WHERE
    $sql .= " WHERE id = ?";
    $params[] = &$id;
    $types .= "i";

    // Preparar y ejecutar la consulta
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirigir al dashboard si la actualización fue exitosa
    header("Location: dashboard.php");
    exit();
} else {
    // Verificar si se proporcionó un ID de actividad
    if (isset($_GET["id"])) {
        $id = mysqli_real_escape_string($conn, $_GET["id"]);
        $sql = "SELECT * FROM actividades WHERE id = $id";
        $result = mysqli_query($conn, $sql);

        // Verificar si se encontró la actividad
        if (mysqli_num_rows($result) == 0) {
            $error_message =
                "No se encontró ninguna actividad con el ID proporcionado.";
        } else {
            $row = mysqli_fetch_assoc($result);

            // Obtener datos para los selects
            $sql_departamentos = "SELECT id, nombre FROM departamentos";
            $result_departamentos = mysqli_query($conn, $sql_departamentos);

            $sql_profesores = "SELECT id, nombre FROM profesores";
            $result_profesores = mysqli_query($conn, $sql_profesores);

            $sql_tipos = "SELECT id, nombre FROM tipos";
            $result_tipos = mysqli_query($conn, $sql_tipos);
        }
    } else {
        $error_message = "No se proporcionó ningún ID de actividad.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Actividad</title>
    <!-- Enlace a Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Actualizar Actividad</h2>

                        <!-- Botón para volver al Dashboard -->
                        <a href="dashboard.php" class="btn btn-secondary mb-3">Volver al Dashboard</a>

                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php else: ?>
                            <form method="POST" action="">
                                <!-- Campo oculto para el ID de la actividad -->
                                <input type="hidden" name="id" value="<?php echo escapar($row["id"]); ?>">
                                <!-- Campo oculto para el token CSRF -->
                                <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION["csrf"]); ?>">

                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título:</label>
                                    <input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo escapar($row["titulo"]); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="tipo" class="form-label">Tipo:</label>
                                    <select id="tipo" name="tipo" class="form-select">
                                        <?php while ($tipo_row = mysqli_fetch_assoc($result_tipos)): ?>
                                            <option value="<?php echo escapar($tipo_row["id"]); ?>" <?php if ($tipo_row["id"] == $row["tipo"]) { echo "selected"; } ?>>
                                                <?php echo escapar($tipo_row["nombre"]); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="departamento" class="form-label">Departamento:</label>
                                    <select id="departamento" name="departamento" class="form-select">
                                        <?php mysqli_data_seek($result_departamentos, 0); ?>
                                        <?php while ($departamento_row = mysqli_fetch_assoc($result_departamentos)): ?>
                                            <option value="<?php echo escapar($departamento_row["id"]); ?>" <?php if ($departamento_row["id"] == $row["departamento"]) { echo "selected"; } ?>>
                                                <?php echo escapar($departamento_row["nombre"]); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="profesor_responsable" class="form-label">Profesor Responsable:</label>
                                    <select id="profesor_responsable" name="profesor_responsable" class="form-select">
                                        <?php mysqli_data_seek($result_profesores, 0); ?>
                                        <?php while ($profesor_row = mysqli_fetch_assoc($result_profesores)): ?>
                                            <option value="<?php echo escapar($profesor_row["id"]); ?>" <?php if ($profesor_row["id"] == $row["profesor_responsable"]) { echo "selected"; } ?>>
                                                <?php echo escapar($profesor_row["nombre"]); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="trimestre" class="form-label">Trimestre:</label>
                                    <select id="trimestre" name="trimestre" class="form-select">
                                        <option value="1" <?php if ($row["trimestre"] == 1) { echo "selected"; } ?>>1</option>
                                        <option value="2" <?php if ($row["trimestre"] == 2) { echo "selected"; } ?>>2</option>
                                        <option value="3" <?php if ($row["trimestre"] == 3) { echo "selected"; } ?>>3</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo escapar($row["fecha_inicio"]); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="hora_inicio" class="form-label">Hora Inicio:</label>
                                    <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" value="<?php echo escapar($row["hora_inicio"]); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?php echo escapar($row["fecha_fin"]); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="hora_fin" class="form-label">Hora Fin:</label>
                                    <input type="time" id="hora_fin" name="hora_fin" class="form-control" value="<?php echo escapar($row["hora_fin"]); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="organizador" class="form-label">Organizador:</label>
                                    <input type="text" id="organizador" name="organizador" class="form-control" value="<?php echo escapar($row["organizador"]); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="acompanantes" class="form-label">Acompañantes:</label>
                                    <textarea id="acompanantes" name="acompanantes" class="form-control"><?php echo escapar($row["acompanantes"]); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="ubicacion" class="form-label">Ubicación:</label>
                                    <input type="text" id="ubicacion" name="ubicacion" class="form-control" value="<?php echo escapar($row["ubicacion"]); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="coste" class="form-label">Coste:</label>
                                    <input type="number" step="0.01" id="coste" name="coste" class="form-control" value="<?php echo escapar($row["coste"]); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="total_alumnos" class="form-label">Total Alumnos:</label>
                                    <input type="number" id="total_alumnos" name="total_alumnos" class="form-control" value="<?php echo escapar($row["total_alumnos"]); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="objetivo" class="form-label">Objetivo:</label>
                                    <textarea id="objetivo" name="objetivo" class="form-control"><?php echo escapar($row["objetivo"]); ?></textarea>
                                </div>

                                <!-- Botón para enviar el formulario -->
                                <button type="submit" name="submit" class="btn btn-primary w-100">Actualizar Actividad</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir el script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>