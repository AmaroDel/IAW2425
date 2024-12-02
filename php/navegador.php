<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Una web con php</title>
</head>
<body>
    <h1>Encabezado</h1>
    <?php 
        echo "Hola usuario, navegas con " . $_SERVER['HTTP_USER_AGENT'] . " desde la dirección " . $_SERVER['REMOTE_ADDR']; 
        
    ?>
</body>
</html>