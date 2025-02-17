<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user_id"])) {
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

// Definir lista blanca de columnas permitidas
$columnas_permitidas = ["id", "titulo", "tipo_nombre", "departamento_nombre", "profesor_nombre", 
                         "trimestre", "fecha_inicio", "hora_inicio", "fecha_fin", "hora_fin", 
                         "organizador", "acompanantes", "ubicacion", "coste", "total_alumnos", 
                         "objetivo", "aprobada"];

// Capturar y validar la columna
$column = isset($_GET["column"]) && in_array($_GET["column"], $columnas_permitidas) ? $_GET["column"] : "id";

// Capturar y validar el orden (solo permitir "ASC" o "DESC")
$order = isset($_GET["order"]) && in_array($_GET["order"], ["ASC", "DESC"]) ? $_GET["order"] : "ASC";

// Alternar entre ASC y DESC para los enlaces
$order_toggle = ($order === "ASC") ? "DESC" : "ASC";

// Consulta SQL segura
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
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Incluir Bootstrap 5 desde un CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
            <h2>Dashboard de Actividades</h2>
            <div>
                <!-- Enlace para añadir una nueva actividad -->
                <a href="create.php" class="btn btn-primary me-2">Añadir Nueva Actividad</a>
                <!-- Enlace para cerrar sesión -->
                <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        <!-- Tabla para mostrar las actividades -->
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th><a href="?column=titulo&order=<?= $order ?>" class="text-white">Título</a></th>
                        <th><a href="?column=tipo_nombre&order=<?= $order ?>" class="text-white">Tipo</a></th>
                        <th><a href="?column=departamento_nombre&order=<?= $order ?>" class="text-white">Departamento</a></th>
                        <th><a href="?column=profesor_nombre&order=<?= $order ?>" class="text-white">Profesor Responsable</a></th>
                        <th><a href="?column=trimestre&order=<?= $order ?>" class="text-white">Trimestre</a></th>
                        <th><a href="?column=fecha_inicio&order=<?= $order ?>" class="text-white">Fecha Inicio</a></th>
                        <th><a href="?column=hora_inicio&order=<?= $order ?>" class="text-white">Hora Inicio</a></th>
                        <th><a href="?column=fecha_fin&order=<?= $order ?>" class="text-white">Fecha Fin</a></th>
                        <th><a href="?column=hora_fin&order=<?= $order ?>" class="text-white">Hora Fin</a></th>
                        <th><a href="?column=organizador&order=<?= $order ?>" class="text-white">Organizador</a></th>
                        <th><a href="?column=acompanantes&order=<?= $order ?>" class="text-white">Acompañantes</a></th>
                        <th><a href="?column=ubicacion&order=<?= $order ?>" class="text-white">Ubicación</a></th>
                        <th><a href="?column=coste&order=<?= $order ?>" class="text-white">Coste</a></th>
                        <th><a href="?column=total_alumnos&order=<?= $order ?>" class="text-white">Total Alumnos</a></th>
                        <th><a href="?column=objetivo&order=<?= $order ?>" class="text-white">Objetivo</a></th>
                        <th><a href="?column=aprobada&order=<?= $order ?>" class="text-white">Aprobada</a></th>
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
                            <td><?php echo escapar($actividad["tipo_nombre"]); ?></td>
                            <td><?php echo escapar($actividad["departamento_nombre"]); ?></td>
                            <td><?php echo escapar($actividad["profesor_nombre"]); ?></td>
                            <td><?php echo escapar($actividad["trimestre"]); ?></td>
                            <td><?php echo escapar($actividad["fecha_inicio"]); ?></td>
                            <td><?php echo escapar($actividad["hora_inicio"]); ?></td>
                            <td><?php echo escapar($actividad["fecha_fin"]); ?></td>
                            <td><?php echo escapar($actividad["hora_fin"]); ?></td>
                            <td><?php echo escapar($actividad["organizador"]); ?></td>
                            <td><?php echo escapar($actividad["acompanantes"]); ?></td>
                            <td><?php echo escapar($actividad["ubicacion"]); ?></td>
                            <td><?php echo escapar($actividad["coste"]); ?></td>
                            <td><?php echo escapar($actividad["total_alumnos"]); ?></td>
                            <td><?php echo escapar($actividad["objetivo"]); ?></td>
                            <td><?php echo escapar($actividad["aprobada"] ? "Sí" : "No"); ?></td>
                            <td>
                                <?php if ($usuario["rol"] == 1): ?>
                                    <!-- Enlace para editar la actividad -->
                                    <a href="update.php?id=<?php echo escapar($actividad["id"]); ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <!-- Enlace para eliminar la actividad -->
                                    <a href="delete.php?id=<?php echo escapar($actividad["id"]); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                    <!-- Enlace para cambiar el estado de aprobación -->
                                    <a href="aprobar.php?id=<?php echo escapar($actividad["id"]); ?>" class="btn btn-success btn-sm">Aprobar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        
    <!-- Incluir el script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php // Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
