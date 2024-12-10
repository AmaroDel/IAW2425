<html>
<body>

<h2>Pinos</h2>

<form action="pinito.php" method="post">
  Cantidad de nivel:<br>
  <input type="number" name="nivel" value="nivel">
  <br>
  <input type="submit" value="Submit">
</form> 
<?php
  $nivel = $_REQUEST['nivel'];
  define('NIVEL', $_REQUEST['nivel']); //constante para almacenar nivel, sin cambios de valor

  echo "<center>";
  //Condicion que limita el numero de niveles
  if ($nivel >=1 && $nivel<=9){

   for ($k=1; $k<=3; $k++){
     for ($j=1; $j<=$nivel; $j++){
        for ($i=1; $i<=$j; $i++){
            echo "*";
        }
        //según esta orden es el primer asterisco de los 2 ultimos triangulos, a los que no se les hará un salto de línea
        if(constant("NIVEL") != $nivel && $j==1){ 
         }else{
           echo "<br>";
           $j++;
         }
      }
      $nivel=$nivel+2;
    }
  //Imprime el tronco
  echo "***<br>";
  echo "***";
  echo "</center>";
}

?>
</body>
</html>