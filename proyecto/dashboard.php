<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: loginproyecto.php');
    exit();
}

include 'config.php';
include 'funciones.php';

mysqli_set_charset($conn, "utf8mb4");

$sql = "SELECT actividades.*, tipos.nombre AS tipo_nombre, departamentos.nombre AS departamento_nombre, profesores.nombre AS profesor_nombre
        FROM actividades
        JOIN tipos ON actividades.tipo = tipos.id
        JOIN departamentos ON actividades.departamento = departamentos.id
        JOIN profesores ON actividades.profesor_responsable = profesores.id";
$result = mysqli_query($conn, $sql);
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
        .btn-volver {
            margin-bottom: 20px;
        }
        table {
            margin-top: 20px;
        }
        .table-container {
            margin-top: 20px;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .table td {
            vertical-align: middle;
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
            <li class="list-group-item"><a href="create.php">Añadir Actividad</a></li>
        </ul>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Actividades Registradas</h1>
        </div>
        <?php if (mysqli_num_rows($result) == 0): ?>
            <div class="alert alert-warning" role="alert">
                No hay actividades registradas.
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Departamento</th>
                            <th>Profesor Responsable</th>
                            <th>Trimestre</th>
                            <th>Fecha Inicio</th>
                            <th>Hora Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Hora Fin</th>
                            <th>Organizador</th>
                            <th>Ubicación</th>
                            <th>Coste</th>
                            <th>Total Alumnos</th>
                            <th>Objetivo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo escapar($row['titulo']); ?></td>
                                <td><?php echo escapar($row['tipo_nombre']); ?></td>
                                <td><?php echo escapar($row['departamento_nombre']); ?></td>
                                <td><?php echo escapar($row['profesor_nombre']); ?></td>
                                <td><?php echo escapar($row['trimestre']); ?></td>
                                <td><?php echo escapar($row['fecha_inicio']); ?></td>
                                <td><?php echo escapar($row['hora_inicio']); ?></td>
                                <td><?php echo escapar($row['fecha_fin']); ?></td>
                                <td><?php echo escapar($row['hora_fin']); ?></td>
                                <td><?php echo escapar($row['organizador']); ?></td>
                                <td><?php echo escapar($row['ubicacion']); ?></td>
                                <td><?php echo escapar($row['coste']); ?></td>
                                <td><?php echo escapar($row['total_alumnos']); ?></td>
                                <td><?php echo escapar($row['objetivo']); ?></td>
                                <td>
                                    <a href="update.php?id=<?php echo escapar($row['id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="delete.php?id=<?php echo escapar($row['id']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
