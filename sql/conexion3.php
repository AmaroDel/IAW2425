<?php
    $servername = "sql308.thsite.top";
    $username = "thsi_38097484";
    $password = "*****";

try {
  $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Conexión exitosa";
} catch(PDOException $e) {
  echo "Conexión fallida: " . $e->getMessage();
}
?> 