<?php
session_start();

if (!isset($_SESSION['preguntas_id'])) {
    header('Location: contacto.php');
    exit();
}

$preguntas_id = $_SESSION['preguntas_id'];
unset($_SESSION['preguntas_id']);

$page_title = '¡Pregunta Enviada!';
$active_page = '';

include 'includes/header.php';
?>

<div class="container" style="padding-top: 4rem; padding-bottom: 4rem; text-align: center;">
    <div class="form-card" style="max-width: 600px; margin: 0 auto;">
        <div style="font-size: 4rem;">✅</div>
        <h1 class="form-title">¡Solicitud Recibida!</h1>
        <p class="form-subtitle">
            Hemos recibido tu mensaje. Tu número de referencia es: 
            <strong>#<?= htmlspecialchars($preguntas_id) ?></strong>
        </p>
        <p>Nuestro equipo revisará tu información y se pondrá en contacto contigo en las próximas 48-72 horas para responder cualquier duda.</p>
        <div style="margin-top: 2rem;">
            <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>