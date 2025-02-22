<?php
session_start();

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Establecer el juego de caracteres a utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Verificar si el usuario ha iniciado sesión y si es administrador (rol = 1)
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] != 1) {
    header("Location: dashboard.php"); // Redirigir si no es admin
    exit();
}

// Obtener todos los usuarios registrados
$sql = "SELECT id, nombre, email, rol FROM registrados ORDER BY rol DESC, nombre ASC";
$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Gestión de Usuarios</h2>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Volver al Dashboard</a>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo escapar($usuario["nombre"]) ?></td>
                        <td><?php echo escapar($usuario["email"]) ?></td>
                        <td><?= $usuario["rol"] == 1 ? "Administrador" : "Usuario" ?></td>
                        <td>
                            <a href="admin_cambiar_password.php?id=<?= $usuario["id"] ?>" class="btn btn-warning btn-sm">Cambiar Contraseña</a>

                            <?php if ($usuario["id"] != $_SESSION["user_id"]): ?> 
                                <a href="admin_eliminar_usuario.php?id=<?= $usuario["id"] ?>" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                             <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>