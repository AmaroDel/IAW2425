<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imagen Aleatoria</title>
    <style>
        div{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        img {
            width: 240px;
            height: 240px;
        }
        h1{
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
<?php 
    $array = array("luffy", "zoro", "nami", "ussop", "sanji", "chopper", "robin", "franky", "brook", "jinbe");
    $random = rand(0,count($array)-1);
    $tripulante = $array[$random];
?>
    <h1>Tripulante Aleatorio:</h1><div><img src="<?= $tripulante; ?>.png" alt=""></div>



</body>
</html>