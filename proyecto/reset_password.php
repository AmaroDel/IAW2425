<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar si el token es válido y no ha expirado
    $sql = "SELECT id FROM usuarios WHERE token = ? AND token_expira > NOW()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($result)) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h2>Restablecer Contraseña</h2>
    <form method="POST" action="update_password.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
        <label for="password">Nueva Contraseña:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Actualizar Contraseña</button>
    </form>
</body>
</html>

<?php
    } else {
        echo "Token inválido o expirado.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
