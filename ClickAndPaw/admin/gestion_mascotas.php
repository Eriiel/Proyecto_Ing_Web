<?php
require '../includes/db_connection.php';

$result = $conn->query("SELECT * FROM mascotas ORDER BY id DESC");

if (!$result) {
    die("Error al consultar las mascotas: " . $mysqli->error);
}

$mascotas = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Mascotas</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="sidebar">
        <a href="index.php" class="sidebar-logo">
            <span>🐾</span>
            Click & Paw
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="gestion_mascotas.php" class="active">Gestión de Mascotas</a></li>
                <li><a href="gestion_solicitudes.php">Solicitudes</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <h1>Gestión de Mascotas</h1>

        <a href="formulario_mascota.php" class="btn btn-primary">Añadir Nueva Mascota</a>

        <div class="table-container">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Edad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                <?php if (empty($mascotas)): ?>
                    <tr><td colspan="7" class="empty-cell">No hay mascotas registradas.</td></tr>

                <?php else: ?>
                    <?php foreach ($mascotas as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['id']) ?></td>

                            <td>
                                <?php if (!empty($m['foto']) && file_exists('../uploads/' . $m['foto'])): ?>
                                    <img src="../uploads/<?= htmlspecialchars($m['foto']) ?>" 
                                         alt="Imagen de <?= htmlspecialchars($m['nombre']) ?>" 
                                         class="table-pet-image">
                                <?php else: ?>
                                    <span class="no-image-text">Sin Imagen</span>
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($m['nombre']) ?></td>
                            <td><?= htmlspecialchars($m['especie']) ?></td>
                            <td><?= htmlspecialchars($m['edad']) ?> años</td>
                            <td><?= htmlspecialchars($m['estado']) ?></td>

                            <td class="actions">
                                <a href="formulario_mascota.php?id=<?= $m['id'] ?>" class="btn btn-edit">Editar</a>
                                <a href="eliminar_mascota.php?id=<?= $m['id'] ?>" 
                                   class="btn btn-delete"
                                   onclick="return confirm('¿Seguro que deseas eliminar a <?= htmlspecialchars($m['nombre']) ?>?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
