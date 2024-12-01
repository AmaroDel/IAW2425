<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operadores cadena</title>
</head>
<body>
    <?php
        // Concatenación de cadenas 
        $cadena1 = "Hola"; 
        $cadena2 = " Hackers!"; 
        $concatenacion = $cadena1 . $cadena2; 
        echo "Opcion 1: $concatenacion <br>";

        //Opcion dos para concatenar 
        $cadenab1 = "Hola";
        $cadenab1 .= "Hackers!";
        echo "Opcion 2: $cadenab1";
    ?>
</body>
</html>