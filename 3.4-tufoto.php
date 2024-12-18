<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Tu foto</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
<label for="foto">Foto</label>Foto:<input type="file" name="imagen">
<input type="submit" name="submit" value="Subir foto">
</form>

<?php
    if (isset($_POST["submit"])){
        $directorio = "uploads/";
        $fichero = $directorio . basename($_FILES["imagen"]["name"]);
        $subidoOk = 1;
        $tipoArchivo = strtolower(pathinfo($fichero, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if($check !== false) {
            echo "Archivo es una imagen - " . $check["mime"] . ".";
            $uploadOk = 1;
            move_uploaded_file($_FILES["imagen"]["tmp_name"],$fichero);
            echo "<img width='20%' src='$fichero'>";
        } else {
            echo "El archivo no es una imagen.";
            $uploadOk = 0;
        }
    }
?>
</body>
</html>