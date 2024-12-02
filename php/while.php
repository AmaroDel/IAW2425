<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>While</title>
</head>
<body>
    <?php
    $numero = 1; //Empezamos por el numero
    $total = 10; //Llegamos hasta el 10
    echo "<table border='1'>";
    while ($numero <= $total){
        echo "<tr><td>" . $numero . "</td></tr>\n";
        $numero ++;
    }
    echo "</table>";
    ?>
</body>
</html>