<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Sorteo</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="participantes">Número:</label>
    <input type="number" name="numero"><br>
    <input type="submit" value="Sorteo">
</form>

<?php
if ($_GET){
    $numero = htmlspecialchars($_GET["numero"]);
    if (is_numeric($numero) && $numero>1 && round($numero,0)==$numero)
    {
        echo "<p>Premio para el número " . rand(1, $numero) . "</p>";
    }
    else {
        echo "<p>Debe introducir un número positivo mayor que 1</p>";
    }
}
?>
</body>

</html>