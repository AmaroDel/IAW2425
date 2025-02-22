<?php
session_start();

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Establecer el juego de caracteres a utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Verificar que el usuario sea administrador
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] != 1) {
    header("Location: dashboard.php");
    exit();
}

// Verificar que el ID del usuario a eliminar esté presente en la URL
if (!isset($_GET["id"])) {
    header("Location: admin_usuarios.php");
    exit();
}

$id_usuario = $_GET["id"];

// Evitar que un usuario se elimine a sí mismo
if ($id_usuario == $_SESSION["user_id"]) {
    header("Location: admin_usuarios.php?error=no_puedes_borrarte");
    exit();
}


// Primero, eliminar todas las actividades creadas por el usuario
$sql_actividades = "DELETE FROM actividades WHERE creador = ?";
$stmt_actividades = mysqli_prepare($conn, $sql_actividades);
mysqli_stmt_bind_param($stmt_actividades, "i", $id_usuario);
mysqli_stmt_execute($stmt_actividades);
mysqli_stmt_close($stmt_actividades);

// Luego, eliminar al usuario
$sql_usuario = "DELETE FROM registrados WHERE id = ?";
$stmt_usuario = mysqli_prepare($conn, $sql_usuario);
mysqli_stmt_bind_param($stmt_usuario, "i", $id_usuario);
mysqli_stmt_execute($stmt_usuario);
mysqli_stmt_close($stmt_usuario);

// Redirigir al panel de usuarios con un mensaje de éxito
header("Location: admin_usuarios.php?mensaje=usuario_eliminado");
exit();
?>