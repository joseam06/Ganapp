<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id_usuario'])) {
    echo "Debes iniciar sesión.";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_res = $_GET['id'] ?? null;

if (!$id_res) {
    echo "ID no proporcionado.";
    exit;
}

$stmt = $conn->prepare("DELETE FROM reses WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id_res, $id_usuario]);

header("Location: mis_publicaciones.php");
exit;
?>
