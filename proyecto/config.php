<?php
// Conexión a la base de datos
$servername = "sql308.thsite.top";
$username = "thsi_38097484";
$password = "A1EbhYLa";
$database = "thsi_38097484_ejemplo";
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>