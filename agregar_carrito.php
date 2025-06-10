<?php
session_start();

if (!isset($_POST['res_id'])) {
    header('Location: index.php');
    exit();
}

$res_id = $_POST['res_id'];  // Ej: res-3 o lote-2

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (!in_array($res_id, $_SESSION['carrito'])) {
    $_SESSION['carrito'][] = $res_id;
}

header('Location: index.php');
exit();
?>
