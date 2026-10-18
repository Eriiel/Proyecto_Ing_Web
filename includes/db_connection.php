<?php

$host = 'localhost';
$db_name = 'click_paw_db';
$username = 'root';
$password = '';  //Usar contraseña real de MySQL

//CONEXIÓN 
$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Error de conexión a MySQL: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// ==========================================================================
// FUNCIÓN PARA VERIFICAR CREDENCIALES DE ADMINISTRADOR
// ==========================================================================
function verificar_admin($conn, $usuario, $password) {
    // Preparamos la consulta para evitar inyección SQL
    $stmt = $conn->prepare("SELECT id, usuario, password_hash, nombre_completo, activo FROM administradores WHERE usuario = ? LIMIT 1");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Si no existe el usuario
    if ($result->num_rows === 0) {
        $stmt->close();
        return false;
    }
    
    $admin = $result->fetch_assoc();
    $stmt->close();
    
    // Verificar si el administrador está activo
    if ($admin['activo'] != 1) {
        return false;
    }
    
    // Verificar la contraseña con el hash
    if (password_verify($password, $admin['password_hash'])) {
        // Actualizar último acceso
        $stmt_update = $conn->prepare("UPDATE administradores SET ultimo_acceso = NOW() WHERE id = ?");
        $stmt_update->bind_param("i", $admin['id']);
        $stmt_update->execute();
        $stmt_update->close();
        
        // Retornar datos del administrador
        return [
            'id' => $admin['id'],
            'usuario' => $admin['usuario'],
            'nombre_completo' => $admin['nombre_completo']
        ];
    }
    
    return false;
}

// ==========================================================================
// FUNCIÓN DE SEGURIDAD PARA LAS PÁGINAS DEL PANEL DE ADMINISTRACIÓN
// ==========================================================================
function proteger_pagina() {
    // Si no hay una sesión iniciada, la iniciamos.
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // --- LÓGICA DE TIEMPO DE SESIÓN ---
    $timeout_duration = 1800; // 30 minutos

    // Si la sesión existe y ha estado inactiva demasiado tiempo...
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
        // ...destruimos la sesión y redirigimos al login.
        session_unset();
        session_destroy();
        header("Location: login.php?status=expired");
        exit();
    }
    
    // Actualizamos la marca de tiempo de la última actividad.
    $_SESSION['last_activity'] = time();

    // --- LÓGICA DE AUTENTICACIÓN ---
    // Si no existe la variable de sesión 'admin_id' (que solo se crea en un login exitoso)...
    if (!isset($_SESSION['admin_id'])) {
        // ...redirigimos al usuario a la página de login y detenemos el script.
        header("Location: login.php");
        exit();
    }
}

// ==========================================================================
// FUNCIÓN PARA CREAR HASH DE CONTRASEÑA (ÚTIL PARA CREAR NUEVOS ADMINS)
// ==========================================================================
function crear_password_hash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// ==========================================================================
// FUNCIÓN PARA CAMBIAR CONTRASEÑA DE ADMINISTRADOR
// ==========================================================================
function cambiar_password_admin($conn, $admin_id, $password_actual, $password_nueva) {
    // Verificar contraseña actual
    $stmt = $conn->prepare("SELECT password_hash FROM administradores WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        return false;
    }
    
    $admin = $result->fetch_assoc();
    $stmt->close();
    
    // Verificar que la contraseña actual sea correcta
    if (!password_verify($password_actual, $admin['password_hash'])) {
        return false;
    }
    
    // Crear hash de la nueva contraseña
    $nuevo_hash = crear_password_hash($password_nueva);
    
    // Actualizar en la base de datos
    $stmt_update = $conn->prepare("UPDATE administradores SET password_hash = ? WHERE id = ?");
    $stmt_update->bind_param("si", $nuevo_hash, $admin_id);
    $resultado = $stmt_update->execute();
    $stmt_update->close();
    
    return $resultado;
}
?>