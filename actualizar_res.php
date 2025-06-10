<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id_usuario'])) {
    echo "Debes iniciar sesión.";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Recibir datos del formulario
$id = $_POST['id'];
$clasificacion = $_POST['clasificacion'];
$tipo = $_POST['tipo'];
$edad = $_POST['edad'];
$peso = $_POST['peso'];
$raza = $_POST['raza'];
$origen_tipo = $_POST['origen_tipo'];
$origen = $_POST['origen'] ?? '';
$alimentacion = $_POST['alimentacion'];
$ubicacion = $_POST['ubicacion'];
$vacunas = $_POST['vacunas'];

// Validar que la res sea del usuario
$stmt = $conn->prepare("SELECT * FROM reses WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id, $id_usuario]);
$res = $stmt->fetch();

if (!$res) {
    echo "No tienes permiso para actualizar esta publicación.";
    exit;
}

// Manejar la imagen si se sube una nueva
$nueva_imagen = $_FILES['imagen']['name'];
if ($nueva_imagen) {
    $imagen_tmp = $_FILES['imagen']['tmp_name'];
    $carpeta = 'img/reses/';
    $nombre_final = uniqid() . '_' . $nueva_imagen;
    move_uploaded_file($imagen_tmp, $carpeta . $nombre_final);
    $imagen_path = $carpeta . $nombre_final;

    // Actualizar con imagen
    $sql = "UPDATE reses SET clasificacion=?, tipo=?, edad=?, peso=?, raza=?, origen_tipo=?, origen=?, alimentacion=?, ubicacion=?, vacunas=?, imagen=? WHERE id=? AND id_usuario=?";
    $params = [$clasificacion, $tipo, $edad, $peso, $raza, $origen_tipo, $origen, $alimentacion, $ubicacion, $vacunas, $imagen_path, $id, $id_usuario];
} else {
    // Actualizar sin cambiar imagen
    $sql = "UPDATE reses SET clasificacion=?, tipo=?, edad=?, peso=?, raza=?, origen_tipo=?, origen=?, alimentacion=?, ubicacion=?, vacunas=? WHERE id=? AND id_usuario=?";
    $params = [$clasificacion, $tipo, $edad, $peso, $raza, $origen_tipo, $origen, $alimentacion, $ubicacion, $vacunas, $id, $id_usuario];
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);

echo "Publicación actualizada correctamente. <a href='mis_publicaciones.php'>Volver a mis publicaciones</a>";
?>
