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
    <style>
        :root {
            --color-peach-bg: #FFC49B;
            --color-licorice-text: #130303;
            --color-fern-green: #566E3D;
            --color-action-red: #CE4257;
            --color-raisin-black: #342E37;
            --color-white: #FFFFFF;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, var(--color-fern-green) 0%, var(--color-raisin-black) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .confirmation-card {
            background-color: var(--color-white);
            border-radius: 16px;
            padding: 4rem 3rem;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: bounce 0.6s ease-in-out;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        h1 {
            color: var(--color-fern-green);
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }

        .solicitud-number {
            background-color: var(--color-peach-bg);
            padding: 1rem 2rem;
            border-radius: 8px;
            margin: 2rem 0;
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--color-raisin-black);
        }

        .message {
            color: #555;
            line-height: 1.8;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .next-steps {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem 0;
            text-align: left;
        }

        .next-steps h3 {
            color: var(--color-raisin-black);
            margin-bottom: 1rem;
        }

        .next-steps ul {
            list-style: none;
            padding-left: 0;
        }

        .next-steps li {
            padding: 0.5rem 0 0.5rem 2rem;
            position: relative;
            color: #555;
        }

        .next-steps li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--color-fern-green);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
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

        .btn-secondary {
            background-color: transparent;
            color: var(--color-raisin-black);
            border: 2px solid var(--color-raisin-black);
        }

        .btn-secondary:hover {
            background-color: var(--color-raisin-black);
            color: var(--color-white);
        }

        @media (max-width: 768px) {
            .confirmation-card {
                padding: 3rem 2rem;
            }

            h1 {
                font-size: 2rem;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="confirmation-card">
        <div class="success-icon">✅</div>
        <h1>¡Solicitud Enviada!</h1>
        
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