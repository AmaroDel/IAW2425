<?php
// Iniciar la sesión
session_start();

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Establecer el juego de caracteres para evitar problemas con caracteres especiales
mysqli_set_charset($conn, "utf8mb4");

// Consulta SQL para obtener estadísticas de actividades por departamento y trimestre
$sql = "SELECT 
            d.nombre AS departamento,  -- Obtener el nombre del departamento
            SUM(a.aprobada = 1) AS aprobadas,  -- Contar actividades aprobadas
            SUM(a.aprobada = 0) AS no_aprobadas,  -- Contar actividades no aprobadas
            SUM(a.trimestre = 1) AS trimestre_1,  -- Contar actividades del trimestre 1
            SUM(a.trimestre = 2) AS trimestre_2,  -- Contar actividades del trimestre 2
            SUM(a.trimestre = 3) AS trimestre_3  -- Contar actividades del trimestre 3
        FROM actividades a, departamentos d 
        WHERE a.departamento = d.id  -- Unir con la tabla departamentos para obtener el nombre
        GROUP BY d.id, d.nombre";  // Agrupar por departamento para mostrar estadísticas por cada uno

// Ejecutar la consulta y almacenar el resultado
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de Actividades</title>
    <!-- Incluir Bootstrap para diseño responsivo y mejor apariencia -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Estadísticas de Actividades</h2>

    <!-- Tabla para mostrar las estadísticas -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Departamento</th>
                <th>Aprobadas</th>
                <th>No Aprobadas</th>
                <th>Trimestre 1</th>
                <th>Trimestre 2</th>
                <th>Trimestre 3</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Recorrer cada fila del resultado de la consulta y mostrar los datos en la tabla
            while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo escapar($row["departamento"]); ?></td>  <!-- Mostrar el nombre del departamento -->
                    <td><?php echo escapar($row["aprobadas"]); ?></td>  <!-- Mostrar número de actividades aprobadas -->
                    <td><?php echo escapar($row["no_aprobadas"]); ?></td>  <!-- Mostrar número de actividades no aprobadas -->
                    <td><?php echo escapar($row["trimestre_1"]); ?></td>  <!-- Mostrar número de actividades del trimestre 1 -->
                    <td><?php echo escapar($row["trimestre_2"]); ?></td>  <!-- Mostrar número de actividades del trimestre 2 -->
                    <td><?php echo escapar($row["trimestre_3"]); ?></td>  <!-- Mostrar número de actividades del trimestre 3 -->
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Botón para regresar a la pantalla de gestión de actividades -->
    <a href="dashboard.php" class="btn btn-secondary">Volver a Gestión de Actividades</a>

    <!-- Incluir jQuery y Bootstrap para funcionalidades adicionales -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
