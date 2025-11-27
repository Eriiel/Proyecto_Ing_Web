<?php
session_start();
// Incluir conexión a la BD
require 'includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: galeria.php');
    exit();
}

$id_mascota = $_POST['id_mascota'] ?? 0;
$nombre_solicitante = trim($_POST['nombre_solicitante'] ?? '');
$email_solicitante = trim($_POST['email_solicitante'] ?? '');
$telefono_solicitante = trim($_POST['telefono_solicitante'] ?? '');
$direccion_solicitante = trim($_POST['direccion_solicitante'] ?? '');
$motivo_adopcion = trim($_POST['motivo_adopcion'] ?? '');

if (empty($nombre_solicitante) || empty($email_solicitante) || empty($telefono_solicitante) || empty($direccion_solicitante) || empty($motivo_adopcion) || !filter_var($email_solicitante, FILTER_VALIDATE_EMAIL)) {
    // Si la validación falla:
    die('Error: Todos los campos son obligatorios y el email debe ser válido.');
}

try {
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

    $solicitud_id = $pdo->lastInsertId();

    $_SESSION['solicitud_id'] = $solicitud_id;
    
    header("Location: confirmacion_adopcion.php");
    exit();

} catch (PDOException $e) {
    die("Error al procesar la solicitud: " . $e->getMessage());
}
?>