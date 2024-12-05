<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gracias Bernat</title>
</head>
<body>
    <p>Introduzca un número positivo > 0</p>
    <form action="bernat.php">
            <input type="number" name="numero">
            <input type="submit" value="Enviar">
    </form>
    <?php
    $numero = ;

    if ($_POST["numero"] > 0){    
        for ($i = 0; $i <= $numero; $i++){
        echo "*"*$i; 
        echo "<br>";
    }}
    else{

    }
    ?>
</body>
</html>