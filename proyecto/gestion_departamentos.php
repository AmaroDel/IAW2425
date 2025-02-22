<?php
session_start();

// Incluir archivos de configuración y funciones
include "config.php";
include "funciones.php";

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] != 1) {
    header("Location: dashboard.php"); // Si no es admin, redirigir al dashboard
    exit();
}

// Verificar si se ha enviado un formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["nombre"])) { 
        // Añadir un nuevo departamento
        $nombre = trim($_POST["nombre"]);
        if (!empty($nombre)) {
            $sql = "INSERT INTO departamentos (nombre) VALUES ('$nombre')";
            if (!mysqli_query($conn, $sql)) {
                die("Error al insertar el departamento: " . mysqli_error($conn));
            }
        }
    } elseif (isset($_POST["eliminar_id"])) { 
        // Eliminar un departamento (se eliminarán también sus actividades por ON DELETE CASCADE)
        $id = (int)$_POST["eliminar_id"];
        $sql = "DELETE FROM departamentos WHERE id = $id";
        if (!mysqli_query($conn, $sql)) {
            die("Error al eliminar el departamento: " . mysqli_error($conn));
        }
    } elseif (isset($_POST["editar_id"]) && isset($_POST["nuevo_nombre"])) { 
        // Editar el nombre de un departamento
        $id = (int)$_POST["editar_id"];
        $nuevo_nombre = trim($_POST["nuevo_nombre"]);
        if (!empty($nuevo_nombre)) {
            $sql = "UPDATE departamentos SET nombre = '$nuevo_nombre' WHERE id = $id";
            if (!mysqli_query($conn, $sql)) {
                die("Error al actualizar el departamento: " . mysqli_error($conn));
            }
        }
    }
}

// Obtener todos los departamentos
$sql = "SELECT * FROM departamentos";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error al obtener los departamentos: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Departamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Gestión de Departamentos</h2>

    <!-- Formulario para añadir un nuevo departamento -->
    <form method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" name="nombre" class="form-control" placeholder="Nombre del departamento" required>
            <button type="submit" class="btn btn-success">Añadir</button>
        </div>
    </form>

    <!-- Tabla con los departamentos existentes -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row["id"]) ?></td>
                <td>
                    <!-- Mostrar el nombre del departamento -->
                    <span id="nombre-<?php $row["id"] ?>"><?php echo escapar($row["nombre"]) ?></span>

                    <!-- Formulario oculto para editar -->
                    <form method="POST" class="d-inline" id="form-editar-<?= $row["id"] ?>" style="display: none;">
                        <input type="hidden" name="editar_id" value="<?= $row["id"] ?>">
                        <input type="text" name="nuevo_nombre" class="form-control d-inline w-auto" required>
                        <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelarEdicion(<?= $row['id'] ?>)">Cancelar</button>
                    </form>
                </td>
                <td>
                    <!-- Botón para activar la edición -->
                    <button class="btn btn-warning btn-sm" onclick="editarDepartamento(<?= $row['id'] ?>)">Editar</button>

                    <!-- Formulario para eliminar el departamento -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="eliminar_id" value="<?php $row["id"] ?>">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este departamento y todas sus actividades asociadas?');">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
</div>

<!-- Script para manejar la edición en línea -->
<script>
function editarDepartamento(id) {
    document.getElementById("nombre-" + id).style.display = "none";
    document.getElementById("form-editar-" + id).style.display = "inline-block";
}

function cancelarEdicion(id) {
    document.getElementById("nombre-" + id).style.display = "inline";
    document.getElementById("form-editar-" + id).style.display = "none";
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
