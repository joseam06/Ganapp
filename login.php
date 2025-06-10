<?php
session_start();
include 'db.php';

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];


$sql = "SELECT * FROM usuarios WHERE correo='$correo'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    if (password_verify($contrasena, $usuario['contraseña'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        echo "Bienvenido " . $usuario['nombre'];
    } else {
        echo "Contraseña incorrecta";
    }
} else {
    echo "Usuario no encontrado";
}
?>
