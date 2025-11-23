<?php
// Conexión a la base de datos
include 'includes/db.php';

// Obtener filtros de la URL
$filtro_especie = isset($_GET['especie']) ? $_GET['especie'] : '';
$filtro_edad = isset($_GET['edad']) ? $_GET['edad'] : '';
$filtro_tamano = isset($_GET['tamano']) ? $_GET['tamano'] : '';
$filtro_genero = isset($_GET['genero']) ? $_GET['genero'] : '';
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

// Construir la consulta SQL con filtros
$sql = "SELECT * FROM mascotas WHERE estado = 'Disponible'";
$params = [];
$types = "";

// Aplicar filtros
if (!empty($filtro_especie)) {
    $sql .= " AND especie = ?";
    $params[] = $filtro_especie;
    $types .= "s";
}

if (!empty($filtro_edad)) {
    $sql .= " AND edad_categoria = ?";
    $params[] = $filtro_edad;
    $types .= "s";
}

if (!empty($filtro_tamano)) {
    $sql .= " AND tamano = ?";
    $params[] = $filtro_tamano;
    $types .= "s";
}

if (!empty($filtro_genero)) {
    $sql .= " AND genero = ?";
    $params[] = $filtro_genero;
    $types .= "s";
}

if (!empty($busqueda)) {
    $sql .= " AND nombre LIKE ?";
    $params[] = "%$busqueda%";
    $types .= "s";
}

$sql .= " ORDER BY fecha_registro DESC";

// Preparar y ejecutar la consulta
if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$mascotas = [];
while ($row = $result->fetch_assoc()) {
    $mascotas[] = $row;
}

