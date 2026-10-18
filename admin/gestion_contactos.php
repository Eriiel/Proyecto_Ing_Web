<?php
// ---- LÍNEA FUNDAMENTAL AÑADIDA ----
// Incluimos el archivo de conexión ANTES de usar cualquiera de sus funciones.
require '../includes/db_connection.php';

// Ahora sí podemos llamar a la función de seguridad, porque ya está definida.
proteger_pagina();

try {
    // Usamos la variable $conn que se creó en db_connection.php
    $result = $conn->query("SELECT * FROM contactos ORDER BY fecha_envio DESC");
    $mensajes = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $mensajes[] = $row;
        }
    }
} catch (Exception $e) {
    die("Error al consultar los mensajes: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensajes de Contacto</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="sidebar">
        <a href="index.php" class="sidebar-logo"><span>🐾</span> Click & Paw</a>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="gestion_mascotas.php">Gestión de Mascotas</a></li>
                <li><a href="gestion_solicitudes.php">Solicitudes</a></li>
                <li><a href="gestion_contactos.php" class="active">Mensajes de Contacto</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <h1>Mensajes de Contacto</h1>
        <div class="table-container">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($mensajes)): ?>
                    <tr><td colspan="5" class="empty-cell">No hay mensajes recibidos.</td></tr>
                <?php else: ?>
                    <?php foreach ($mensajes as $msg): ?>
                        <tr>
                            <td><?= $msg['id'] ?></td>
                            <td><?= htmlspecialchars($msg['nombre'] . ' ' . $msg['apellido']) ?></td>
                            <td><a href="mailto:<?= htmlspecialchars($msg['correo']) ?>"><?= htmlspecialchars($msg['correo']) ?></a></td>
                            <td><?= nl2br(htmlspecialchars($msg['mensaje'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($msg['fecha_envio'])) ?></td>
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