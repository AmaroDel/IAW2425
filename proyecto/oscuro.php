<?php
session_start();

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

if (!isset($_SESSION['modo_oscuro'])) {
    $_SESSION['modo_oscuro'] = false;
}

// Alternar el estado del modo oscuro
if ($_SESSION['modo_oscuro'] === true) {
    $_SESSION['modo_oscuro'] = false;
    $modo = 0; // MySQL no tiene booleanos, usamos 0 para false
} else {
    $_SESSION['modo_oscuro'] = true;
    $modo = 1; // Usamos 1 para true
}

// Si el usuario está autenticado, actualizamos en la BD
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Preparar la consulta con MySQLi procedural
    $sql = "UPDATE registrados SET modo_oscuro = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $modo, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Cerrar la conexión
    mysqli_close($conn);
}

// Redirigir a la página principal
header("Location: dashboard.php");
exit;
?>