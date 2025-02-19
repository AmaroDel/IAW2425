<?php
    header("Content-disposition: attachment; filename=logs.txt");
    header("Content-type: text/plain");
    readfile("logs.txt");
?>