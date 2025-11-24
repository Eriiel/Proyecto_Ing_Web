<?php
require '../includes/db_connection.php';
proteger_pagina();

// ---- LÓGICA PARA ACTUALIZAR ESTADOS ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['statuses'])) {
    try {
        $update_stmt = $pdo->prepare("UPDATE solicitudes SET estado_solicitud = ? WHERE id = ?");
        foreach ($_POST['statuses'] as $solicitud_id => $nuevo_estado) {
            $update_stmt->execute([$nuevo_estado, $solicitud_id]);
        }
        // Usaremos mensajes flash para mostrar el resultado después de redirigir
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => '¡Los estados de las solicitudes han sido actualizados!'];
    } catch (PDOException $e) {
        $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Error al actualizar: ' . $e->getMessage()];
    }
    header("Location: gestion_solicitudes.php");
    exit();
}

// ---- LÓGICA PARA OBTENER LAS SOLICITUDES ----
try {
    $stmt = $pdo->query("
        SELECT 
            solicitudes.*, 
            mascotas.nombre AS nombre_mascota 
        FROM solicitudes 
        JOIN mascotas ON solicitudes.id_mascota = mascotas.id 
        ORDER BY solicitudes.fecha_solicitud DESC
    ");
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar solicitudes: " . $e->getMessage());
}

// Lógica para mostrar mensajes flash
$flash_message = null;
if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Solicitudes</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="sidebar">
        <a href="index.php" class="sidebar-logo">
            <span>🐾</span> Click & Paw
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="gestion_mascotas.php">Gestión de Mascotas</a></li>
                <li><a href="gestion_solicitudes.php" class="active">Solicitudes</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <h1>Gestión de Solicitudes</h1>

        <?php if ($flash_message): ?>
            <div class="message <?= $flash_message['type'] === 'error' ? 'error-message' : '' ?>">
                <?= htmlspecialchars($flash_message['text']); ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <form method="POST">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mascota</th>
                        <th>Solicitante</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($solicitudes)): ?>
                        <tr><td colspan="7" class="empty-cell">No hay solicitudes registradas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($solicitudes as $s): ?>
                            <tr>
                                <td><?= $s['id'] ?></td>
                                <td><?= htmlspecialchars($s['nombre_mascota']) ?></td>
                                <td><?= htmlspecialchars($s['nombre_solicitante']) ?></td>
                                <!-- NOMBRES DE COLUMNA CORREGIDOS -->
                                <td><?= htmlspecialchars($s['email_solicitante']) ?></td>
                                <td><?= htmlspecialchars($s['telefono_solicitante']) ?></td>
                                <td>
                                    <select name="statuses[<?= $s['id'] ?>]">
                                        <option value="Recibida" <?= $s['estado_solicitud'] === 'Recibida' ? 'selected' : '' ?>>Recibida</option>
                                        <option value="En Revision" <?= $s['estado_solicitud'] === 'En Revision' ? 'selected' : '' ?>>En Revisión</option>
                                        <option value="Aprobada" <?= $s['estado_solicitud'] === 'Aprobada' ? 'selected' : '' ?>>Aprobada</option>
                                        <option value="Rechazada" <?= $s['estado_solicitud'] === 'Rechazada' ? 'selected' : '' ?>>Rechazada</option>
                                    </select>
                                </td>
                                <td><?= date('d/m/Y', strtotime($s['fecha_solicitud'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
                <?php if (!empty($solicitudes)): ?>
                    <div class="form-actions" style="justify-content: flex-start;">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </main>
</div>
</body>
</html>