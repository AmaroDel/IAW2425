<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuadrado Color</title>
    <?php 
        $rojo = rand(0,255);
        $verde = rand(0,255);
        $azul= rand(0,255);
        $color = "rgb($rojo, $verde, $azul)";
    ?>
    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        div{
            width: 300px;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background-color: <?= $color ?>;
        }
    </style>
</head>
<body>
    <div>Tengo un color aleatorio, prueba con F5</div>

</body>
</html>