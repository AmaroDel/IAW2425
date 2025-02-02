<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user_id"])) {
    // Si no ha iniciado sesión, redirigir al usuario a la página de inicio de sesión
    header("Location: loginproyecto.php");
    exit();
}

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Establecer el juego de caracteres a utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Obtener información del usuario
$usuario = $_SESSION;

// Capturar los parámetros de ordenación desde la URL
$column = isset($_GET["column"]) ? $_GET["column"] : "id";
$order = isset($_GET["order"]) ? $_GET["order"] : "ASC";

// Alternar entre ASC y DESC
$order = $order === "ASC" ? "DESC" : "ASC";

// Consulta SQL con orden dinámico
$sql = "SELECT actividades.*, 
               tipos.nombre AS tipo_nombre, 
               departamentos.nombre AS departamento_nombre, 
               profesores.nombre AS profesor_nombre
        FROM actividades
        JOIN tipos ON actividades.tipo = tipos.id
        JOIN departamentos ON actividades.departamento = departamentos.id
        JOIN profesores ON actividades.profesor_responsable = profesores.id
        ORDER BY $column $order";

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
        <a href="create.php">Añadir Nueva Actividad</a>
        <!-- Enlace para cerrar sesión -->
        <a href="logout.php">Cerrar Sesión</a>
        <!-- Tabla para mostrar las actividades -->
        <table border="1">
            <thead>
                <tr>
                <th><a href="?column=titulo&order=<?= $order ?>">Título</a></th>
                    <th><a href="?column=tipo_nombre&order=<?= $order ?>">Tipo</a></th>
                    <th><a href="?column=departamento_nombre&order=<?= $order ?>">Departamento</a></th>
                    <th><a href="?column=profesor_nombre&order=<?= $order ?>">Profesor Responsable</a></th>
                    <th><a href="?column=trimestre&order=<?= $order ?>">Trimestre</a></th>
                    <th><a href="?column=fecha_inicio&order=<?= $order ?>">Fecha Inicio</a></th>
                    <th><a href="?column=hora_inicio&order=<?= $order ?>">Hora Inicio</a></th>
                    <th><a href="?column=fecha_fin&order=<?= $order ?>">Fecha Fin</a></th>
                    <th><a href="?column=hora_fin&order=<?= $order ?>">Hora Fin</a></th>
                    <th><a href="?column=organizador&order=<?= $order ?>">Organizador</a></th>
                    <th><a href="?column=acompanantes&order=<?= $order ?>">Acompañantes</a></th>
                    <th><a href="?column=ubicacion&order=<?= $order ?>">Ubicación</a></th>
                    <th><a href="?column=coste&order=<?= $order ?>">Coste</a></th>
                    <th><a href="?column=total_alumnos&order=<?= $order ?>">Total Alumnos</a></th>
                    <th><a href="?column=objetivo&order=<?= $order ?>">Objetivo</a></th>
                    <th><a href="?column=aprobada&order=<?= $order ?>">Aprobada</a></th>
                    <!-- Verificar si el rol del usuario es 1 (administrador) para mostrar la columna "Acciones" -->
                    <?php if ($usuario["rol"] == 1): ?>
                    <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <!-- Iterar sobre los resultados de la consulta -->
                <?php while ($actividad = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo escapar($actividad["titulo"]); ?></td>
                        <td><?php echo escapar(
                            $actividad["tipo_nombre"]
                        ); ?></td>
                        <td><?php echo escapar(
                            $actividad["departamento_nombre"]
                        ); ?></td>
                        <td><?php echo escapar(
                            $actividad["profesor_nombre"]
                        ); ?></td>
                        <td><?php echo escapar($actividad["trimestre"]); ?></td>
                        <td><?php echo escapar(
                            $actividad["fecha_inicio"]
                        ); ?></td>
                        <td><?php echo escapar(
                            $actividad["hora_inicio"]
                        ); ?></td>
                        <td><?php echo escapar($actividad["fecha_fin"]); ?></td>
                        <td><?php echo escapar($actividad["hora_fin"]); ?></td>
                        <td><?php echo escapar(
                            $actividad["organizador"]
                        ); ?></td>
                        <td><?php echo escapar(
                            $actividad["acompanantes"]
                        ); ?></td>
                        <td><?php echo escapar($actividad["ubicacion"]); ?></td>
                        <td><?php echo escapar($actividad["coste"]); ?></td>
                        <td><?php echo escapar(
                            $actividad["total_alumnos"]
                        ); ?></td>
                        <td><?php echo escapar($actividad["objetivo"]); ?></td>
                        <!-- Mostrar 'Sí' si está aprobada, 'No' si no lo está -->
                        <td> <?php echo escapar(
                            $actividad["aprobada"] ? "Sí" : "No"
                        ); ?></td>
                        <td>
                            <?php if ($usuario["rol"] == 1): ?>
                                <!-- Enlace para editar la actividad -->
                                <a href="update.php?id=<?php echo escapar(
                                    $actividad["id"]
                                ); ?>">Editar</a>
                                <!-- Enlace para eliminar la actividad -->
                                <a href="delete.php?id=<?php echo escapar(
                                    $actividad["id"]
                                ); ?>">Eliminar</a>
                                 <!-- Enlace para cambiar el estado de aprobación -->
                                <a href="aprobar.php?id=<?php echo escapar(
                                    $actividad["id"]
                                ); ?>">Aprobr</a> 
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php // Cerrar la conexión a la base de datos
mysqli_close($conn);
?>