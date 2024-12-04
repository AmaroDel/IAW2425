<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super globales</title>
</head>
<body>
    <?php
        echo "Te estás conectando desde la IP: " . $_SERVER['REMOTE_ADDR'] . ' !' . '<br>';
        echo "Tu navegador está catalogado como: ". $_SERVER['HTTP_USER_AGENT'] . '<br>';
        echo "Vienes de la página " . $_SERVER['HTTP_REFERER'];

    ?>
</body>
</html>