<?php
//Conexión con BD
    $servername = "sql308.thsite.top";
    $username = "thsi_38097484";
    $password = "A1EbhYLa";
    $bd= "thsi_38097484_ejemplo";
    $enlace= mysqli_connect($servername,$username,$password,$bd);
    if (!$enlace){
        die("Ocurrió algún problema con la conexión :" . mysqli_connect_error());
    }

//Construcción de la Query
    $query= "INSERT INTO usuarios (nombre, apellidos, email) VALUES ('Alberto', 'Moreno Carrero', 'alberto@gmail.com'";
//Ejecución de la Query
    $resultado = mysqli_query($enlace, $query);
//Procesamiento de los resultados
    if (mysqli_num_rows($resultado)>0){
        //Al menos tengo un registro
        while($fila = mysqli_fetch_assoc($resultado)){
            echo "Nombre: " . $fila["nombre"] . " <br>Apellido: " . $fila["apellidos"] . "<br>Email: " . $fila["email"] . "<br>";
        }
    }else{
        echo "<p>No hubo resultados en la tabla</p>";
    }
//Cierre de la conexión
    mysqli_close($enlace);
?>