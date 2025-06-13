<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';
$usuario_id = $_SESSION['usuario_id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Publicaciones - GanApp</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-body .btn {
      width: 48%;
    }
    .btn i {
      margin-right: 5px;
    }
  </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">

    <?php if (isset($_GET['eliminado'])): ?>
  <div class="alert alert-success text-center">
    <?php if ($_GET['eliminado'] === 'res'): ?>
      Res eliminada correctamente.
    <?php elseif ($_GET['eliminado'] === 'lote'): ?>
      Lote eliminado correctamente.
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if (isset($_GET['actualizado'])): ?>
  <div class="alert alert-info text-center">
    <?php if ($_GET['actualizado'] === 'res'): ?>
      Res actualizada exitosamente.
    <?php elseif ($_GET['actualizado'] === 'lote'): ?>
      Lote actualizado exitosamente.
    <?php endif; ?>
  </div>
<?php endif; ?>

  <h2 class="text-center mb-4">Mis Publicaciones</h2>

  <div class="row">
    <?php
    // Mostrar reses del usuario
    $reses = $conexion->prepare("SELECT * FROM reses WHERE id_usuario = ? ORDER BY fecha_publicacion DESC");
    $reses->bind_param("i", $usuario_id);
    $reses->execute();
    $result_reses = $reses->get_result();
    while ($res = $result_reses->fetch_assoc()):
    ?>
    <div class="col-md-4 mb-4">
      <div class="card h-100 shadow">
        <a href="ver_res.php?id=<?= $res['id'] ?>" title="Ver más información">
          <img src="<?= $res['imagen'] ?>" class="card-img-top" alt="Res" style="height: 220px; object-fit: cover; cursor:pointer;">
        </a>
        <div class="card-body">
          <h5 class="card-title text-success">Res individual</h5>
          <p class="card-text"><strong>Edad:</strong> <?= $res['edad'] ?></p>
          <p class="card-text"><strong>Peso:</strong> <?= $res['peso'] ?></p>
          <p class="card-text"><strong>Ubicación:</strong> <?= $res['ubicacion'] ?></p>
          <div class="d-flex justify-content-between mt-3">
            <a href="editar_res.php?id=<?= $res['id'] ?>" class="btn btn-outline-warning btn-sm">
              ✏️ Editar
            </a>
            <a href="eliminar_res.php?id=<?= $res['id'] ?>" class="btn btn-outline-danger btn-sm"
               onclick="return confirm('¿Estás seguro de eliminar esta res?')">
              🗑️ Eliminar
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>

    <?php
    // Mostrar lotes del usuario
    $lotes = $conexion->prepare("SELECT * FROM lotes WHERE id_usuario = ? ORDER BY fecha_publicacion DESC");
    $lotes->bind_param("i", $usuario_id);
    $lotes->execute();
    $result_lotes = $lotes->get_result();
    while ($lote = $result_lotes->fetch_assoc()):
    ?>
    <div class="col-md-4 mb-4">
      <div class="card h-100 shadow">
        <a href="ver_lote.php?id=<?= $lote['id'] ?>" title="Ver más información">
          <img src="<?= $lote['imagen'] ?>" class="card-img-top" alt="Lote" style="height: 220px; object-fit: cover; cursor:pointer;">
        </a>
        <div class="card-body">
          <h5 class="card-title text-primary">Lote de <?= $lote['cantidad'] ?> reses</h5>
          <p class="card-text"><strong>Edad promedio:</strong> <?= $lote['edad_promedio'] ?></p>
          <p class="card-text"><strong>Peso promedio:</strong> <?= $lote['peso_promedio'] ?></p>
          <p class="card-text"><strong>Ubicación:</strong> <?= $lote['ubicacion'] ?></p>
          <div class="d-flex justify-content-between mt-3">
            <a href="editar_lote.php?id=<?= $lote['id'] ?>" class="btn btn-outline-warning btn-sm">
              ✏️ Editar
            </a>
            <a href="eliminar_lote.php?id=<?= $lote['id'] ?>" class="btn btn-outline-danger btn-sm"
               onclick="return confirm('¿Eliminar este lote?')">
              🗑️ Eliminar
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
