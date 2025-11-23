<?php
session_start();

// Verificar que hay datos de confirmación
if (!isset($_SESSION['success']) || !isset($_SESSION['solicitud_id'])) {
    header('Location: galeria.php');
    exit();
}

$solicitud_id = $_SESSION['solicitud_id'];
$mascota_nombre = $_SESSION['mascota_nombre'] ?? 'tu nueva mascota';
$mensaje = $_SESSION['success'];

// Limpiar las variables de sesión
unset($_SESSION['success']);
unset($_SESSION['solicitud_id']);
unset($_SESSION['mascota_nombre']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Enviada - Click & Paw</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body style="background: linear-gradient(135deg, var(--color-fern-green) 0%, var(--color-raisin-black) 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    
    <div class="confirmation-card">
        <div class="success-icon">✅</div>
        <h1 style="color: var(--color-fern-green); margin-bottom: 1rem; font-size: 2.5rem;">¡Solicitud Enviada!</h1>
        
        <div class="solicitud-number">
            Solicitud #<?php echo str_pad($solicitud_id, 6, '0', STR_PAD_LEFT); ?>
        </div>

        <p class="message">
            Gracias por tu interés en adoptar a <strong><?php echo htmlspecialchars($mascota_nombre); ?></strong>. 
            Hemos recibido tu solicitud y la revisaremos cuidadosamente.
        </p>

        <div class="next-steps">
            <h3>📋 Próximos Pasos</h3>
            <ul>
                <li>Recibirás un correo de confirmación en las próximas horas</li>
                <li>Nuestro equipo revisará tu solicitud en 2-3 días hábiles</li>
                <li>Te contactaremos para agendar una entrevista</li>
                <li>Si todo va bien, ¡podrás conocer a tu nueva mascota!</li>
            </ul>
        </div>

        <p class="message">
            <strong>Guarda tu número de solicitud para futuras referencias.</strong>
        </p>

        <div class="buttons">
            <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
            <a href="galeria.php" class="btn btn-secondary">Ver Más Mascotas</a>
        </div>
    </div>

</body>
</html>