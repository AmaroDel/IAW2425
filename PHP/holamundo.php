<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hola mundo</title>
</head>
<body>
    <?php
        $cadena1 = "<p>Hola Mundo</p>";
        $cadena2 = "<p>desde PHP</p>";
        echo $cadena1 . $cadena2; //Concatenar
        echo "$cadena1$cadena2"; //Otra forma de concatenar
        print "<p>Este mensaje está escrito con la instrucción print</p>" 
    ?>
</body>
</html>