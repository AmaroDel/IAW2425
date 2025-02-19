<?php
session_start();
session_destroy();
header('Location: loginproyecto.php');
exit();
?>
