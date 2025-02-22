<?php
session_start();

// Habilitar la visualización de errores en PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

date_default_timezone_set('Europe/Madrid');

if (!isset($_SESSION["user_id"])) {
    header("Location: loginproyecto.php");
    exit();
}

include "config.php";
include "funciones.php";
mysqli_set_charset($conn, "utf8mb4");

$usuario = $_SESSION;

// **GESTIÓN DE DEPARTAMENTOS (Solo Administradores)**
if ($usuario["rol"] == 1) {

    // Agregar Departamento
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {
        $nombre = trim($_POST["nombre"]);
        if (!empty($nombre)) {
            $stmt = mysqli_prepare($conn, "INSERT INTO departamentos (nombre) VALUES (?)");
            mysqli_stmt_bind_param($stmt, "s", $nombre);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['mensaje'] = "Departamento agregado con éxito.";
        } else {
            $_SESSION['error'] = "El nombre no puede estar vacío.";
        }
        header("Location: gestion_departamentos.php");
        exit;
    }

    // Eliminar Departamento
    if (isset($_GET["eliminar"])) {
        $id = (int)$_GET["eliminar"];
        $stmt = mysqli_prepare($conn, "DELETE FROM departamentos WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['mensaje'] = "Departamento eliminado correctamente.";
        header("Location: gestion_departamentos.php");
        exit;
    }

    // Editar Departamento
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editar_id"]) && isset($_POST["editar_nombre"])) {
        $id = (int)$_POST["editar_id"];
        $nombre = trim($_POST["editar_nombre"]);
        if (!empty($nombre)) {
            $stmt = mysqli_prepare($conn, "UPDATE departamentos SET nombre = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $nombre, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['mensaje'] = "Departamento actualizado con éxito.";
        } else {
            $_SESSION['error'] = "El nombre no puede estar vacío.";
        }
        header("Location: gestion_departamentos.php");
        exit;
    }

    // Obtener lista de departamentos con paginación
    $por_pagina = 5;
    $pagina = isset($_GET['pagina']) && ctype_digit($_GET['pagina']) && $_GET['pagina'] > 0 ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $por_pagina;

    $sql = "SELECT * FROM departamentos ORDER BY id DESC LIMIT $por_pagina OFFSET $offset";
    $result = mysqli_query($conn, $sql);
    $departamentos = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    // Contar total de departamentos para la paginación
    $query = "SELECT COUNT(*) AS total FROM departamentos";
    $result = mysqli_query($conn, $query);
    $total_rows = mysqli_fetch_assoc($result)['total'];
    $total_paginas = ceil($total_rows / $por_pagina);
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
<div class="container mt-4">
    <h1>Gestión de Departamentos</h1>

    <!-- Mensajes de éxito/error -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success"><?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Formulario para agregar departamentos -->
    <form method="post" class="mb-3 d-flex">
        <input type="text" name="nombre" placeholder="Nuevo Departamento" class="form-control me-2" required>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>

    <!-- Tabla de Departamentos -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departamentos as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d["nombre"]) ?></td>
                    <td>
                        <button onclick="editarDepartamento(<?= $d['id'] ?>, '<?= htmlspecialchars($d['nombre']) ?>')" class="btn btn-warning btn-sm">✏️ Editar</button>
                        <a href="?eliminar=<?= $d['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este departamento?')" class="btn btn-danger btn-sm">❌ Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <!-- Formulario oculto para edición -->
    <form id="editForm" method="post" class="d-flex" style="display: none;">
        <input type="hidden" name="editar_id" id="editar_id">
        <input type="text" name="editar_nombre" id="editar_nombre" class="form-control me-2" required>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
    </form>
</div>

<script>
function editarDepartamento(id, nombre) {
    document.getElementById("editar_id").value = id;
    document.getElementById("editar_nombre").value = nombre;
    document.getElementById("editForm").style.display = "flex";
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>