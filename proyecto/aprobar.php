<?php
// Iniciar la sesión
session_start();

// Habilitar la visualización de errores en PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user_id"])) {
    // Si no ha iniciado sesión, redirigir al usuario a la página de inicio de sesión
    header("Location: loginproyecto.php");
    exit();
}

// Verificar si el usuario es administrador
if ($_SESSION["rol"] != 1) {
    //Si el usuario no es administrador, redirigir al dashboard
    header("Location: dashboard.php");
    exit();
}

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Establecer el juego de caracteres a utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Generar el token CSRF
csrf();

// Procesar formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar el token CSRF
    if (
        isset($_POST["submit"]) &&
        !hash_equals($_SESSION["csrf"], $_POST["csrf"])
    ) {
        die("Token CSRF inválido.");
    }
}

// Obtener el ID de la actividad desde la URL
$actividad_id = $_GET["id"];

//Consulta SQL para obtener la actividad
$sql = "SELECT * FROM actividades where id = $actividad_id";
$resultado = mysqli_query($conn, $sql);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    // Si la consulta falla, mostrar un mensaje de error y detener la ejecución del script
    die("Error en la consulta: " . mysqli_error($conn));
}

//Obtener la actividad
$actividad = mysqli_fetch_assoc($resultado);

//Cambiar el estado de la aprobación
$nuevo_estado = $actividad["aprobada"] ? 0 : 1; // Si está aprobada, cambiar a no aprobada, y viceversa

//Actualizar el estado de la aprobación en la base de datos
$sql = "UPDATE actividades SET aprobada = $nuevo_estado WHERE id = $actividad_id";
if (mysqli_query($conn, $sql)) {
    //Redirigir de vuelta al dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Si la actualización falla, mostrar un mensaje de error
    die("Error al actualizar el estado de aprobación: " . mysqli_error($conn));
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>