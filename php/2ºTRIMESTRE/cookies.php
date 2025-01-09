<?php
if ( isset( $_COOKIE[ 'visitas' ] ) ) {

    setcookie( 'visitas', $_COOKIE[ 'visitas' ] + 1, time() + 3600 * 24 );
    setcookie('lang', $_SERVER['HTTP_ACCEPT_LANGUAGE'], time()+3600*24);
    $mensaje = 'Numero de visitas: '.$_COOKIE[ 'visitas' ]. " usando el idioma " .$_COOKIE['lang'];
}
else {

    setcookie( 'visitas', 1, time() + 3600 * 24 );
    $mensaje = 'Bienvenido por primera vez a nuesta web' . " usando el idioma " .$_COOKIE['lang'];
}
//El html va abajo porque esto froma parte de la cabecera
?>
<html>
    <head>
        <title>Cookie</title>
    </head>
    <body>
        <p>
            <?php     print_r($mensaje);?>
            
        </p>    
    </body>
</html> 
