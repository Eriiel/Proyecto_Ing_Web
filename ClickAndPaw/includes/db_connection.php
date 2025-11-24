<?php
// ---- CONFIGURACIÓN DE LA BASE DE DATOS ----
$host = 'localhost';
$db_name = 'click_paw_db';
$username = 'root';
$password = ''; // Coloca aquí la contraseña de tu MySQL si tienes una (comúnmente es vacía en XAMPP).

// ---- INTENTO DE CONEXIÓN ----
try {
    $pdo = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    // Configurar el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Asegurar que la conexión use el cotejamiento UTF-8
    $pdo->exec("SET NAMES 'utf8mb4'");
} catch (PDOException $exception) {
    // Si la conexión falla, mostrar un mensaje de error y detener la ejecución
    echo "Error de conexión a la base de datos: " . $exception->getMessage();
    exit();
}

// ---- FUNCIÓN DE SEGURIDAD Y GESTIÓN DE SESIÓN ----
function proteger_pagina() {
    session_start();

    // --- LÓGICA DE TIEMPO DE SESIÓN ---
    $timeout_duration = 1800; // 30 minutos en segundos (30 * 60)

    if (isset($_SESSION['last_activity'])) {
        // Calcula el tiempo de inactividad
        $inactive_time = time() - $_SESSION['last_activity'];
        
        // Si el tiempo de inactividad supera la duración del timeout
        if ($inactive_time > $timeout_duration) {
            // Destruye la sesión
            session_unset();
            session_destroy();
            // Redirige al login con un mensaje de sesión expirada
            header("Location: login.php?status=expired");
            exit();
        }
    }
    // Actualiza la hora de la última actividad en cada carga de página
    $_SESSION['last_activity'] = time();
    // --- FIN DE LA LÓGICA DE TIEMPO DE SESIÓN ---


    // Verifica si el usuario está autenticado. Si no, lo redirige a la página de login.
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>