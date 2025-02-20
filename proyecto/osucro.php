<?php
session_start();

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Alternar el estado del modo oscuro
if (isset($_SESSION['modo_oscuro']) && $_SESSION['modo_oscuro'] === true) {
    $_SESSION['modo_oscuro'] = false;
    $modo = false;
} else {
    $_SESSION['modo_oscuro'] = true;
    $modo = true;
}

// Si el usuario está autenticado, actualizamos en la BD
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("UPDATE usuarios SET modo_oscuro = ? WHERE id = ?");
    $stmt->execute([$modo, $user_id]);
}

// Redirigimos a la página principal
header("Location: loginproyecto.php");
exit;
?>
