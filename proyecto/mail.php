<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Función para enviar correo de recuperación
function enviarCorreoRecuperacion($email, $token) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER; // Se usa la constante de config.php
        $mail->Password   = SMTP_PASS; // Se usa la constante de config.php
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        // Configuración del correo
        $mail->setFrom(SMTP_USER, 'Soporte de Mi App');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña';

        // Enlace de recuperación con token
        $link = "https://tu-dominio.com/reset_password.php?token=" . urlencode($token);
        $mail->Body = "<p>Hola,</p>
                       <p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
                       <p><a href='$link'>$link</a></p>
                       <p>Si no solicitaste este cambio, ignora este mensaje.</p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        return false;
    }
}


// Función para generar el token y enviarlo por correo
function generarTokenRecuperacion($conn, $email) {
    // Verificar si el correo existe en la base de datos
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Generar un token único
        $token = bin2hex(random_bytes(50));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Guardar el token en la base de datos
        $sql = "UPDATE usuarios SET token = ?, token_expira = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $token, $expira, $email);
        mysqli_stmt_execute($stmt);

        // Enviar el correo con el enlace de recuperación
        if (enviarCorreoRecuperacion($email, $token)) {
            echo "Correo de recuperación enviado.";
        } else {
            echo "Error al enviar el correo.";
        }
    } else {
        echo "El correo no está registrado.";
    }

    mysqli_stmt_close($stmt);
}

// Si se envía el formulario con el email
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    generarTokenRecuperacion($conn, $_POST['email']);
}

// Cerrar conexión
mysqli_close($conn);
?>
