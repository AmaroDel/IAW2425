<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        img {
            width: 240px;
            height: 240px;
        }
    </style>
</head>
<body>
    <?php 
    $array = array("luffy", "zoro", "nami", "ussop", "sanji", "chopper", "robin", "franky", "brook", "jinbe");
    $random = rand(0,9);
    $tripulante = $array[$random];
    ?>

<img src="../images/tripulacion/<?= $tripulante; ?>.png" alt="">

</body>
</html>