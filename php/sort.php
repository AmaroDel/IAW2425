<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sort</title>
</head>
<body>
    <?php
        $palabras = ["zaragoza", "abanico", "barco", "hogaza", "dia"];
        sort($palabras);
        $totalPalabras = count($palabras);
        for($i=0;$i<=$totalPalabras-1;$i++){
            echo "$palabras[$i] <br>";
        }
    ?>
</body>
</html>