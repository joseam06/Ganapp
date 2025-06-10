<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

// Obtener todas las reses
$sql = "SELECT r.*, u.nombre AS dueño FROM reses r
        JOIN usuarios u ON r.id_usuario = u.id
        ORDER BY fecha_publicacion DESC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo de Reses - GanApp</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <h2 class="text-center mb-4">Catálogo de Reses Publicadas</h2>
  <div class="row">
    <?php while($res = $resultado->fetch_assoc()): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="<?php echo $res['imagen']; ?>" class="card-img-top" alt="Imagen de la res">
          <div class="card-body">
            <h5 class="card-title">Edad: <?php echo $res['edad']; ?></h5>
            <p class="card-text"><strong>Vacunas:</strong> <?php echo $res['vacunas']; ?></p>
            <p class="card-text"><strong>Salud:</strong> <?php echo $res['salud']; ?></p>
            <p class="card-text"><strong>Peso:</strong> <?php echo $res['peso']; ?></p>
            <p class="card-text"><strong>Alimentación:</strong> <?php echo $res['alimentacion']; ?></p>
            <p class="card-text"><strong>Origen:</strong> <?php echo $res['origen']; ?></p>
            <p class="card-text"><strong>Ubicación actual:</strong> <?php echo $res['ubicacion']; ?></p>
            <p class="card-text text-muted"><small>Publicado por: <?php echo $res['dueño']; ?></small></p>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</body>
</html>
