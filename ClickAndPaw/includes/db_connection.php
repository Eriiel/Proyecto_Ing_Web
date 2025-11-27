<?php
// ---- CONFIGURACIÓN DE LA BASE DE DATOS ----
$host = 'localhost';
$db_name = 'click_paw_db';
$username = 'admin';
$password = '$2y$10$GkY7aQXdz6kDWGUyBSG13.cd7jMqDfHLNpgpNmlPkk5Q4PrfYSZSq'; 

$dsn = "mysql:host=localhost;port=3307;dbname=click_paw_db;charset=utf8mb4";

// ---- INTENTO DE CONEXIÓN ----
try {
    $pdo = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8mb4'");
} catch (PDOException $exception) {
    echo "Error de conexión a la base de datos: " . $exception->getMessage();
    exit();
}

// ---- FUNCIÓN DE SEGURIDAD MODIFICADA (ACCESO LIBRE) ----
function proteger_pagina() {
    // Iniciamos sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // ELIMINAMOS LA VERIFICACIÓN DE SEGURIDAD Y EL TIMEOUT.
    // En su lugar, aseguramos que existan las variables de sesión 
    // para que los archivos del admin no den error al intentar mostrar el nombre.

    if (!isset($_SESSION['admin_id'])) {
        $_SESSION['admin_id'] = 1; // ID ficticio
        $_SESSION['admin_usuario'] = 'Administrador (Acceso Libre)'; // Nombre a mostrar en el dashboard
    }
    
    // Ya no hay redirección a login.php
}
?>