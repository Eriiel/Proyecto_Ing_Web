<?php
require '../includes/db_connection.php';
proteger_pagina();

if (!isset($_GET['id'])) {
    header("Location: gestion_mascotas.php");
    exit();
}

try {
    // La restricción ON DELETE CASCADE en la base de datos se encargará de borrar las solicitudes asociadas.
    // Si no tuvieras esa restricción, necesitarías borrar las solicitudes primero:
    // $stmt_solicitudes = $pdo->prepare("DELETE FROM solicitudes WHERE id_mascota = ?");
    // $stmt_solicitudes->execute([$_GET['id']]);

    // Ahora, eliminar la mascota
    $stmt = $pdo->prepare("DELETE FROM mascotas WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    
    header("Location: gestion_mascotas.php");
    exit();
} catch (PDOException $e) {
    die("Error al eliminar la mascota: " . $e->getMessage());
}
?>