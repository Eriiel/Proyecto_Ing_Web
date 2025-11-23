<?php
// Iniciar sesión para mensajes
session_start();

// Conexión a la base de datos
include 'includes/db.php';

// Verificar que el formulario fue enviado por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: galeria.php');
    exit();
}

// Obtener y limpiar datos del formulario
$mascota_id = isset($_POST['mascota_id']) ? intval($_POST['mascota_id']) : 0;
$mascota_nombre = isset($_POST['mascota_nombre']) ? trim($_POST['mascota_nombre']) : '';
$nombre_completo = isset($_POST['nombre_completo']) ? trim($_POST['nombre_completo']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
$descripcion_hogar = isset($_POST['descripcion_hogar']) ? trim($_POST['descripcion_hogar']) : '';
$motivo_adopcion = isset($_POST['motivo_adopcion']) ? trim($_POST['motivo_adopcion']) : '';
$experiencia = isset($_POST['experiencia']) ? trim($_POST['experiencia']) : '';

// Validaciones básicas en el servidor
$errores = [];

if ($mascota_id == 0) {
    $errores[] = "ID de mascota inválido";
}

if (empty($nombre_completo)) {
    $errores[] = "El nombre completo es obligatorio";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El correo electrónico es inválido";
}

if (empty($telefono)) {
    $errores[] = "El teléfono es obligatorio";
}

if (empty($direccion)) {
    $errores[] = "La dirección es obligatoria";
}

if (empty($descripcion_hogar)) {
    $errores[] = "La descripción del hogar es obligatoria";
}

if (empty($motivo_adopcion) || strlen($motivo_adopcion) < 500) {
    $errores[] = "El motivo de adopción debe tener al menos 500 caracteres";
}

// Si hay errores, redirigir de vuelta al formulario
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['form_data'] = $_POST; // Guardar datos para repoblar el formulario
    header("Location: formulario-adopcion.php?id=$mascota_id");
    exit();
}

// Verificar que la mascota existe y está disponible
$query = "SELECT id, nombre, estado FROM mascotas WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $mascota_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "La mascota seleccionada no existe";
    header('Location: galeria.php');
    exit();
}

$mascota = $result->fetch_assoc();

if ($mascota['estado'] != 'Disponible') {
    $_SESSION['error'] = "Esta mascota ya no está disponible para adopción";
    header("Location: perfil-mascota.php?id=$mascota_id");
    exit();
}

// Insertar la solicitud en la base de datos
$query_insert = "INSERT INTO solicitudes (
    mascota_id, 
    nombre_completo, 
    email, 
    telefono, 
    direccion, 
    descripcion_hogar, 
    motivo_adopcion, 
    experiencia, 
    estado, 
    fecha_solicitud
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Recibida', NOW())";

$stmt_insert = $conn->prepare($query_insert);
$stmt_insert->bind_param(
    "isssssss",
    $mascota_id,
    $nombre_completo,
    $email,
    $telefono,
    $direccion,
    $descripcion_hogar,
    $motivo_adopcion,
    $experiencia
);

if ($stmt_insert->execute()) {
    // Solicitud guardada exitosamente
    $solicitud_id = $stmt_insert->insert_id;
    
    // Guardar mensaje de éxito en sesión
    $_SESSION['success'] = "¡Tu solicitud ha sido enviada exitosamente! Número de solicitud: #$solicitud_id";
    $_SESSION['solicitud_id'] = $solicitud_id;
    $_SESSION['mascota_nombre'] = $mascota_nombre;
    
    // Redirigir a página de confirmación
    header("Location: confirmacion-adopcion.php");
    exit();
    
} else {
    // Error al guardar
    $_SESSION['error'] = "Hubo un error al procesar tu solicitud. Por favor, intenta nuevamente.";
    header("Location: formulario-adopcion.php?id=$mascota_id");
    exit();
}

$stmt_insert->close();
$stmt->close();
$conn->close();
?>