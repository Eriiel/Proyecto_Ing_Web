<?php
require '../includes/db_connection.php';

if (!isset($_GET['id'])) {
    header("Location: gestion_mascotas.php");
    exit();
}

$mascota_id = intval($_GET['id']);

$stmt = $mysqli->prepare("DELETE FROM mascotas WHERE id = ?");

if (!$stmt) {
    die("Error al preparar la consulta: " . $mysqli->error);
}

$stmt->bind_param("i", $mascota_id);

if (!$stmt->execute()) {
    die("Error al eliminar la mascota: " . $stmt->error);
}

$stmt->close();

header("Location: gestion_mascotas.php");
exit();
?>
