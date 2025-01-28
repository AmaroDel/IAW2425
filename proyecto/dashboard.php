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

// Establecer el juego de caracteres a utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Consulta SQL para obtener todas las actividades con nombres de tipos, departamentos y profesores
$sql = "SELECT actividades.*, tipos.nombre AS tipo_nombre, departamentos.nombre AS departamento_nombre, profesores.nombre AS profesor_nombre
        FROM actividades
        JOIN tipos ON actividades.tipo = tipos.id
        JOIN departamentos ON actividades.departamento = departamentos.id
        JOIN profesores ON actividades.profesor_responsable = profesores.id";

// Ejecutar la consulta
$resultado = mysqli_query($conn, $sql);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    // Si la consulta falla, mostrar un mensaje de error y detener la ejecución del script
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <div>
        <h2>Dashboard de Actividades</h2>
        <!-- Enlace para añadir una nueva actividad -->
        <a href="nueva_actividad.php">Añadir Nueva Actividad</a>
        <!-- Enlace para cerrar sesión -->
        <a href="logout.php">Cerrar Sesión</a>
        <!-- Tabla para mostrar las actividades -->
        <table border="1">
            <thead>
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
                    <th>Acompañantes</th>
                    <th>Ubicación</th>
                    <th>Coste</th>
                    <th>Total Alumnos</th>
                    <th>Objetivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Iterar sobre los resultados de la consulta -->
                <?php while ($actividad = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo escapar($actividad['titulo']); ?></td>
                        <td><?php echo escapar($actividad['tipo_nombre']); ?></td>
                        <td><?php echo escapar($actividad['departamento_nombre']); ?></td>
                        <td><?php echo escapar($actividad['profesor_nombre']); ?></td>
                        <td><?php echo escapar($actividad['trimestre']); ?></td>
                        <td><?php echo escapar($actividad['fecha_inicio']); ?></td>
                        <td><?php echo escapar($actividad['hora_inicio']); ?></td>
                        <td><?php echo escapar($actividad['fecha_fin']); ?></td>
                        <td><?php echo escapar($actividad['hora_fin']); ?></td>
                        <td><?php echo escapar($actividad['organizador']); ?></td>
                        <td><?php echo escapar($actividad['acompañantes']); ?></td>
                        <td><?php echo escapar($actividad['ubicacion']); ?></td>
                        <td><?php echo escapar($actividad['coste']); ?></td>
                        <td><?php echo escapar($actividad['total_alumnos']); ?></td>
                        <td><?php echo escapar($actividad['objetivo']); ?></td>
                        <td>
                            <!-- Enlace para editar la actividad -->
                            <a href="update.php?id=<?php echo escapar($actividad['id']); ?>">Editar</a>
                            <!-- Enlace para eliminar la actividad -->
                            <a href="delete.php?id=<?php echo escapar($actividad['id']); ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
