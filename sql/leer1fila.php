<?php
//Conexión con BD
    $servername = "sql308.thsite.top";
    $username = "thsi_38097484";
    $password = "*****";
    $bd= "thsi_38097484_ejemplo";
    $enlace= mysqli_connect($servername,$username;$password,$bd);
    if (!$enlace){
        die("Ocurrión algún problema con la conexión :" . mysqli_connect_error())
    }

//Construcción de la Query
    $query= "SELECT * FROM usuarios LIMIT 1";
//Ejecución de la Query
    $resultado = mysqli_query($enlace, $query);
//Procesamiento de los resultados
    print_r($resultado);
//Cierre de la conexión
    mysqli_close($enlace);
?>