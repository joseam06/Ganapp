<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id_usuario'])) {
    echo "Debes iniciar sesión.";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_lote = $_GET['id'] ?? null;

if (!$id_lote) {
    echo "ID no proporcionado.";
    exit;
}

$stmt = $conn->prepare("DELETE FROM lotes WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id_lote, $id_usuario]);

header("Location: mis_publicaciones.php");
exit;
?>
