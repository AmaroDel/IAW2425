<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El tiempo 2</title>
</head>
<body>
    <?php
    $text= file_get_contents("https://www.eltiempo.es/sevilla.html");
    $resultado= explode('<span class="degrees" data-temperature=',$text);//El <td> hace de separador
    print_r($resultado[0]);
    ?>
</body>
</html>