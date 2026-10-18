<?php
// Iniciar sesión ANTES de cualquier salida
session_start();

require_once '../includes/db_connection.php';

// Si ya inició sesión, enviarlo al panel
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$mensaje = "";

// Verificar si la sesión expiró
if (isset($_GET['status']) && $_GET['status'] === 'expired') {
    $mensaje = "Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validación básica
    if (empty($usuario) || empty($password)) {
        $error = "Por favor, completa todos los campos.";
    } else {
        // Verificar credenciales en la base de datos
        try {
            $admin = verificar_admin($conn, $usuario, $password);
            
            if ($admin !== false) {
                // Login exitoso - crear sesión
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_usuario'] = $admin['usuario'];
                $_SESSION['admin_nombre'] = $admin['nombre_completo'];
                $_SESSION['last_activity'] = time();

                header("Location: index.php");
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }
        } catch (Exception $e) {
            $error = "Error en el sistema: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador - Click & Paw</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body class="login-body">

<div class="login-container">
    <h2>Acceso Administrador</h2>

    <?php if (!empty($mensaje)): ?>
        <p class="message"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="" method="POST" class="login-form">

        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required autofocus>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="btn btn-primary">Ingresar</button>
    </form>

    <div class="back-to-site-link">
        <a href="../index.php" class="btn">Volver a Home</a>
    </div>
</div>

</body>
</html>