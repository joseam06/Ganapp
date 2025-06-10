<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

$id = $_POST['id'] ?? null;
if (!$id) {
    echo "ID inválido.";
    exit();
}

$sql = "SELECT imagen FROM reses WHERE id = ? AND id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res) {
    echo "No autorizado o no encontrado.";
    exit();
}

// Procesar nueva imagen si se cargó
$rutaImagen = $res['imagen'];
if (!empty($_FILES["imagen"]["name"])) {
    $directorio = "img/reses/";
    $nombreImagen = uniqid() . "_" . basename($_FILES["imagen"]["name"]);
    $rutaNueva = $directorio . $nombreImagen;

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaNueva)) {
        $rutaImagen = $rutaNueva;
    }
}

// Actualizar la res
$sql = "UPDATE reses SET
    clasificacion = ?, tipo = ?, edad = ?, peso = ?, raza = ?,
    origen_tipo = ?, origen = ?, alimentacion = ?, ubicacion = ?,
    vacunas = ?, salud = ?, imagen = ?
    WHERE id = ? AND id_usuario = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "sssssssssssiii",
    $_POST['clasificacion'],
    $_POST['tipo'],
    $_POST['edad'],
    $_POST['peso'],
    $_POST['raza'],
    $_POST['origen_tipo'],
    $_POST['origen'],
    $_POST['alimentacion'],
    $_POST['ubicacion'],
    $_POST['vacunas'],
    $_POST['salud'],
    $rutaImagen,
    $id,
    $_SESSION['usuario_id']
);

if ($stmt->execute()) {
    header("Location: index.php?editado=ok");
    exit();
} else {
    echo "Error al actualizar: " . $stmt->error;
}
?>
