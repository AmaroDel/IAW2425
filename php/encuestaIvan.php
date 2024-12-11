<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="#" type="image/x-icon">
    <title>Encuesta</title>
    <style>
        body{
            display: flex;
            justify-content:center;
            align-items :center;
            min-height: 100vh;
        }
        form div{
            display:flex;
            width: max-content;
            flex-direction:column;
            margin: 1rem;
        }
        #espaciado{margin-top:1rem;}
    </style>
</head>
<body>
    <form action="encuesta.php" method="post">
        <div class="estructurado">
            <label for="">*Nombre:</label>
            <input type="text" name="nombre" id="nombre" placeholder="nombre">
        </div>
        <div class="estructurado">
            <label for="genero">*Género:</label>
            <select name="genero" id="genero">
                <option value="Hombre">Hombre</option>
                <option value="Mujer">Mujer</option>
                <option value="Otro">Otro</option>
            </select>
        </div>
        <div class="estructurado">
            <label for="">¿Desea recibir novedades?</label>
            <input type="checkbox" name="novedades" id="novedades">
        </div>
        <div class="estructurado" id="espaciado">
            <label for="">*Elija su nacionalidad:</label>
            <label for="">Española<input type="radio" name="nacionalidad" value="española" id="espanola"></label>
            <label for="">Portuguesa<input type="radio" name="nacionalidad" value="portuguesa" id="portuguesa"></label>
            <label for="">Francesa<input type="radio" name="nacionalidad" value="francesa" id="francesa"></label>
        </div>
        <input type="submit" value="Enviar">
        <?php
            
                if(empty($_POST["nombre"]) || empty($_POST["genero"]) || empty($_POST["nacionalidad"]))
                    echo"<div class='estructurado'>Debes de rellenar los campos obligatorios</div>";
                else{
                        echo"<p>Los datos se enviaron exitosamente:</p>";
                        echo"<p>Nombre: " . htmlspecialchars($_POST["nombre"]) . "</p>";
                        echo"<p>Genero: " . htmlspecialchars($_POST["genero"]) . "</p>";
                        echo"<p>Nacionalidad: " . htmlspecialchars($_POST["nacionalidad"]) ."</p>";
                }
            
        ?>
    </form>
</body>
</html>