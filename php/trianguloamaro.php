<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triangulo</title>
</head>
<body>
    <h1>Triángulo</h1>
    <form id="formulario" action="trianguloamaro.php">
	<input type="text" name="numero" id="numero">
	<input type="submit" value="Enviar">
</form>
    <?php
        if (isset($_GET["numero"])){
            $numero= htmlspecialchars($_GET["numero"]);
                if ($numero > 0 && is_numeric($numero)){
                    for ($i=1;$i<=$numero; $i++){
                        for($j=1;$j<=$i; $j++){
                            echo "* ";
                        }
                        echo "<br>";
                    }
                }
        }
    ?>
</body>
</html>