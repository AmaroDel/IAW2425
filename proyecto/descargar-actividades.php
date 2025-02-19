<?php
// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

$sql = "SELECT actividades.*,
               tipos.nombre AS tipo_nombre,
               departamentos.nombre AS departamento_nombre,
               profesores.nombre AS profesor_nombre
        FROM actividades
        JOIN tipos ON actividades.tipo = tipos.id
        JOIN departamentos ON actividades.departamento = departamentos.id
        JOIN profesores ON actividades.profesor_responsable = profesores.id";

$texto = mysqli_query($conn, $sql);
