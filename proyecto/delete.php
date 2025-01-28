<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: loginproyecto.php');
    exit();
}

// Incluir archivos de configuración y funciones
include 'config.php';
include 'funciones.php';

// Generar el token CSRF
csrf();

// Procesar formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar el token CSRF
    if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die('Token CSRF inválido.');
    }

    // Obtener el ID de la actividad a eliminar
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // Consulta SQL para eliminar la actividad
    $sql = "DELETE FROM actividades WHERE id=$id";

    // Ejecutar la consulta y verificar si fue exitosa
    if (mysqli_query($conn, $sql)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error: " . escapar($sql) . "<br>" . mysqli_error($conn);
    }
} else {
    // Verificar si se proporcionó un ID de actividad
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $sql = "SELECT * FROM actividades WHERE id=$id";
        $result = mysqli_query($conn, $sql);

        // Verificar si se encontró la actividad
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
    <title>Eliminar Actividad</title>
</head>
<body>
    <div>
        <div>
            <h2>Eliminar Actividad</h2>
            <!-- Botón para volver al dashboard -->
            <a href="dashboard.php">Volver al Dashboard</a>
        </div>
        <?php if (isset($error_message)): ?>
            <div>
                <?php echo $error_message; ?>
            </div>
        <?php else: ?>
            <p>¿Estás seguro de que deseas eliminar la actividad "<?php echo escapar($row['titulo']); ?>"?</p>
            <form method="POST" action="">
                <!-- Campo oculto para el ID de la actividad -->
                <input type="hidden" name="id" value="<?php echo escapar($row['id']); ?>">
                <!-- Campo oculto para el token CSRF -->
                <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
                <!-- Botón para enviar el formulario -->
                <button type="submit" name="submit">Eliminar Actividad</button>
                <!-- Botón para cancelar la eliminación -->
                <a href="dashboard.php">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>