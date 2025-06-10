<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID no válido";
    exit();
}

$sql = "SELECT l.*, u.nombre, u.correo FROM lotes l 
        JOIN usuarios u ON l.id_usuario = u.id 
        WHERE l.id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$lote = $stmt->get_result()->fetch_assoc();

if (!$lote) {
    echo "Lote no encontrado";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalles del Lote - GanApp</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <h2 class="text-center mb-4 text-primary">Detalle del Lote</h2>
  <div class="card mx-auto" style="max-width: 700px;">
    <img src="<?= $lote['imagen'] ?>" class="card-img-top" style="height: 300px; object-fit: cover;">
    <div class="card-body">
  <p><strong>Clasificación:</strong> <?= ucfirst($lote['clasificacion'] ?? 'No definido') ?></p>
  <p><strong>Tipo:</strong> <?= $lote['tipo'] ?? 'No definido' ?></p>
  <p><strong>Cantidad de reses:</strong> <?= $lote['cantidad'] ?></p>
  <p><strong>Edad promedio:</strong> <?= $lote['edad_promedio'] ?></p>
  <p><strong>Peso promedio:</strong> <?= $lote['peso_promedio'] ?></p>
  <p><strong>Salud general:</strong> <?= $lote['salud_general'] ?></p>
  <p><strong>Alimentación:</strong> <?= $lote['alimentacion'] ?></p>
  <p><strong>Origen:</strong> <?= $lote['origen'] ?></p>
  <p><strong>Ubicación:</strong> <?= $lote['ubicacion'] ?></p>
  <hr>
  <h5 class="text-primary">Información del Vendedor</h5>
  <p><strong>Nombre:</strong> <?= $lote['nombre'] ?></p>
  <p><strong>Correo:</strong> <?= $lote['correo'] ?></p>
</div>

  </div>
</div>
</body>
</html>
