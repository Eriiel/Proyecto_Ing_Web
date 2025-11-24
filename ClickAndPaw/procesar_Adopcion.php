<?php
session_start();
require 'includes/db_connection.php';

// Verificar que el formulario fue enviado por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: galeria.php');
    exit();
}

// Obtener datos del formulario
$id_mascota = $_POST['id_mascota'] ?? 0;
$nombre_solicitante = trim($_POST['nombre_solicitante'] ?? '');
$email_solicitante = trim($_POST['email_solicitante'] ?? '');
$telefono_solicitante = trim($_POST['telefono_solicitante'] ?? '');
$direccion_solicitante = trim($_POST['direccion_solicitante'] ?? '');
$motivo_adopcion = trim($_POST['motivo_adopcion'] ?? '');

// Validaciones básicas en el servidor
if (empty($nombre_solicitante) || empty($email_solicitante) || empty($telefono_solicitante) || empty($direccion_solicitante) || empty($motivo_adopcion) || !filter_var($email_solicitante, FILTER_VALIDATE_EMAIL)) {
    // Si la validación falla, podrías redirigir con un mensaje de error.
    // Por simplicidad, por ahora simplemente detenemos la ejecución.
    die('Error: Todos los campos son obligatorios y el email debe ser válido.');
}

try {
    // Insertar la solicitud en la base de datos usando PDO
    $sql = "INSERT INTO solicitudes (id_mascota, nombre_solicitante, email_solicitante, telefono_solicitante, direccion_solicitante, motivo_adopcion) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $id_mascota,
        $nombre_solicitante,
        $email_solicitante,
        $telefono_solicitante,
        $direccion_solicitante,
        $motivo_adopcion
    ]);

    // Obtener el ID de la solicitud recién insertada
    $solicitud_id = $pdo->lastInsertId();

    // Guardar información en la sesión para la página de confirmación
    $_SESSION['solicitud_id'] = $solicitud_id;
    
    // Redirigir a una página de confirmación (aún no la hemos creado, pero lo haremos)
    header("Location: confirmacion_adopcion.php");
    exit();

} catch (PDOException $e) {
    die("Error al procesar la solicitud: " . $e->getMessage());
}
?>