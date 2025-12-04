<?php

$host = 'localhost';
$db_name = 'click_paw_db';
$username = 'root';
$password = '';
$port = 3306;

// CONEXIÓN
$conn = new mysqli($host, $username, $password, $db_name, $port);

if ($conn->connect_error) {
    die("Error de conexión a MySQL: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_usuario'] = 'Administrador (Acceso Libre)';
}


// =====================================================
//   FUNCIÓN db_query() PARA PREPARED STATEMENTS
// =====================================================
function db_query($sql, $params = [])
{
    global $conn;

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en prepare(): " . $conn->error);
    }

    if (!empty($params)) {
        // Detecta tipos automáticamente
        $types = "";
        foreach ($params as $p) {
            $types .= is_int($p) ? "i" : "s";
        }

        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        die("Error en execute(): " . $stmt->error);
    }

    return $stmt;
}
?>