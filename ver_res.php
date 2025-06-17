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

$sql = "SELECT r.*, u.nombre, u.correo FROM reses r 
        JOIN usuarios u ON r.id_usuario = u.id 
        WHERE r.id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res) {
    echo "Res no encontrada";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalles de Res - GanApp</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <h2 class="text-center mb-4 text-success">Detalle de Res Individual</h2>
  <div class="card mx-auto" style="max-width: 700px;">
    <img src="<?= $res['imagen'] ?>" class="card-img-top" style="height: 300px; object-fit: cover;">
    <div class="card-body">
      <p><strong>Clasificación:</strong> <?= ucfirst($res['clasificacion']) ?></p>
      <p><strong>Tipo:</strong> <?= $res['tipo'] ?></p>
      <p><strong>Origen:</strong> <?= $res['origen_tipo'] ?></p>
      <p><strong>Edad:</strong> <?= $res['edad'] ?></p>
      <p><strong>Peso:</strong> <?= $res['peso'] ?></p>
      <p><strong>Raza:</strong> <?= $res['raza'] ?></p>
      <p><strong>Alimentación:</strong> <?= $res['alimentacion'] ?></p>
      <p><strong>Vacunas:</strong> <?= $res['vacunas'] ?></p>
      <p><strong>Estado de Salud:</strong> <?= $res['salud'] ?></p>
      <p><strong>Ubicación:</strong> <?= $res['ubicacion'] ?></p>
      <p><strong>Precio:</strong> <?= $res['precio'] ?></p>
      <hr>
      <h5 class="text-primary">Información del Vendedor</h5>
      <p><strong>Nombre:</strong> <?= $res['nombre'] ?></p>
      <p><strong>Correo:</strong> <?= $res['correo'] ?></p>

      <hr>
<form method="POST" action="agregar_carrito.php">
  <input type="hidden" name="res_id" value="res-<?= $res['id'] ?>">
  <?php if (in_array('res-' . $res['id'], $_SESSION['carrito'] ?? [])): ?>
    <button type="button" class="btn btn-secondary w-100" disabled>Ya en el carrito</button>
  <?php else: ?>
    <button type="submit" class="btn btn-primary w-100">Agregar al carrito</button>
  <?php endif; ?>
</form>

    </div>
  </div>
</div>
</body>
</html>
