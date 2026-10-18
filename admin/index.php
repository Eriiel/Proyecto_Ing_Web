<?php
require_once '../includes/db_connection.php';
proteger_pagina();

// ---- LÓGICA PARA OBTENER LAS ESTADÍSTICAS DEL DASHBOARD ----
try {
    // 1. Contar mascotas disponibles
    $result_disponibles = $conn->query("SELECT COUNT(id) as total FROM mascotas WHERE estado = 'Disponible'");
    $count_disponibles = $result_disponibles->fetch_assoc()['total'];

    // 2. Contar mascotas en proceso
    $result_en_proceso = $conn->query("SELECT COUNT(id) as total FROM mascotas WHERE estado = 'En Proceso'");
    $count_en_proceso = $result_en_proceso->fetch_assoc()['total'];

    // 3. Contar mascotas adoptadas
    $result_adoptadas = $conn->query("SELECT COUNT(id) as total FROM mascotas WHERE estado = 'Adoptado'");
    $count_adoptadas = $result_adoptadas->fetch_assoc()['total'];

    // 4. Contar solicitudes nuevas (estado 'Recibida')
    $result_solicitudes = $conn->query("SELECT COUNT(id) as total FROM solicitudes WHERE estado_solicitud = 'Recibida'");
    $count_solicitudes_recibidas = $result_solicitudes->fetch_assoc()['total'];

} catch (Exception $e) {
    // En caso de error, mostramos 0 para no romper la página
    $count_disponibles = $count_en_proceso = $count_adoptadas = $count_solicitudes_recibidas = "Error";
    error_log("Error en el dashboard: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Click & Paw</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

<div class="admin-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <a href="index.php" class="sidebar-logo">
            🐾 Click & Paw
        </a>
        
        <nav>
            <ul>
                <li><a href="index.php" class="active">Dashboard</a></li>
                <li><a href="gestion_mascotas.php">Gestión de Mascotas</a></li>
                <li><a href="gestion_solicitudes.php">Solicitudes de Adopción</a></li>
                <li><a href="gestion_contactos.php">Mensajes de Contacto</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="main-content">
        <h1>Dashboard - Panel de Administración</h1>
        
        <p>Bienvenido, <strong><?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Administrador') ?></strong></p>

        <!-- Grid de estadísticas (2x2) -->
        <div class="stats-grid">
            
            <!-- Tarjeta 1: Mascotas Disponibles -->
            <div class="stat-card">
                <h4>Mascotas Disponibles</h4>
                <div class="stat-circle" style="background-color: #566E3D;">
                    <span><?= $count_disponibles ?></span>
                </div>
            </div>

            <!-- Tarjeta 2: En Proceso de Adopción -->
            <div class="stat-card">
                <h4>En Proceso</h4>
                <div class="stat-circle" style="background-color: #FFC49B;">
                    <span style="color: #342E37;"><?= $count_en_proceso ?></span>
                </div>
            </div>

            <!-- Tarjeta 3: Mascotas Adoptadas -->
            <div class="stat-card">
                <h4>Adoptadas</h4>
                <div class="stat-circle" style="background-color: #CE4257;">
                    <span><?= $count_adoptadas ?></span>
                </div>
            </div>

            <!-- Tarjeta 4: Solicitudes Nuevas -->
            <div class="stat-card">
                <h4>Solicitudes Nuevas</h4>
                <div class="stat-circle" style="background-color: #342E37;">
                    <span><?= $count_solicitudes_recibidas ?></span>
                </div>
            </div>

        </div>

    </main>
</div>

</body>
</html>