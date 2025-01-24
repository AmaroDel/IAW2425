<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: loginproyecto.php');
    exit();
}

include 'config.php';
include 'funciones.php';
csrf();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die('Token CSRF inválido.');
    }

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM actividades WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error: " . escapar($sql) . "<br>" . mysqli_error($conn);
    }
} else {
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
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
    <title>Eliminar Actividad</title>
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
            <h2 class="mb-0">Eliminar Actividad</h2>
            <a href="dashboard.php" class="btn btn-primary">Volver al Dashboard</a> <!-- Botón para volver al dashboard -->
        </div>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php else: ?>
            <p>¿Estás seguro de que deseas eliminar la actividad "<?php echo escapar($row['titulo']); ?>"?</p>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo escapar($row['id']); ?>">
                <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
                <button type="submit" name="submit" class="btn btn-danger">Eliminar Actividad</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
