<?php
session_start();
require 'db.php';
include 'navbar.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Publicaciones - GanApp</title>
    <link rel="stylesheet" href="styles.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4 text-center">Mis Reses Publicadas</h2>
    <div class="row">
        <?php
        $reses = $conn->prepare("SELECT * FROM reses WHERE id_usuario = ?");
        $reses->execute([$id_usuario]);
        foreach ($reses as $res) {
            echo "<div class='col-md-4'>";
            echo "<div class='card mb-4 shadow-sm'>";
            echo "<img src='{$res['imagen']}' class='card-img-top' style='height: 200px; object-fit: cover;'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>Tipo: {$res['tipo']} ({$res['clasificacion']})</h5>";
            echo "<p class='card-text'>Edad: {$res['edad']}</p>";
            echo "<p class='card-text'>Peso: {$res['peso']}</p>";
            echo "<p class='card-text'>Raza: {$res['raza']}</p>";
            echo "<p class='card-text'>Ubicación: {$res['ubicacion']}</p>";
            echo "<div class='d-flex justify-content-between'>";
            echo "<a href='editar_res.php?id={$res['id']}' class='btn btn-sm btn-outline-primary'>Editar</a>";
            echo "<a href='eliminar_res.php?id={$res['id']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"¿Estás seguro de eliminar esta res?\")'>Eliminar</a>";
            echo "</div>";
            echo "</div></div></div>";
        }
        ?>
    </div>

    <hr class="my-5">

    <h2 class="mb-4 text-center">Mis Lotes Publicados</h2>
    <div class="row">
        <?php
        $lotes = $conn->prepare("SELECT * FROM lotes WHERE id_usuario = ?");
        $lotes->execute([$id_usuario]);
        foreach ($lotes as $lote) {
            echo "<div class='col-md-4'>";
            echo "<div class='card mb-4 shadow-sm'>";
            echo "<img src='{$lote['imagen']}' class='card-img-top' style='height: 200px; object-fit: cover;'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>Cantidad: {$lote['cantidad']}</h5>";
            echo "<p class='card-text'>Peso Promedio: {$lote['peso_promedio']}</p>";
            echo "<p class='card-text'>Edad Promedio: {$lote['edad_promedio']}</p>";
            echo "<p class='card-text'>Ubicación: {$lote['ubicacion']}</p>";
            echo "<div class='d-flex justify-content-between'>";
            echo "<a href='editar_lote.php?id={$lote['id']}' class='btn btn-sm btn-outline-primary'>Editar</a>";
            echo "<a href='eliminar_lote.php?id={$lote['id']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"¿Eliminar este lote?\")'>Eliminar</a>";
            echo "</div>";
            echo "</div></div></div>";
        }
        ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
