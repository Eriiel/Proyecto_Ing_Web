<?php
// Incluir conexión a la BD
require 'includes/db_connection.php';

$page_title = 'Galería de Mascotas - Click & Paw';
$active_page = 'galeria';

$filtro_especie = $_GET['especie'] ?? '';
$filtro_tamano = $_GET['tamano'] ?? '';
$filtro_genero = $_GET['genero'] ?? '';
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

$sql_parts = [];
$params = [];

$sql = "SELECT * FROM mascotas WHERE estado = 'Disponible'";

if (!empty($filtro_especie)) {
    $sql_parts[] = "especie = ?";
    $params[] = $filtro_especie;
}
if (!empty($filtro_tamano)) {
    $sql_parts[] = "tamano = ?";
    $params[] = $filtro_tamano;
}
if (!empty($filtro_genero)) {
    $sql_parts[] = "genero = ?";
    $params[] = $filtro_genero;
}
if (!empty($busqueda)) {
    $sql_parts[] = "nombre LIKE ?";
    $params[] = "%$busqueda%";
}

if (!empty($sql_parts)) {
    $sql .= " AND " . implode(" AND ", $sql_parts);
}

$sql .= " ORDER BY id DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $mascotas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar las mascotas: " . $e->getMessage());
}
function obtenerEmoji($especie) {
    switch (strtolower($especie)) {
        case 'perro': return '🐕';
        case 'gato': return '🐈';
        default: return '🐾';
    }
}

include 'includes/header.php';
?>

<!-- ==========================================================================
   HERO
   ========================================================================== -->
<section class="page-hero">
    <div class="container">
        <h1>Encuentra a tu Compañero Perfecto</h1>
        <p>Cada una de estas mascotas ha sido rescatada y está esperando una segunda oportunidad. ¡La tuya podría ser la que cambie su vida!</p>
    </div>
</section>

<!-- ==========================================================================
   FILTROS
   ========================================================================== -->
<section class="filters-section">
    <div class="container">
        <form method="GET" action="galeria.php" id="filterForm">
            <div class="search-bar">
                <div class="search-form">
                    <input type="text" name="buscar" class="search-input" placeholder="🔍 Buscar por nombre..." value="<?= htmlspecialchars($busqueda); ?>">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="especie">Especie</label>
                    <select name="especie" id="especie">
                        <option value="">Todas</option>
                        <option value="Perro" <?= ($filtro_especie == 'Perro') ? 'selected' : ''; ?>>Perros</option>
                        <option value="Gato" <?= ($filtro_especie == 'Gato') ? 'selected' : ''; ?>>Gatos</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="tamano">Tamaño</label>
                    <select name="tamano" id="tamano">
                        <option value="">Todos</option>
                        <option value="Pequeño" <?= ($filtro_tamano == 'Pequeño') ? 'selected' : ''; ?>>Pequeño</option>
                        <option value="Mediano" <?= ($filtro_tamano == 'Mediano') ? 'selected' : ''; ?>>Mediano</option>
                        <option value="Grande" <?= ($filtro_tamano == 'Grande') ? 'selected' : ''; ?>>Grande</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="genero">Género</label>
                    <select name="genero" id="genero">
                        <option value="">Todos</option>
                        <option value="Macho" <?= ($filtro_genero == 'Macho') ? 'selected' : ''; ?>>Macho</option>
                        <option value="Hembra" <?= ($filtro_genero == 'Hembra') ? 'selected' : ''; ?>>Hembra</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <a href="galeria.php" class="btn btn-secondary" style="color:var(--color-licorice-text); border-color:var(--color-licorice-text);">Limpiar</a>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- ==========================================================================
   GALERÍA
   ========================================================================== -->
<section class="results-section">
    <div class="container">
        <div class="results-header">
            <div class="results-count">
                <strong><?= count($mascotas) ?></strong> mascota(s) encontrada(s)
            </div>
        </div>

        <?php if (empty($mascotas)): ?>
            <div class="no-results">
                <h2>No encontramos mascotas con esos criterios</h2>
                <p>Intenta ajustar los filtros o explorar todas nuestras mascotas disponibles.</p>
                <a href="galeria.php" class="btn btn-primary">Ver Todas las Mascotas</a>
            </div>
        <?php else: ?>
            <div class="pets-grid">
                <?php foreach ($mascotas as $mascota): ?>
                    <div class="pet-card">
                        <div class="pet-image">
                            <?php if (!empty($mascota['foto'])): ?>
                                <img src="uploads/<?= htmlspecialchars($mascota['foto']); ?>" alt="Foto de <?= htmlspecialchars($mascota['nombre']); ?>">
                            <?php else: ?>
                                <?= obtenerEmoji($mascota['especie']); ?>
                            <?php endif; ?>
                        </div>
                        <div class="pet-info">
                            <h3 class="pet-name"><?= htmlspecialchars($mascota['nombre']); ?></h3>
                            <p class="pet-details">
                                <?= htmlspecialchars($mascota['edad']); ?> años • 
                                <?= htmlspecialchars($mascota['genero']); ?> • 
                                <?= htmlspecialchars($mascota['tamano']); ?>
                            </p>
                            <p class="pet-description">
                                <?= htmlspecialchars(substr($mascota['descripcion'], 0, 100)); ?>...
                            </p>
                            <a href="formulario_de_adopcion.php?id=<?= $mascota['id']; ?>" class="btn btn-primary">Adoptar a <?= htmlspecialchars($mascota['nombre']); ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
include 'includes/footer.php';
?>