<?php
//Conexión con BD
    $servername = "sql308.thsite.top";
    $username = "thsi_38097484";
    $password = "*****";
    $bd= "thsi_38097484_ejemplo";
    $enlace= mysqli_connect($servername,$username,$password,$bd);
    if (!$enlace){
        die("Ocurrió algún problema con la conexión :" . mysqli_connect_error());
    }

//Construcción de la Query
    $query= "ALTER TABLE usuarios ADD fullname VARCHAR(100), ADD direccion VARCHAR(100)";
//Ejecución de la Query
    $resultado = mysqli_query($enlace, $query);

//Procesamiento de los resultados
    if ($resultado){
        echo "Columnas añadidas correctamente";
    }
    else{
        echo "Error al añadir las columnas";
    }
//Cierre de la conexión
    mysqli_close($enlace);
?>