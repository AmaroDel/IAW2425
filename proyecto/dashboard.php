<?php
// Iniciar la sesión
session_start();

// Habilitar la visualización de errores en PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

// Establecer la zona horaria a Madrid, España
date_default_timezone_set('Europe/Madrid');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user_id"])) {
    header("Location: loginproyecto.php");
    exit();
}

// Manejar el cambio de tema (AJAX)
if (isset($_POST['theme'])) {
    $_SESSION['theme'] = $_POST['theme'];
    exit;
}

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Establecer el juego de caracteres a utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Obtener información del usuario
$usuario = $_SESSION;

// Obtener la última conexión desde la sesión (en lugar de hacer otra consulta SQL)
$ultima_conexion = $_SESSION["ultima_conexion"] ?? null;

// Configurar el locale en español
setlocale(LC_TIME, 'es_ES.UTF-8');

// Formatear la fecha y hora de la última conexión
$fecha_conexion = $ultima_conexion ? strftime("%d de %B a las %H:%M", strtotime($ultima_conexion)) : "No disponible";

// Mostrar mensaje con la IP del usuario
$ip_usuario = $_SERVER['REMOTE_ADDR'];

// Recuperar la hora de la última conexión
$user_id = $_SESSION["user_id"];
$query = "SELECT ultima_conexion FROM registrados WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// Configurar el locale en español
setlocale(LC_TIME, 'es_ES.UTF-8');

// Formatear la fecha y hora de la última conexión
$fecha_conexion = strftime("%d de %B a las %H:%M", strtotime($ultima_conexion));

// Lista blanca de columnas permitidas
$columnas_permitidas = ["id", "titulo", "tipo_nombre", "departamento_nombre", "profesor_nombre",
                         "trimestre", "fecha_inicio", "hora_inicio", "fecha_fin", "hora_fin",
                         "organizador", "acompanantes", "ubicacion", "coste", "total_alumnos",
                         "objetivo", "aprobada"];

// Validar la columna (si no es válida, usar "id")
$column = isset($_GET["column"]) && in_array($_GET["column"], $columnas_permitidas) ? $_GET["column"] : "id";

// Validar el orden (solo "ASC" o "DESC")
$order = isset($_GET["order"]) && in_array(strtoupper($_GET["order"]), ["ASC", "DESC"]) ? strtoupper($_GET["order"]) : "ASC";

// Validar la página (solo permitir números positivos)
$pagina = isset($_GET['pagina']) && ctype_digit($_GET['pagina']) && $_GET['pagina'] > 0 ? (int)$_GET['pagina'] : 1;

// Definir paginación
$por_pagina = 5;  // Número de actividades a mostrar por página
$offset = ($pagina - 1) * $por_pagina;

// Asegurar que el offset nunca sea negativo
$offset = max(0, $offset);

// Consulta SQL con validaciones
$sql = "SELECT actividades.*,
               tipos.nombre AS tipo_nombre,
               departamentos.nombre AS departamento_nombre,
               profesores.nombre AS profesor_nombre
        FROM actividades
        JOIN tipos ON actividades.tipo = tipos.id
        JOIN departamentos ON actividades.departamento = departamentos.id
        JOIN profesores ON actividades.profesor_responsable = profesores.id
        ORDER BY $column $order
        LIMIT $por_pagina OFFSET $offset";

// Ejecutar la consulta
$resultado = mysqli_query($conn, $sql);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Consulta para obtener el conteo de actividades
$query = "SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN aprobada = 1 THEN 1 ELSE 0 END) AS aprobadas,
            SUM(CASE WHEN aprobada = 0 THEN 1 ELSE 0 END) AS pendientes
          FROM actividades";

$result = $conn->query($query);
$stats = $result->fetch_assoc();
$total = $stats['total'];
$paginas = ceil($total / $por_pagina); // Calcular total de páginas

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Incluir Bootstrap 5 desde un CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">

</head>
<body class="<?php echo isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'dark-mode' : ''; ?>"> 
<h1>Bienvenido, <?php echo escapar($_SESSION["username"]); ?>, 
    se conectó por última vez el <?php echo $fecha_conexion; ?> 
    con la IP: <?php echo $ip_usuario; ?>.
</h1>

<!-- Interruptor de modo oscuro -->
<label class="switch">
    <input type="checkbox" id="theme-toggle" <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'checked' : ''; ?>>
    <span class="slider round"></span>
</label>

<script>
    document.getElementById("theme-toggle").addEventListener("change", function() {
        let theme = this.checked ? "dark" : "light";

        fetch("dashboard.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "theme=" + theme
        }).then(() => {
            document.body.classList.toggle("dark-mode", this.checked);
        });
    });
</script>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total de Actividades</h5>
                    <p class="card-text"><?= $stats['total'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Aprobadas</h5>
                    <p class="card-text"><?= $stats['aprobadas'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Pendientes</h5>
                    <p class="card-text"><?= $stats['pendientes'] ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<h2>Dashboard de Actividades</h2>
<div>
    <!-- Enlace para añadir una nueva actividad -->
    <a href="create.php" class="btn btn-primary me-2">Añadir Nueva Actividad</a>
    <!-- Enlace para cerrar sesión -->
    <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
    <!-- Enlace para ir a las estadisticas -->
    <a href="estadisticas.php" class="btn btn-info">Ver Estadísticas</a>
    <!-- Enlace para descargar -->
    <a href="descargar.php"><button>Descargar</button></a>
    <!-- Enlace para modo oscuro -->
    <a href="oscuro.php" class="modo-oscuro-btn">Activar Modo Oscuro</a>
    <?php if ($usuario["rol"] == 1): ?>
    <!-- Enlace para administrar usuarios -->
    <a href="admin_usuarios.php" class="btn btn-warning me-2">Administrar Usuarios</a>
    <?php endif; ?>
    <?php if ($usuario["rol"] == 1): ?>
    <!-- Enlace para gestionar departamentos (solo visible para administradores) -->
    <a href="gestion_departamentos.php" class="btn btn-secondary me-2">Gestionar Departamentos</a>
    <?php endif; ?>


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

<!-- Enlaces de paginación -->
<div class="d-flex justify-content-center">
    <?php for ($i = 1; $i <= $paginas; $i++): ?>
        <a href="?pagina=<?= $i ?>&column=<?= $column ?>&order=<?= $order ?>" class="btn btn-sm btn-outline-primary m-1"><?= $i ?></a>
    <?php endfor; ?>
</div>

<!-- Incluir el script de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php // Cerrar la conexión a la base de datos
mysqli_close($conn);
?>