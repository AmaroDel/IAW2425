<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta</title>
</head>
<body>
    <h1>Datos Personales</h1>
    <form action="encuesta.php">
    <label for="">Nombre</label><input type="text" name="nombre"><br>
    <label for="">Edad</label><input type="text" name="edad"><br>

    <label for="">Sexo</label>
    <input type="radio" name="sexo" value="hombre">
    <label for="">Hombre</label>
    <input type="radio" name="sexo" value="mujer">
    <label for="">Mujer</label><br>

    <label for="">Provincia</label>
    <select name="provincia">
        <option>Sevilla</option>
        <option>Huelva</option>
        <option>Córdoba</option>
        <option>Jaén</option>
        <option>Granada</option>
        <option>Almería</option>
        <option>Málaga</option>
        <option>Cadiz</option>
    </select><br>

    <label for="">Aceptar politica de privacidad</label><input type="checkbox" name="privacidad"><br>
    <input type="submit" name="enviar">
    </form>

    <?php
        if(isset($_GET["nombre"]) && isset($_GET["edad"]) && isset($_GET["sexo"]) && isset($_GET["provincia"]) && isset($_GET["privacidad"])){
            $nombre = htmlspecialchars($_GET["nombre"]);
            $edad = htmlspecialchars($_GET["edad"]);
            $sexo = htmlspecialchars($_GET["sexo"]);
            $provincia = htmlspecialchars($_GET["provincia"]);

            if(is_numeric($edad) && $edad>0){
            echo "<br><p>Hola $nombre, eres $sexo, tienes $edad años y vives en $provincia.</p>";
            }else{
                echo "<br><p>Pon tu edad bien</p>";

            }
        }else{
            echo "<br><p>Te falta algo por rellenar o marcar</p>";
        }

    ?>
</body>
</html>