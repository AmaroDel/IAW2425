<?php
    $servername = "sql308.thsite.top";
    $username = "thsi_38097484";
    $password = "*****";

try {
  $conn = new PDO("mysql:host=$servername;dbname=thsi_38097484_ejemplo", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Conexión exitosa con PDO";
} catch(PDOException $e) {
  echo "Conexión fallida: " . $e->getMessage();
}
?>