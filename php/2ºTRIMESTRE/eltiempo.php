<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiempo</title>
</head>
<body>
    <?php
    $text=file_get_contents("eltiempo.html");
    $resultado= explode("<td>",$text);//El <td> hace de separador
    print_r($resultado[4]);
    ?>
</body>
</html>