<?php
session_start();

require 'includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    // Validación simple en el servidor
    if (empty($nombre) || empty($apellido) || !filter_var($correo, FILTER_VALIDATE_EMAIL) || empty($mensaje)) {
        // Si hay un error, guardar un mensaje de error y redirigir de vuelta
        $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Por favor, completa todos los campos correctamente.'];
        header('Location: contacto.php');
        exit();
    }

    // Guardar los datos en la base de datos
    try {
        $sql = "INSERT INTO contactos (nombre, apellido, correo, mensaje) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $apellido, $correo, $mensaje);
        $stmt->execute();
        
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => '¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.'];

    } catch (Exception $e) {
        $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.'];
        // error_log('Error al guardar contacto: ' . $e->getMessage());
    }

    header('Location: contacto.php');
    exit();

} else {
    header('Location: index.php');
    exit();
}
?>