<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fecha</title>
</head>
<body>
    <?php

       $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
       $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
       echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
       //Resultado: Domingo 26 de Enero del 2020

    ?>
</body>
</html>