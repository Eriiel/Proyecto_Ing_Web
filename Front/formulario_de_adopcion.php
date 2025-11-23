<?php
// Conexión a la base de datos
include 'includes/db.php';

// Obtener el ID de la mascota desde la URL
$mascota_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Si no hay ID, redirigir a la galería
if ($mascota_id == 0) {
    header('Location: galeria.php');
    exit();
}

// Consultar información de la mascota
$query = "SELECT * FROM mascotas WHERE id = ? AND estado = 'Disponible'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $mascota_id);
$stmt->execute();
$result = $stmt->get_result();

// Si la mascota no existe o no está disponible
if ($result->num_rows == 0) {
    header('Location: galeria.php');
    exit();
}

$mascota = $result->fetch_assoc();

// Determinar el emoji según la especie
$emoji = '🐾';
if (strtolower($mascota['especie']) == 'perro') {
    $emoji = '🐕';
} elseif (strtolower($mascota['especie']) == 'gato') {
    $emoji = '🐈';
} elseif (strtolower($mascota['especie']) == 'conejo') {
    $emoji = '🐰';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Adopción - <?php echo htmlspecialchars($mascota['nombre']); ?> - Click & Paw</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <nav class="nav-container">
            <a href="index.php" class="logo">
                <span class="logo-icon">🐾</span>
                Click & Paw
            </a>
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="galeria.php">Mascotas Disponibles</a></li>
                <li><a href="proceso-adopcion.php">Proceso de Adopción</a></li>
                <li><a href="como-ayudar.php">Cómo Ayudar</a></li>
                <li><a href="sobre-nosotros.php">Sobre Nosotros</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <!-- BREADCRUMB -->
    <nav class="breadcrumb">
        <ul class="breadcrumb-list">
            <li><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-separator">›</li>
            <li><a href="galeria.php">Mascotas Disponibles</a></li>
            <li class="breadcrumb-separator">›</li>
            <li><a href="perfil-mascota.php?id=<?php echo $mascota_id; ?>"><?php echo htmlspecialchars($mascota['nombre']); ?></a></li>
            <li class="breadcrumb-separator">›</li>
            <li class="breadcrumb-current">Solicitud de Adopción</li>
        </ul>
    </nav>

    <!-- FORM SECTION -->
    <section class="form-section">
        <!-- Pet Info Card -->
        <div class="pet-info-card">
            <div class="pet-image-small">
                <?php if (!empty($mascota['foto_principal'])): ?>
                    <img src="img/mascotas/<?php echo htmlspecialchars($mascota['foto_principal']); ?>" alt="<?php echo htmlspecialchars($mascota['nombre']); ?>">
                <?php else: ?>
                    <?php echo $emoji; ?>
                <?php endif; ?>
            </div>
            <div class="pet-info-text">
                <h2>Solicitud de Adopción para: <?php echo htmlspecialchars($mascota['nombre']); ?></h2>
                <p>
                    <?php echo htmlspecialchars($mascota['especie']); ?> • 
                    <?php echo htmlspecialchars($mascota['edad']); ?> años • 
                    <?php echo htmlspecialchars($mascota['genero']); ?> • 
                    <?php echo htmlspecialchars($mascota['tamano']); ?>
                </p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <h1 class="form-title">Formulario de Adopción</h1>
            <p class="form-subtitle">Por favor, completa todos los campos para iniciar el proceso de adopción. Nos pondremos en contacto contigo pronto.</p>

            <form id="adoptionForm" method="POST" action="procesar-adopcion.php">
                <!-- Campo oculto para ID de mascota -->
                <input type="hidden" name="mascota_id" value="<?php echo $mascota_id; ?>">
                <input type="hidden" name="mascota_nombre" value="<?php echo htmlspecialchars($mascota['nombre']); ?>">

                <!-- Información Personal -->
                <h3 style="color: var(--color-fern-green); margin-bottom: 1.5rem; margin-top: 1rem;">📋 Información Personal</h3>
                
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo <span class="required">*</span></label>
                    <input type="text" id="nombre_completo" name="nombre_completo" required>
                    <span class="error-message" id="error-nombre">Por favor, ingresa tu nombre completo</span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Correo Electrónico <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required>
                        <span class="error-message" id="error-email">Por favor, ingresa un correo válido</span>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono de Contacto <span class="required">*</span></label>
                        <input type="tel" id="telefono" name="telefono" placeholder="Ej: +507 6000-0000" required>
                        <span class="error-message" id="error-telefono">Por favor, ingresa tu teléfono</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección Completa <span class="required">*</span></label>
                    <input type="text" id="direccion" name="direccion" placeholder="Calle, ciudad, provincia" required>
                    <span class="error-message" id="error-direccion">Por favor, ingresa tu dirección</span>
                </div>

                <!-- Información del Hogar -->
                <h3 style="color: var(--color-fern-green); margin-bottom: 1.5rem; margin-top: 2rem;">🏠 Información del Hogar</h3>

                <div class="form-group">
                    <label for="descripcion_hogar">Descripción de tu Hogar <span class="required">*</span></label>
                    <textarea id="descripcion_hogar" name="descripcion_hogar" placeholder="Cuéntanos sobre tu hogar: ¿Vives solo/a o con familia? ¿Tienes otras mascotas? ¿Casa o apartamento? ¿Hay espacio exterior?" required></textarea>
                    <span class="helper-text">Describe tu situación familiar y el ambiente donde vivirá la mascota</span>
                    <span class="error-message" id="error-hogar">Por favor, describe tu hogar</span>
                </div>

                <!-- Motivación -->
                <h3 style="color: var(--color-fern-green); margin-bottom: 1.5rem; margin-top: 2rem;">💭 Motivación para Adoptar</h3>

                <div class="form-group">
                    <label for="motivo_adopcion">¿Por qué quieres adoptar a <?php echo htmlspecialchars($mascota['nombre']); ?>? <span class="required">*</span></label>
                    <textarea id="motivo_adopcion" name="motivo_adopcion" placeholder="Cuéntanos por qué <?php echo htmlspecialchars($mascota['nombre']); ?> es especial para ti y qué puedes ofrecerle..." required minlength="500"></textarea>
                    <div class="char-counter" id="charCounter">0 / 500 caracteres mínimo</div>
                    <span class="error-message" id="error-motivo">Por favor, escribe al menos 500 caracteres explicando tu motivación</span>
                </div>

                <!-- Experiencia con Mascotas -->
                <div class="form-group">
                    <label for="experiencia">Experiencia Previa con Mascotas</label>
                    <textarea id="experiencia" name="experiencia" placeholder="¿Has tenido mascotas antes? ¿Qué tipo? (Opcional)"></textarea>
                    <span class="helper-text">Opcional, pero nos ayuda a conocerte mejor</span>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="perfil-mascota.php?id=<?php echo $mascota_id; ?>" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Enviar Solicitud</button>
                </div>
            </form>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Click & Paw</h3>
                <p>Rescatamos, rehabilitamos y reubicamos mascotas que necesitan un hogar lleno de amor.</p>
            </div>
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="galeria.php">Mascotas Disponibles</a></li>
                    <li><a href="proceso-adopcion.php">Proceso de Adopción</a></li>
                    <li><a href="como-ayudar.php">Cómo Ayudar</a></li>
                    <li><a href="sobre-nosotros.php">Sobre Nosotros</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contacto</h3>
                <ul>
                    <li>📧 info@clickandpaw.com</li>
                    <li>📱 +507 1234-5678</li>
                    <li>📍 Ciudad de Panamá, Panamá</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Síguenos</h3>
                <ul>
                    <li><a href="#facebook">Facebook</a></li>
                    <li><a href="#instagram">Instagram</a></li>
                    <li><a href="#twitter">Twitter</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Click & Paw. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>