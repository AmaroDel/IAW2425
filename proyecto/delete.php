<?php
// Iniciar la sesión
session_start();

// Habilitar la visualización de errores en PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user_id"])) {
    header("Location: loginproyecto.php");
    exit();
}

// Verificar si el usuario es administrador
if ($_SESSION["rol"] != 1) {
    header("Location: dashboard.php");
    exit();
}

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Generar el token CSRF
csrf();

// Procesar formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"]) && !hash_equals($_SESSION["csrf"], $_POST["csrf"])) {
        die("Token CSRF inválido.");
    }

    $id = mysqli_real_escape_string($conn, $_POST["id"]);
    $sql = "DELETE FROM actividades WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . escapar($sql) . "<br>" . mysqli_error($conn);
    }
} else {
    if (isset($_GET["id"])) {
        $id = mysqli_real_escape_string($conn, $_GET["id"]);
        $sql = "SELECT * FROM actividades WHERE id=$id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            $error_message = "No se encontró ninguna actividad con el ID proporcionado.";
        } else {
            $row = mysqli_fetch_assoc($result);
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
    <title>Eliminar Actividad</title>
    <!-- Incluir Bootstrap 5 desde un CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Eliminar Actividad</h2>
            <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php else: ?>
            <p>¿Estás seguro de que deseas eliminar la actividad "<strong><?php echo escapar($row["titulo"]); ?></strong>"?</p>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo escapar($row["id"]); ?>">
                <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION["csrf"]); ?>">
                <button type="submit" name="submit" class="btn btn-danger">Eliminar Actividad</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>
    
    <script src="modo.js"></script>

    <!-- Incluir el script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
