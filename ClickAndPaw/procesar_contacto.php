<?php
session_start();

// ================================
//  CONFIGURACIÓN DE LA BD
// ================================
$host = 'localhost';
$db_name = 'click_paw_db';
$username = 'root';
$password = '';
$port = 3306;

// Conexión
$conn = new mysqli($host, $username, $password, $db_name, $port);

if ($conn->connect_error) {
    die("Error de conexión a MySQL: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// ================================
//  PROCESAR FORMULARIO
// ================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre   = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $correo   = $conn->real_escape_string($_POST['correo']);
    $mensaje  = $conn->real_escape_string($_POST['mensaje']);

    // Insertar en la tabla 'preguntas'
    $sql = "INSERT INTO preguntas (nombre, apellido, correo, mensaje, fecha_envio)
            VALUES ('$nombre', '$apellido', '$correo', '$mensaje', NOW())";

    if ($conn->query($sql) === TRUE) {

        // Obtener ID generado
        $preguntas_id = $conn->insert_id;

        // Guardarlo en sesión
        $_SESSION['preguntas_id'] = $preguntas_id;

        // Redirigir a la página de éxito
        header("Location: contacto_exito.php");
        exit();
    } else {
        echo "Error al guardar la pregunta: " . $conn->error;
    }
}

$conn->close();
?>