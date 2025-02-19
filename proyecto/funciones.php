<?php
// Función para escapar caracteres especiales en HTML
function escapar($html)
{
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

// Función para generar y almacenar un token CSRF en la sesión
function csrf()
{
    session_start();

    if (empty($_SESSION["csrf"])) {
        if (function_exists("random_bytes")) {
            $_SESSION["csrf"] = bin2hex(random_bytes(32));
        } elseif (function_exists("mcrypt_create_iv")) {
            $_SESSION["csrf"] = bin2hex(
                mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)
            );
        } else {
            $_SESSION["csrf"] = bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
}

?>
