<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit(); 
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require '../includes/db_connection.php'; 

    if (empty($_POST['usuario']) || empty($_POST['password'])) {
        $error_message = "Ambos campos son obligatorios.";
    } else {
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare("SELECT * FROM administradores WHERE usuario = :usuario");
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_usuario'] = $admin['usuario'];
                $_SESSION['last_activity'] = time();
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Usuario o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            $error_message = "Ocurrió un error en el sistema.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Administración</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css"> 
</head>
<body class="login-body">
    
    <div class="login-container">
        <h2>Panel de Administración</h2>
        
        <?php if (isset($_GET['status']) && $_GET['status'] == 'expired'): ?>
            <p class="message">Tu sesión ha expirado por inactividad. Por favor, inicia sesión de nuevo.</p>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <p class="message error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form action="login.php" method="post" novalidate>
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar sesión</button>
        </form>

        <!-- ================================================== -->
        <!-- ENLACE AÑADIDO PARA VOLVER AL SITIO PÚBLICO -->
        <!-- ================================================== -->
        <div class="back-to-site-link">
            <a href="../index.php">← Volver al sitio principal</a>
        </div>
        
    </div>

</body>
</html>