<?php
require 'includes/db_connection.php';
try {
    $stmt = $pdo->query("SELECT DATABASE() as db");
    $row = $stmt->fetch();
    echo "Conectado correctamente. DB actual: " . ($row['db'] ?? 'N/A');
} catch (Exception $e) {
    echo "Fallo en la consulta de prueba: " . $e->getMessage();
}