// Función para determinar emoji según especie
function obtenerEmoji($especie) {
    $especie_lower = strtolower($especie);
    switch($especie_lower) {
        case 'perro': return '🐕';
        case 'gato': return '🐈';
        case 'conejo': return '🐰';
        case 'ave': return '🦜';
        case 'hamster': return '🐹';
        default: return '🐾';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas Disponibles - Click & Paw</title>
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
           HERO SECTION
           ========================================================================== */
        .page-hero {
            background: linear-gradient(135deg, var(--color-fern-green) 0%, var(--color-raisin-black) 100%);
            color: var(--color-white);
            padding: 4rem 2rem;
            text-align: center;
        }

        .page-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .page-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* ==========================================================================
           FILTERS SECTION
           ========================================================================== */
        .filters-section {
            background-color: var(--color-white);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 1200px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .search-bar {
            margin-bottom: 1.5rem;
        }

        .search-form {
            display: flex;
            gap: 0.5rem;
        }

        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--color-action-red);
        }

        .search-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            background-color: var(--color-action-red);
            color: var(--color-white);
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-btn:hover {
            background-color: #b33a4a;
            transform: translateY(-2px);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--color-raisin-black);
        }

        .filter-group select {
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            background-color: var(--color-white);
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .filter-group select:focus {
            outline: none;
            border-color: var(--color-action-red);
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            justify-content: flex-end;
        }

        .btn-filter {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: var(--color-action-red);
            color: var(--color-white);
        }

        .btn-primary:hover {
            background-color: #b33a4a;
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--color-raisin-black);
            border: 2px solid #ddd;
        }

        .btn-secondary:hover {
            background-color: #f5f5f5;
        }

        /* Active Filters */
        .active-filters {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-tag {
            background-color: var(--color-fern-green);
            color: var(--color-white);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-tag button {
            background: none;
            border: none;
            color: var(--color-white);
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0;
            line-height: 1;
        }

        /* ==========================================================================
           RESULTS SECTION
           ========================================================================== */
        .results-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .results-count {
            font-size: 1.1rem;
            color: var(--color-raisin-black);
            font-weight: 600;
        }

        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            background-color: var(--color-white);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .no-results-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .no-results h2 {
            color: var(--color-raisin-black);
            margin-bottom: 1rem;
        }

        .no-results p {
            color: #666;
            margin-bottom: 1.5rem;
        }

        /* ==========================================================================
           PETS GRID
           ========================================================================== */
        .pets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .pet-card {
            background-color: var(--color-white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .pet-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .pet-image {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, var(--color-fern-green), var(--color-action-red));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            position: relative;
            overflow: hidden;
        }

        .pet-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pet-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background-color: var(--color-action-red);
            color: var(--color-white);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .pet-info {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .pet-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--color-raisin-black);
            margin-bottom: 0.5rem;
        }

        .pet-details {
            color: #666;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .pet-description {
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pet-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
            display: block;
        }

        .btn-view {
            background-color: var(--color-fern-green);
            color: var(--color-white);
        }

        .btn-view:hover {
            background-color: #455a2f;
        }

        .btn-adopt {
            background-color: var(--color-action-red);
            color: var(--color-white);
        }

        .btn-adopt:hover {
            background-color: #b33a4a;
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

            .page-hero h1 {
                font-size: 2rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .search-form {
                flex-direction: column;
            }

            .filter-actions {
                flex-direction: column;
            }

            .btn-filter {
                width: 100%;
            }

            .pets-grid {
                grid-template-columns: 1fr;
            }

            .results-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
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
                <li><a href="galeria.php" style="background-color: var(--color-action-red);">Mascotas Disponibles</a></li>
                <li><a href="proceso-adopcion.php">Proceso de Adopción</a></li>
                <li><a href="como-ayudar.php">Cómo Ayudar</a></li>
                <li><a href="sobre-nosotros.php">Sobre Nosotros</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <!-- HERO -->
    <section class="page-hero">
        <h1>🐾 Encuentra a tu Compañero Perfecto</h1>
        <p>Todas nuestras mascotas están rescatadas, rehabilitadas y listas para un nuevo hogar</p>
    </section>

    <!-- FILTERS -->
    <section class="filters-section">
        <form method="GET" action="galeria.php" id="filterForm">
            <!-- Search Bar -->
            <div class="search-bar">
                <div class="search-form">
                    <input type="text" 
                           name="buscar" 
                           class="search-input" 
                           placeholder="🔍 Buscar por nombre..." 
                           value="<?php echo htmlspecialchars($busqueda); ?>">
                    <button type="submit" class="search-btn">Buscar</button>
                </div>
            </div>

            <!-- Filters Grid -->
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="especie">Especie</label>
                    <select name="especie" id="especie">
                        <option value="">Todas las especies</option>
                        <option value="Perro" <?php echo ($filtro_especie == 'Perro') ? 'selected' : ''; ?>>🐕 Perros</option>
                        <option value="Gato" <?php echo ($filtro_especie == 'Gato') ? 'selected' : ''; ?>>🐈 Gatos</option>
                        <option value="Conejo" <?php echo ($filtro_especie == 'Conejo') ? 'selected' : ''; ?>>🐰 Conejos</option>
                        <option value="Ave" <?php echo ($filtro_especie == 'Ave') ? 'selected' : ''; ?>>🦜 Aves</option>
                        <option value="Otro" <?php echo ($filtro_especie == 'Otro') ? 'selected' : ''; ?>>🐾 Otros</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="edad">Edad</label>
                    <select name="edad" id="edad">
                        <option value="">Todas las edades</option>
                        <option value="Cachorro" <?php echo ($filtro_edad == 'Cachorro') ? 'selected' : ''; ?>>Cachorro/Joven</option>
                        <option value="Adulto" <?php echo ($filtro_edad == 'Adulto') ? 'selected' : ''; ?>>Adulto</option>
                        <option value="Senior" <?php echo ($filtro_edad == 'Senior') ? 'selected' : ''; ?>>Senior</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="tamano">Tamaño</label>
                    <select name="tamano" id="tamano">
                        <option value="">Todos los tamaños</option>
                        <option value="Pequeño" <?php echo ($filtro_tamano == 'Pequeño') ? 'selected' : ''; ?>>Pequeño</option>
                        <option value="Mediano" <?php echo ($filtro_tamano == 'Mediano') ? 'selected' : ''; ?>>Mediano</option>
                        <option value="Grande" <?php echo ($filtro_tamano == 'Grande') ? 'selected' : ''; ?>>Grande</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="genero">Género</label>
                    <select name="genero" id="genero">
                        <option value="">Ambos géneros</option>
                        <option value="Macho" <?php echo ($filtro_genero == 'Macho') ? 'selected' : ''; ?>>Macho</option>
                        <option value="Hembra" <?php echo ($filtro_genero == 'Hembra') ? 'selected' : ''; ?>>Hembra</option>
                    </select>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="filter-actions">
                <a href="galeria.php" class="btn-filter btn-secondary">Limpiar Filtros</a>
                <button type="submit" class="btn-filter btn-primary">Aplicar Filtros</button>
            </div>

            <!-- Active Filters Tags -->
            <?php if (!empty($filtro_especie) || !empty($filtro_edad) || !empty($filtro_tamano) || !empty($filtro_genero) || !empty($busqueda)): ?>
            <div class="active-filters">
                <?php if (!empty($busqueda)): ?>
                    <span class="filter-tag">
                        Búsqueda: "<?php echo htmlspecialchars($busqueda); ?>"
                        <button type="button" onclick="removeFilter('buscar')">×</button>
                    </span>
                <?php endif; ?>
                <?php if (!empty($filtro_especie)): ?>
                    <span class="filter-tag">
                        Especie: <?php echo htmlspecialchars($filtro_especie); ?>
                        <button type="button" onclick="removeFilter('especie')">×</button>
                    </span>
                <?php endif; ?>
                <?php if (!empty($filtro_edad)): ?>
                    <span class="filter-tag">
                        Edad: <?php echo htmlspecialchars($filtro_edad); ?>
                        <button type="button" onclick="removeFilter('edad')">×</button>
                    </span>
                <?php endif; ?>
                <?php if (!empty($filtro_tamano)): ?>
                    <span class="filter-tag">
                        Tamaño: <?php echo htmlspecialchars($filtro_tamano); ?>
                        <button type="button" onclick="removeFilter('tamano')">×</button>
                    </span>
                <?php endif; ?>
                <?php if (!empty($filtro_genero)): ?>
                    <span class="filter-tag">
                        Género: <?php echo htmlspecialchars($filtro_genero); ?>
                        <button type="button" onclick="removeFilter('genero')">×</button>
                    </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </form>
    </section>

    <!-- RESULTS -->
    <section class="results-section">
        <div class="results-header">
            <div class="results-count">
                <?php 
                $total = count($mascotas);
                echo $total . " mascota" . ($total != 1 ? "s" : "") . " disponible" . ($total != 1 ? "s" : "");
                ?>
            </div>
        </div>

        <?php if (empty($mascotas)): ?>
            <!-- No Results -->
            <div class="no-results">
                <div class="no-results-icon">🔍</div>
                <h2>No encontramos mascotas con esos criterios</h2>
                <p>Intenta ajustar los filtros o explorar todas nuestras mascotas disponibles</p>
                <a href="galeria.php" class="btn btn-adopt">Ver Todas las Mascotas</a>
            </div>
        <?php else: ?>
            <!-- Pets Grid -->
            <div class="pets-grid">
                <?php foreach ($mascotas as $mascota): ?>
                    <div class="pet-card">
                        <div class="pet-image">
                            <?php if (!empty($mascota['foto_principal'])): ?>
                                <img src="img/mascotas/<?php echo htmlspecialchars($mascota['foto_principal']); ?>" 
                                     alt="<?php echo htmlspecialchars($mascota['nombre']); ?>">
                            <?php else: ?>
                                <?php echo obtenerEmoji($mascota['especie']); ?>
                            <?php endif; ?>
                            <span class="pet-badge"><?php echo htmlspecialchars($mascota['especie']); ?></span>
                        </div>
                        <div class="pet-info">
                            <h3 class="pet-name"><?php echo htmlspecialchars($mascota['nombre']); ?></h3>
                            <p class="pet-details">
                                <?php echo htmlspecialchars($mascota['edad']); ?> años • 
                                <?php echo htmlspecialchars($mascota['genero']); ?> • 
                                <?php echo htmlspecialchars($mascota['tamano']); ?>
                            </p>
                            <p class="pet-description">
                                <?php echo htmlspecialchars(substr($mascota['descripcion'], 0, 120)); ?>...
                            </p>
                            <div class="pet-actions">
                                <a href="perfil-mascota.php?id=<?php echo $mascota['id']; ?>" class="btn btn-view">Ver Perfil</a>
                                <a href="formulario-adopcion.php?id=<?php echo $mascota['id']; ?>" class="btn btn-adopt">Adoptar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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

        // Remove individual filter
        function removeFilter(filterName) {
            const url = new URL(window.location);
            url.searchParams.delete(filterName);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>