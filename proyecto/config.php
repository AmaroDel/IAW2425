<?php
// Conexión a la base de datos
$servername = "*******";
$username = "*******";
$password = "*******";
$database = "*******";
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>