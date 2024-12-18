<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>webscrapping</title>
</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<input type="text" name="url" size="50" value=""/><br />
  <input type="submit" value="Buscar emails" />
</form> 
<?php
 $the_url = isset($_REQUEST['url']) ? htmlspecialchars($_REQUEST['url']) : ''; // Ternario
 if (isset($_REQUEST['url']) && !empty($_REQUEST['url'])) {
  $text = file_get_contents($_REQUEST['url']);
  }
  if (!empty($text)) {
    $res = preg_match_all(
    "/[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}/i",$text,$matches);
    if ($res) {
          foreach(array_unique($matches[0]) as $email) {
          echo $email . "<br/>";
    }
  }
  else {
      echo "No he encontrado ningún email.";
  }
}
?>
</body>
</html>