<?php
// Iniciar sesión y asignar variables por si acaso se entra directo aquí
session_start();
$_SESSION['admin_id'] = 1;
$_SESSION['admin_usuario'] = 'Administrador (Acceso Libre)';

// Redirigir directamente al dashboard
header("Location: index.php");
exit();
?>