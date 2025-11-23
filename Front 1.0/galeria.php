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

// Ejecutar consulta
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

// Función para emoji
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
    <link rel="stylesheet" href="CSS/CCS_STYLE.css">
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
                <li><a href="galeria.php" class="active">Mascotas Disponibles</a></li>
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
                    <input type="text" name="buscar" class="search-input" placeholder="🔍 Buscar por nombre..." value="<?php echo htmlspecialchars($busqueda); ?>">
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

    <script src="js/main.js"></script>
</body>
</html>