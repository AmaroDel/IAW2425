<?php
session_start();

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Establecer el juego de caracteres a utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Verificar si el usuario es administrador (rol = 1)
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] != 1) {
    header("Location: dashboard.php");
    exit();
}

// Inicializar una variable de error
$error = "";

// Si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    // Comprobar que las dos contraseñas son iguales
    if ($password !== $password2) {
        $error = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
    } else {
        // Si coinciden, hashear la nueva contraseña
        $nueva_password = password_hash($password, PASSWORD_BCRYPT);

        // Actualizar la contraseña en la base de datos
        $sql = "UPDATE registrados SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $nueva_password, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Redirigir de vuelta al panel de usuarios con un mensaje
        header("Location: admin_usuarios.php?mensaje=contraseña_cambiada");
        exit();
    }
}

// Obtener el ID del usuario desde la URL
$id_usuario = $_GET["id"] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Cambiar Contraseña</h2>

        <!-- Mostrar un mensaje de error si las contraseñas no coinciden -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="admin_cambiar_password.php">
            <input type="hidden" name="id" value="<?php echo escapar($id_usuario) ?>">
            <div class="mb-3">
                <label for="password" class="form-label">Nueva Contraseña:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password2" class="form-label">Repite la Nueva Contraseña:</label>
                <input type="password" class="form-control" name="password2" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="admin_usuarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>