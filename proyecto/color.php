<?php
// Habilitar la visualización de errores en PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($cookie_name = "white") {
    $cookie_name = $_POST["color"];
}

if (empty($_COOKIE[$cookie_name])) {
    $cookie_name = "white";
}

setcookie($cookie_name, time() + (86400 * 365))
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Color</title>
    <style>
        body {            
            background-color: <?php echo $cookie_name ?>;
        }
    </style>
</head>
<body>

<form method="POST" action="color.php">
<input type="color" name="color">
<input type="submit">
</form>

    <?php echo $cookie_name; ?>

</body>
</html>