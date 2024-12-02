<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foreach</title>
</head>
<body>
<?php
$refranes = [
    "A quien madruga, Dios le ayuda.",
    "Camarón que se duerme, se lo lleva la corriente.",
    "Más vale tarde que nunca.",
    "No hay mal que por bien no venga.",
    "Al mal tiempo, buena cara.",
    "Perro que ladra no muerde.",
    "En casa de herrero, cuchillo de palo.",
    "El que mucho abarca, poco aprieta.",
    "A caballo regalado no se le mira el diente.",
    "Quien mucho habla, mucho yerra."
];
    echo "<ul>"; //Empezar una lista no ordenada
    foreach ($refranes as $elementoDelArray){
            echo "<li>$elementoDelArray</li>"; //Escribo un refrán
}
    echo"</ul>"; //Termino la lista
?>

</body>
</html>