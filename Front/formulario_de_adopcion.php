<?php
// Conexión a la base de datos
include 'includes/db.php'; // Tu archivo de conexión

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
    <style>
        /* ==========================================================================
           VARIABLES Y RESET
           ========================================================================== */
        :root {
            --color-peach-bg: #FFC49B;
            --color-licorice-text: #130303;
            --color-fern-green: #566E3D;
            --color-action-red: #CE4257;
            --color-raisin-black: #342E37;
            --color-white: #FFFFFF;
            --color-light-gray-bg: #fdf6f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--color-peach-bg);
            color: var(--color-licorice-text);
            line-height: 1.6;
        }

        /* ==========================================================================
           HEADER
           ========================================================================== */
        .header {
            background-color: var(--color-fern-green);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--color-white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo-icon {
            font-size: 2rem;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 1.5rem;
            align-items: center;
            margin: 0;
        }

        .nav-menu li {
            display: flex;
            align-items: center;
        }

        .nav-menu a {
            color: var(--color-white);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            display: block;
            text-align: center;
        }

        .nav-menu a:hover {
            background-color: var(--color-action-red);
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--color-white);
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* ==========================================================================
           BREADCRUMB
           ========================================================================== */
        .breadcrumb {
            max-width: 900px;
            margin: 2rem auto 0;
            padding: 0 2rem;
        }

        .breadcrumb-list {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .breadcrumb-list a {
            color: var(--color-fern-green);
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb-list a:hover {
            color: var(--color-action-red);
        }

        .breadcrumb-separator {
            color: #666;
        }

        .breadcrumb-current {
            color: #666;
        }

        /* ==========================================================================
           FORM CONTAINER
           ========================================================================== */
        .form-section {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem 4rem;
        }

        .pet-info-card {
            background-color: var(--color-white);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .pet-image-small {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--color-fern-green), var(--color-action-red));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .pet-image-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pet-info-text h2 {
            color: var(--color-raisin-black);
            margin-bottom: 0.5rem;
        }

        .pet-info-text p {
            color: #666;
        }

        .form-card {
            background-color: var(--color-white);
            border-radius: 12px;
            padding: 3rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .form-title {
            color: var(--color-raisin-black);
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        .form-subtitle {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        /* ==========================================================================
           FORM ELEMENTS
           ========================================================================== */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--color-raisin-black);
        }

        .required {
            color: var(--color-action-red);
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            font-family: inherit;
            background-color: #fff;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--color-action-red);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .char-counter {
            text-align: right;
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .char-counter.warning {
            color: var(--color-action-red);
        }

        .helper-text {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .error-message {
            color: var(--color-action-red);
            font-size: 0.9rem;
            margin-top: 0.25rem;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .form-group.error input,
        .form-group.error textarea {
            border-color: var(--color-action-red);
        }

        /* ==========================================================================
           BUTTONS
           ========================================================================== */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: flex-end;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-primary {
            background-color: var(--color-action-red);
            color: var(--color-white);
        }

        .btn-primary:hover {
            background-color: #b33a4a;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(206, 66, 87, 0.3);
        }

        .btn-primary:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--color-raisin-black);
            border: 2px solid var(--color-raisin-black);
        }

        .btn-secondary:hover {
            background-color: var(--color-raisin-black);
            color: var(--color-white);
        }

        /* ==========================================================================
           FOOTER
           ========================================================================== */
        .footer {
            background-color: var(--color-raisin-black);
            color: var(--color-white);
            padding: 3rem 2rem 1rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: var(--color-peach-bg);
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: var(--color-white);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: var(--color-action-red);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            opacity: 0.8;
        }

        /* ==========================================================================
           RESPONSIVE
           ========================================================================== */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background-color: var(--color-fern-green);
                flex-direction: column;
                padding: 1rem;
                gap: 0;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-menu a {
                width: 100%;
                padding: 1rem;
            }

            .pet-info-card {
                flex-direction: column;
                text-align: center;
            }

            .form-card {
                padding: 2rem 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
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

    <script>
        // Toggle Menu Mobile
        function toggleMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        // Character Counter para el campo de motivación
        const motivoTextarea = document.getElementById('motivo_adopcion');
        const charCounter = document.getElementById('charCounter');

        motivoTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCounter.textContent = `${length} / 500 caracteres mínimo`;
            
            if (length >= 500) {
                charCounter.classList.remove('warning');
                charCounter.style.color = 'var(--color-fern-green)';
            } else {
                charCounter.classList.add('warning');
            }
        });

        // Form Validation
        const form = document.getElementById('adoptionForm');
        
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Reset errors
            document.querySelectorAll('.form-group').forEach(group => {
                group.classList.remove('error');
            });
            document.querySelectorAll('.error-message').forEach(msg => {
                msg.classList.remove('show');
            });

            // Validar Nombre
            const nombre = document.getElementById('nombre_completo');
            if (nombre.value.trim() === '') {
                showError('nombre_completo', 'error-nombre');
                isValid = false;
            }

            // Validar Email
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                showError('email', 'error-email');
                isValid = false;
            }

            // Validar Teléfono
            const telefono = document.getElementById('telefono');
            if (telefono.value.trim() === '') {
                showError('telefono', 'error-telefono');
                isValid = false;
            }

            // Validar Dirección
            const direccion = document.getElementById('direccion');
            if (direccion.value.trim() === '') {
                showError('direccion', 'error-direccion');
                isValid = false;
            }

            // Validar Descripción Hogar
            const hogar = document.getElementById('descripcion_hogar');
            if (hogar.value.trim() === '') {
                showError('descripcion_hogar', 'error-hogar');
                isValid = false;
            }

            // Validar Motivo (mínimo 500 caracteres)
            const motivo = document.getElementById('motivo_adopcion');
            if (motivo.value.trim().length < 500) {
                showError('motivo_adopcion', 'error-motivo');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                // Scroll al primer error
                const firstError = document.querySelector('.form-group.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            // Si es válido, se envía normalmente
        });

        function showError(fieldId, errorId) {
            const field = document.getElementById(fieldId);
            const error = document.getElementById(errorId);
            
            field.closest('.form-group').classList.add('error');
            error.classList.add('show');
        }
    </script>
</body>
</html>