<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Click & Paw - Adopta, Salva una Vida'; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- La ruta apunta a la carpeta assets/css/ -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

    <header class="header">
        <nav class="nav-container">
            <a href="index.php" class="logo">
                <span class="logo-icon">🐾</span>
                Click & Paw
            </a>
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="<?php echo ($active_page == 'inicio') ? 'active' : ''; ?>">Inicio</a></li>
                <li><a href="galeria.php" class="<?php echo ($active_page == 'galeria') ? 'active' : ''; ?>">Galería</a></li>
                <li><a href="proceso.php" class="<?php echo ($active_page == 'proceso') ? 'active' : ''; ?>">Proceso</a></li>
                <li><a href="ayudar.php" class="<?php echo ($active_page == 'ayudar') ? 'active' : ''; ?>">Ayudar</a></li>
                <li><a href="nosotros.php" class="<?php echo ($active_page == 'nosotros') ? 'active' : ''; ?>">Nosotros</a></li>
                <li><a href="contacto.php" class="<?php echo ($active_page == 'contacto') ? 'active' : ''; ?>">Contacto</a></li>
                
                <!-- BOTÓN CAMBIADO: Ahora apunta directo al panel -->
                <li><a href="admin/index.php" class="btn-login">Panel Admin</a></li>
            </ul>
        </nav>
    </header>

    <main>