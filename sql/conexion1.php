<?php
    $servername = "sql308.thsite.top";
    $username = "thsi_38097484";
    $password = "*****";

// Crear conexión
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>