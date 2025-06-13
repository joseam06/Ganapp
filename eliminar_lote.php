<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

if (!isset($_GET['id'])) {
    echo "ID de lote no especificado.";
    exit();
}

$id = intval($_GET['id']);
$usuario_id = $_SESSION['usuario_id'];

// Verificar propiedad y obtener imagen
$sql = "SELECT imagen FROM lotes WHERE id = ? AND id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    echo "Lote no encontrado o no autorizado.";
    exit();
}

$lote = $resultado->fetch_assoc();
$imagen = $lote['imagen'];

// Eliminar de la base de datos
$delete = $conexion->prepare("DELETE FROM lotes WHERE id = ? AND id_usuario = ?");
$delete->bind_param("ii", $id, $usuario_id);
$delete->execute();

// Eliminar imagen
if ($imagen && file_exists($imagen)) {
    unlink($imagen);
}

// ...
header("Location: mis_publicaciones.php?eliminado=lote");
exit();

?>
