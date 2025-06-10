<?php
include 'db.php';

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES ('$nombre', '$correo', '$contrasena')";
if ($conexion->query($sql)) {
    echo "Usuario registrado exitosamente.";
} else {
    echo "Error al registrar: " . $conexion->error;
}
?>
