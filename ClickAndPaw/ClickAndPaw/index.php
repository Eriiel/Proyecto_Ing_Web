<?php
// Incluir conexión a la BD
require 'includes/db_connection.php';

try {
    $stmt = $pdo->query("SELECT * FROM mascotas WHERE estado = 'Disponible' ORDER BY id DESC LIMIT 3");
    $mascotas_destacadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mascotas_destacadas = [];
    error_log("Error al obtener mascotas destacadas: " . $e->getMessage());
}

function obtenerEmoji($especie) {
    switch (strtolower($especie)) {
        case 'perro': return '🐕';
        case 'gato': return '🐈';
        default: return '🐾';
    }
}

$page_title = 'Click & Paw - Adopta, Salva una Vida';
$active_page = 'inicio';

include 'includes/header.php';
?>

<!-- ==========================================================================
   PÁGINA DE INICIO
   ========================================================================== -->

<section class="hero">
    <div class="hero-content">
        <h1>Dale a una mascota una segunda oportunidad</h1>
        <p>En Click & Paw rescatamos, rehabilitamos y reubicamos mascotas que necesitan un hogar lleno de amor.</p>
        <div class="cta-buttons">
            <a href="galeria.php" class="btn btn-primary">Ver Mascotas Disponibles</a>
            <a href="ayudar.php" class="btn btn-secondary">Quiero Ayudar</a>
        </div>
    </div>
</section>

<section class="mission-section">
    <div class="container">
        <h2 class="section-title">Nuestra Misión: Las 3 R's</h2>
        <div class="mission-grid">
            <div class="mission-card">
                <div class="mission-icon">🛟</div>
                <h3>Rescatar</h3>
                <p>Colaboramos con refugios locales para sacar a los animales de situaciones de riesgo, abandono o maltrato.</p>
            </div>
            <div class="mission-card">
                <div class="mission-icon">💚</div>
                <h3>Rehabilitar</h3>
                <p>Proveemos cuidado veterinario completo y trabajamos en la socialización de cada mascota.</p>
            </div>
            <div class="mission-card">
                <div class="mission-icon">🏡</div>
                <h3>Reubicar</h3>
                <p>Facilitamos un proceso de adopción responsable para que cada mascota encuentre el hogar perfecto.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
   SECCIÓN DE MASCOTAS 
   ========================================================================== -->
<section class="featured-pets">
    <div class="container">
        <h2 class="section-title">Conoce a Nuestras Mascotas</h2>
        
        <?php if (empty($mascotas_destacadas)): ?>
            <p style="text-align: center;">Actualmente no tenemos mascotas disponibles. ¡Vuelve pronto!</p>
        <?php else: ?>
            <div class="pets-grid">
                <?php foreach ($mascotas_destacadas as $mascota): ?>
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
                                <?= htmlspecialchars($mascota['especie']); ?> • 
                                <?= htmlspecialchars($mascota['edad']); ?> años • 
                                <?= htmlspecialchars($mascota['genero']); ?> • 
                                <?= htmlspecialchars($mascota['tamano']); ?>
                            </p>
                            <p class="pet-description"><?= htmlspecialchars(substr($mascota['descripcion'], 0, 100)); ?>...</p>
                            <a href="formulario_de_adopcion.php?id=<?= $mascota['id']; ?>" class="btn btn-primary">Adoptar a <?= htmlspecialchars($mascota['nombre']); ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="center-button">
            <a href="galeria.php" class="btn btn-primary">Ver Todas Las Mascotas</a>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2>¿Listo para cambiar una vida?</h2>
        <p>Cada adopción es una historia de amor que comienza. Sé parte de ella.</p>
        <a href="galeria.php" class="btn btn-primary">Adoptar Ahora</a>
    </div>
</section>

<?php
include 'includes/footer.php';
?>