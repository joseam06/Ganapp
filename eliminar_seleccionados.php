<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}

$seleccionados = $_POST['seleccionados'] ?? [];

$_SESSION['carrito'] = array_diff($_SESSION['carrito'], $seleccionados);

header("Location: carrito.php");
exit;
?>
