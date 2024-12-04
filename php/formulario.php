<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
</head>
<body>
    <form action="formulario.php" >
        <input type="text" name="caja" placeholder="Escrie aquí">
        <input type="submit" value="Enviar">
    </form>
    <?php
        if (isset($_GET["caja"])){ //si tiene algpo $_GET
                echo 'Hola ' . htmlspecialchars($_GET["caja"]) . '!';
        }
    ?>
</body>
</html>