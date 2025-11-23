<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "click_paw";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
