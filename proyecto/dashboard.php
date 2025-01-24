<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: loginproyecto.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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
        <h1>IES Antonio Machado</h1>
        <h1>Bienvenido, <?php echo $_SESSION['username'];?></h1>
        <a href="logout.php" class="btn btn-danger mb-3">Cerrar Sesión</a>
        <h2 class="mt-4">Gestión de Actividades</h2>
        <ul class="list-group">
            <li class="list-group-item"><a href="read.php">Consultar Actividades</a></li>
            <li class="list-group-item"><a href="create.php">Añadir Actividad</a></li>
            <li class="list-group-item"><a href="update.php">Modificar Actividad</a></li>
            <li class="list-group-item"><a href="delete.php">Eliminar Actividad</a></li>
        </ul>
    </div>
</body>
</html>