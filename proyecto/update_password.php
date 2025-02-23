<?php
require 'config.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $nueva_password = $_POST['password'];

    // Validar que la contraseña no esté vacía
    if (empty($nueva_password)) {
        die("Error: La nueva contraseña no puede estar vacía.");
    }

    // Cifrar la nueva contraseña con el mismo método usado en el registro (SHA-512 + salt)
    $password_encrypted = crypt(
        $nueva_password,
        '$6$rounds=5000$' . uniqid(mt_rand(), true) . '$'
    );

    // Actualizar la contraseña y eliminar el token
    $sql = "UPDATE registrados SET password = ?, token = NULL, token_expira = NULL WHERE token = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $password_encrypted, $token);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "Contraseña actualizada correctamente.";
    } else {
        echo "Error al actualizar la contraseña. Verifica tu token.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
