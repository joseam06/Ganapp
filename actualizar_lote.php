<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id_usuario'])) {
    echo "Debes iniciar sesión.";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id = $_POST['id'];
$edad_promedio = $_POST['edad_promedio'];
$peso_promedio = $_POST['peso_promedio'];
$cantidad = $_POST['cantidad'];
$alimentacion = $_POST['alimentacion'];
$vacuna = $_POST['vacuna'];
$ubicacion = $_POST['ubicacion'];
$origen = $_POST['origen'];

$stmt = $conn->prepare("SELECT * FROM lotes WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id, $id_usuario]);
$lote = $stmt->fetch();

if (!$lote) {
    echo "No tienes permiso para actualizar este lote.";
    exit;
}

$nueva_imagen = $_FILES['imagen']['name'];
if ($nueva_imagen) {
    $imagen_tmp = $_FILES['imagen']['tmp_name'];
    $carpeta = 'img/reses/';
    $nombre_final = uniqid() . '_' . $nueva_imagen;
    move_uploaded_file($imagen_tmp, $carpeta . $nombre_final);
    $imagen_path = $carpeta . $nombre_final;

    $sql = "UPDATE lotes SET edad_promedio=?, peso_promedio=?, cantidad=?, alimentacion=?, salud_general=?, ubicacion=?, origen=?, imagen=? WHERE id=? AND id_usuario=?";
    $params = [$edad_promedio, $peso_promedio, $cantidad, $alimentacion, $vacuna, $ubicacion, $origen, $imagen_path, $id, $id_usuario];
} else {
    $sql = "UPDATE lotes SET edad_promedio=?, peso_promedio=?, cantidad=?, alimentacion=?, salud_general=?, ubicacion=?, origen=? WHERE id=? AND id_usuario=?";
    $params = [$edad_promedio, $peso_promedio, $cantidad, $alimentacion, $vacuna, $ubicacion, $origen, $id, $id_usuario];
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);

echo "Lote actualizado correctamente. <a href='mis_publicaciones.php'>Volver</a>";
?>
