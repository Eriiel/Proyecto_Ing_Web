<?php
require '../includes/db_connection.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Click & Paw</title>
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
                    <li><a href="index.php" class="active">Dashboard</a></li>
                    <li><a href="gestion_mascotas.php">Gestión de Mascotas</a></li>
                    <li><a href="gestion_solicitudes.php">Solicitudes</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <h1>Bienvenido, <?= htmlspecialchars($_SESSION['admin_usuario']) ?>!</h1>
            <p>Este es el panel principal. Selecciona una opción del menú de la izquierda para comenzar a administrar el contenido del sitio web.</p>
        </main>
    </div>
</body>
</html>
