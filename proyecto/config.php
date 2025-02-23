<?php
// Conexión a la base de datos
$servername = "*********";
$username = "*********";
$password = "*********";
$database = "*********";
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Configuración del correo SMTP con Gmail
define("SMTP_HOST", "*******");
define("SMTP_USER", "*******"); // Tu correo de Gmail
define("SMTP_PASS", "*******"); // Tu contraseña de aplicación de 16 caracteres
define("SMTP_PORT", 587);
?>