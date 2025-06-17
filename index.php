<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio - GanApp</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="main-content text-center" style="padding: 40px;">
  <h2>Bienvenido a nuestro asistente virtual para la compra y venta de reses</h2>
  <p>GanApp facilita la compra y venta de reses con información detallada y una búsqueda inteligente.</p>
  <div class="text-center mt-3">
    <a href="publicar.php" class="btn btn-success">+ Publicar nueva res</a>
    <a href="carrito.php" class="btn btn-warning ms-3">🛒 Ver carrito</a>
  </div>
</div>
<div class="container mt-5">
  <h2 class="text-center mb-4">Reses y Lotes disponibles</h2>
  <div class="container text-center my-4">
    <h2 class="text-success">Búsqueda de Información Detallada</h2>
    <input type="text" id="busquedaReses" class="form-control w-50 mx-auto mt-3"
           placeholder="Buscar por edad, tratamientos, salud, alimentación, origen...">
  </div>
  <div class="row">
    <?php
    include 'db.php';
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    $reses = $conexion->query("SELECT r.*, u.nombre AS dueno FROM reses r JOIN usuarios u ON r.id_usuario = u.id WHERE r.vendido = 0 ORDER BY r.fecha_publicacion DESC");
    while ($res = $reses->fetch_assoc()):
    ?>
    <div class="col-md-4 mb-4 res-item" data-info="<?php echo strtolower($res['edad'] . ' ' . $res['vacunas'] . ' ' . $res['salud'] . ' ' . $res['peso'] . ' ' . $res['alimentacion'] . ' ' . $res['ubicacion']); ?>">
      <div class="card h-100">
        <a href="ver_res.php?id=<?= $res['id'] ?>" title="Ver más información">
          <img src="<?= $res['imagen'] ?>" class="card-img-top" alt="Res" style="height: 220px; object-fit: cover; cursor: pointer;">
        </a>
        <div class="card-body">
          <h5 class="card-title text-success">Res individual</h5>
          <p class="card-text"><strong>Edad:</strong> <?= $res['edad'] ?> años</p>
          <p class="card-text"><strong>Peso:</strong> <?= $res['peso'] ?></p>
          <p class="card-text"><strong>Ubicación:</strong> <?= $res['ubicacion'] ?></p>
          <p><strong>Precio:</strong> $ <?= $res['precio'] ?></p>
           <form method="POST" action="agregar_carrito.php">
  <input type="hidden" name="res_id" value="res-<?= $res['id'] ?>">
  <?php if (in_array('res-' . $res['id'], $_SESSION['carrito'])): ?>
    <button type="button" class="btn btn-secondary w-100 mt-2" disabled>Agregado al carrito</button>
  <?php else: ?>
    <button type="submit" class="btn btn-primary w-100 mt-2">Agregar al carrito</button>
  <?php endif; ?>
</form>

        </div>
      </div>
    </div>
    <?php endwhile; ?>
    <?php
    $lotes = $conexion->query("SELECT l.*, u.nombre AS dueno FROM lotes l JOIN usuarios u ON l.id_usuario = u.id WHERE l.vendido = 0 ORDER BY l.fecha_publicacion DESC");
    while ($lote = $lotes->fetch_assoc()):
    ?>
    <div class="col-md-4 mb-4 res-item" data-info="<?php echo strtolower($lote['edad_promedio'] . ' ' . $lote['salud_general'] . ' ' . $lote['peso_promedio'] . ' ' . $lote['alimentacion'] . ' ' . $lote['origen'] . ' ' . $lote['ubicacion']); ?>">
      <div class="card h-100">
        <a href="ver_lote.php?id=<?= $lote['id'] ?>" title="Ver más información">
          <img src="<?= $lote['imagen'] ?>" class="card-img-top" alt="Lote" style="height: 220px; object-fit: cover; cursor: pointer;">
        </a>
        <div class="card-body">
          <h5 class="card-title text-primary">Lote de <?= $lote['cantidad'] ?> reses</h5>
          <p class="card-text"><strong>Edad promedio:</strong> <?= $lote['edad_promedio'] ?> años</p>
          <p class="card-text"><strong>Peso promedio:</strong> <?= $lote['peso_promedio'] ?></p>
          <p class="card-text"><strong>Ubicación:</strong> <?= $lote['ubicacion'] ?></p>
          <p><strong>Precio:</strong> $ <?= $lote['precio'] ?></p>
          <form method="POST" action="agregar_carrito.php">
            <input type="hidden" name="res_id" value="lote-<?= $lote['id'] ?>">
          <?php if (in_array('lote-' . $lote['id'], $_SESSION['carrito'])): ?>
            <button type="button" class="btn btn-secondary w-100 mt-2" disabled>Agregado al carrito</button>
         <?php else: ?>
            <button type="submit" class="btn btn-primary w-100 mt-2">Agregar al carrito</button>
          <?php endif; ?>
          </form>

        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>
<script>
  document.getElementById('busquedaReses').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    const reses = document.querySelectorAll('.res-item');
    reses.forEach(function (res) {
      const info = res.getAttribute('data-info');
      res.style.display = info.includes(query) ? 'block' : 'none';
    });
  });
</script>
</body>
</html>
