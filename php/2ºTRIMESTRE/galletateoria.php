<?php
setcookie("nombre", "Pepito Conejo");

if (isset($_COOKIE["nombre"])) {
    print "<p>Su nombre es $_COOKIE[nombre]</p>\n";
} else {
    print "<p>No sé su nombre.</p>\n";
}
?>