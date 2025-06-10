<?php
session_start();
require 'db.php';
include 'navbar.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_lote = $_GET['id'] ?? null;

if (!$id_lote) {
    echo "ID no proporcionado.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM lotes WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id_lote, $id_usuario]);
$lote = $stmt->fetch();

if (!$lote) {
    echo "No tienes permiso para editar este lote.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Lote - GanApp</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="card-container">
        <h2>Editar Lote</h2>
        <form action="actualizar_lote.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $lote['id'] ?>">

            <label>Rango de Edad</label>
            <input type="text" name="edad_promedio" value="<?= $lote['edad_promedio'] ?>" required>

            <label>Peso Promedio</label>
            <input type="text" name="peso_promedio" value="<?= $lote['peso_promedio'] ?>" required>

            <label>Cantidad</label>
            <input type="number" name="cantidad" value="<?= $lote['cantidad'] ?>" required>

            <label>Alimentación</label>
            <textarea name="alimentacion" required><?= $lote['alimentacion'] ?></textarea>

            <label>Vacunas</label>
            <textarea name="vacuna" required><?= $lote['salud_general'] ?></textarea>

            <label>Zona / Ubicación</label>
            <input type="text" name="ubicacion" value="<?= $lote['ubicacion'] ?>" required>

            <label>Origen</label>
            <input type="text" name="origen" value="<?= $lote['origen'] ?>" required>

            <label>Imagen (opcional)</label>
            <input type="file" name="imagen" accept="image/*">

            <button type="submit">Actualizar Lote</button>
        </form>
    </div>
</body>
</html>
