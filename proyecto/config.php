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
define("SMTP_HOST", "smtp.gmail.com");
define("SMTP_USER", "wpamarillo@gmail.com"); // Tu correo de Gmail
define("SMTP_PASS", "phxl tzlz fckw fddu"); // Tu contraseña de aplicación de 16 caracteres
define("SMTP_PORT", 587);
?>