<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ligin</title>
</head>
<body>
    <h1>Sistema de autenticación</h1>
    <form action="login.php" method="POST">
        <label for="">Escribe tu nombre</label>
        <input type="text" name="nombre">
        <input type="password" name="contrasena">
        <input type="submit" name="Enviar">
    </form>
    <?php 
        if(isset($_POST["nombre"]) && isset($_POST["contrasena"])){
            $nombre = $_POST["nombre"];
            $contrasena = $_POST["contrasena"];
            if($nombre == "admin" && $contrasena == "H4CK3R4$1R"){
                echo 'Acceso concedido';
            }
            else{
                echo "Acceso denegado";
            }
        }
    ?>
</body>
</html>