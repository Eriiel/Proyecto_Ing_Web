<?php
// Incluir conexión a la BD
require 'includes/db_connection.php'; // Asegúrate que aquí usas $mysqli en lugar de $pdo

$mascota_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($mascota_id === 0) {
    header('Location: galeria.php');
    exit();
}

// Obtener información de la mascota con MySQLi
$stmt = $conn->prepare("SELECT * FROM mascotas WHERE id = ? AND estado = 'Disponible'");
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $mysqli->error);
}

$stmt->bind_param("i", $mascota_id);
$stmt->execute();
$result = $stmt->get_result();
$mascota = $result->fetch_assoc();

if (!$mascota) {
    header('Location: galeria.php');
    exit();
}

$stmt->close();

function obtenerEmoji($especie) {
    switch (strtolower($especie)) {
        case 'perro': return '🐕';
        case 'gato': return '🐈';
        default: return '🐾';
    }
}

$page_title = 'Solicitud de Adopción para ' . htmlspecialchars($mascota['nombre']);
$active_page = 'galeria';

include 'includes/header.php';
?>

<nav class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-separator">›</li>
            <li><a href="galeria.php">Galería</a></li>
            <li class="breadcrumb-separator">›</li>
            <li class="breadcrumb-current">Solicitud de Adopción</li>
        </ul>
    </div>
</nav>

<section class="form-section">
    <div class="container">
        <div class="pet-info-card">
            <div class="pet-image-small">
                <?php if (!empty($mascota['foto'])): ?>
                    <img src="uploads/<?= htmlspecialchars($mascota['foto']); ?>" 
                         alt="<?= htmlspecialchars($mascota['nombre']); ?>">
                <?php else: ?>
                    <?= obtenerEmoji($mascota['especie']); ?>
                <?php endif; ?>
            </div>
            <div class="pet-info-text">
                <h2>Estás aplicando para adoptar a: 
                    <strong><?= htmlspecialchars($mascota['nombre']); ?></strong>
                </h2>
            </div>
        </div>

        <div class="form-card">
            <h1 class="form-title">Formulario de Adopción</h1>
            <p class="form-subtitle">
                Por favor, completa todos los campos con la mayor sinceridad posible.
                Esto nos ayuda a asegurar el mejor futuro para nuestras mascotas.
            </p>

            <form id="adoptionForm" method="POST" action="procesar_Adopcion.php">
                <input type="hidden" name="id_mascota" value="<?= $mascota_id; ?>">

                <div class="form-group">
                    <label for="nombre_solicitante">Nombre Completo <span class="required">*</span></label>
                    <input type="text" id="nombre_solicitante" name="nombre_solicitante" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email_solicitante">Correo Electrónico <span class="required">*</span></label>
                        <input type="email" id="email_solicitante" name="email_solicitante" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono_solicitante">Teléfono <span class="required">*</span></label>
                        <input type="tel" id="telefono_solicitante" name="telefono_solicitante" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="direccion_solicitante">Dirección <span class="required">*</span></label>
                    <input type="text" id="direccion_solicitante" name="direccion_solicitante" required>
                </div>
                
                <div class="form-group">
                    <label for="motivo_adopcion">
                        ¿Por qué quieres adoptar a <?= htmlspecialchars($mascota['nombre']); ?>? 
                        <span class="required">*</span>
                    </label>
                    <textarea id="motivo_adopcion" name="motivo_adopcion" required 
                              placeholder="Cuéntanos por qué te interesa esta mascota y cómo sería su vida contigo..."></textarea>
                </div>

                <div class="form-actions">
                    <a href="galeria.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>
