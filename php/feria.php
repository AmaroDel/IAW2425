<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feria de ¿MAYO?</title>
</head>
<body>

<?php 
$fecha = "6-05-2025";
$fechahoy =  date("Y-m-d");
$resta = strtotime($fecha)-strtotime($fechahoy);
$dias = $resta / (24*3600);
echo "Quedan " . $dias . " dias para la feria de Sevilla de 2025"
?> 
</body>
</html>