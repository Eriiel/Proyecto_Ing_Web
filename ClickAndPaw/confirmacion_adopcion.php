<?php
session_start();
// Si no hay un ID de solicitud en la sesión, redirigir a la galería
if (!isset($_SESSION['solicitud_id'])) {
    header('Location: galeria.php');
    exit();
}

$solicitud_id = $_SESSION['solicitud_id'];
unset($_SESSION['solicitud_id']);

$page_title = '¡Solicitud Enviada!';
$active_page = ''; 

include 'includes/header.php';
?>

<div class="container" style="padding-top: 4rem; padding-bottom: 4rem; text-align: center;">
    <div class="form-card" style="max-width: 600px; margin: 0 auto;">
        <div style="font-size: 4rem;">✅</div>
        <h1 class="form-title">¡Solicitud Recibida!</h1>
        <p class="form-subtitle">
            Hemos recibido tu solicitud de adopción. Tu número de referencia es: 
            <strong>#<?= htmlspecialchars($solicitud_id) ?></strong>
        </p>
        <p>Nuestro equipo revisará tu información y se pondrá en contacto contigo en las próximas 48-72 horas para continuar con el proceso. ¡Muchas gracias por querer cambiar una vida!</p>
        <div style="margin-top: 2rem;">
            <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>