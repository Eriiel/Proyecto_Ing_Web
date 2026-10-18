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

// Validación
if (
    empty($nombre_solicitante) || 
    empty($email_solicitante) || 
    empty($telefono_solicitante) ||
    empty($direccion_solicitante) || 
    empty($motivo_adopcion) ||
    !filter_var($email_solicitante, FILTER_VALIDATE_EMAIL)
) {
    die('Error: Todos los campos son obligatorios y el email debe ser válido.');
}

// Consulta preparada
$sql = "INSERT INTO solicitudes 
        (id_mascota, nombre_solicitante, email_solicitante, telefono_solicitante, direccion_solicitante, motivo_adopcion)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la solicitud: " . $conn->error);
}

// Bind de parámetros
$stmt->bind_param(
    "isssss", 
    $id_mascota, 
    $nombre_solicitante, 
    $email_solicitante, 
    $telefono_solicitante, 
    $direccion_solicitante, 
    $motivo_adopcion
);

// Ejecutar la consulta
if (!$stmt->execute()) {
    die("Error al guardar la solicitud: " . $stmt->error);
}

// Obtener el ID insertado
$solicitud_id = $conn->insert_id;

// Guardar en sesión
$_SESSION['solicitud_id'] = $solicitud_id;

// Redirigir
header("Location: confirmacion_de_Adopcion.php");
exit();
?>
