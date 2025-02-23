<?php
// Iniciar sesión
session_start();

// Incluir configuración y conexión a la base de datos
include "config.php";
include "funciones.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user_id"])) {
    die("No autorizado");
}

// Establecer cabeceras para la descarga del archivo
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="actividades.txt"');

// Conectar a la base de datos
mysqli_set_charset($conn, "utf8mb4");

// Consulta para obtener todas las actividades
$sql = "SELECT actividades.id, actividades.titulo, tipos.nombre AS tipo_nombre, 
               departamentos.nombre AS departamento_nombre, profesores.nombre AS profesor_nombre, 
               actividades.trimestre, actividades.fecha_inicio, actividades.hora_inicio, 
               actividades.fecha_fin, actividades.hora_fin, actividades.organizador, 
               actividades.acompanantes, actividades.ubicacion, actividades.coste, 
               actividades.total_alumnos, actividades.objetivo, 
               CASE WHEN actividades.aprobada = 1 THEN 'Sí' ELSE 'No' END AS aprobada
        FROM actividades
        JOIN tipos ON actividades.tipo = tipos.id
        JOIN departamentos ON actividades.departamento = departamentos.id
        JOIN profesores ON actividades.profesor_responsable = profesores.id
        ORDER BY actividades.id ASC";

$resultado = mysqli_query($conn, $sql);

// Verificar si hay datos
if (mysqli_num_rows($resultado) > 0) {
    // Escribir los datos en el archivo de salida
    echo "ID | Título | Tipo | Departamento | Profesor Responsable | Trimestre | Fecha Inicio | Hora Inicio | Fecha Fin | Hora Fin | Organizador | Acompañantes | Ubicación | Coste | Total Alumnos | Objetivo | Aprobada\n";
    echo str_repeat("-", 160) . "\n";

    while ($actividad = mysqli_fetch_assoc($resultado)) {
        echo "{$actividad['id']} | {$actividad['titulo']} | {$actividad['tipo_nombre']} | {$actividad['departamento_nombre']} | {$actividad['profesor_nombre']} | {$actividad['trimestre']} | {$actividad['fecha_inicio']} | {$actividad['hora_inicio']} | {$actividad['fecha_fin']} | {$actividad['hora_fin']} | {$actividad['organizador']} | {$actividad['acompanantes']} | {$actividad['ubicacion']} | {$actividad['coste']} | {$actividad['total_alumnos']} | {$actividad['objetivo']} | {$actividad['aprobada']}\n";
    }
} else {
    echo "No hay actividades registradas.";
}

// Cerrar conexión
mysqli_close($conn);
?>
