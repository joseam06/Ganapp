<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID inválido";
    exit();
}

$sql = "SELECT * FROM reses WHERE id = ? AND id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res) {
    echo "Res no encontrada o no tienes permiso.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Res - GanApp</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5" style="max-width: 600px;">
  <h2 class="mb-4 text-center">Editar Res</h2>

  <form action="actualizar_res.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $res['id'] ?>">

    <?php
    function selected($val, $opt) {
        return $val === $opt ? 'selected' : '';
    }
    ?>

    <div class="mb-3">
      <label>Clasificación</label>
      <select name="clasificacion" class="form-select" required>
        <option value="primera" <?= selected($res['clasificacion'], 'primera') ?>>Primera</option>
        <option value="segunda" <?= selected($res['clasificacion'], 'segunda') ?>>Segunda</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Tipo</label>
      <select name="tipo" class="form-select" required>
        <?php foreach (['ML','MC','TO','BM','HL','HV','VP','VE'] as $tipo): ?>
          <option value="<?= $tipo ?>" <?= selected($res['tipo'], $tipo) ?>><?= $tipo ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Edad</label>
      <input type="text" name="edad" class="form-control" value="<?= $res['edad'] ?>" required>
    </div>

    <div class="mb-3">
      <label>Peso</label>
      <input type="text" name="peso" class="form-control" value="<?= $res['peso'] ?>" required>
    </div>

    <div class="mb-3">
      <label>Raza</label>
      <input type="text" name="raza" class="form-control" value="<?= $res['raza'] ?>" required>
    </div>

    <div class="mb-3">
      <label>Origen</label>
      <select name="origen_tipo" class="form-select" required>
        <option value="comercial" <?= selected($res['origen_tipo'], 'comercial') ?>>Línea Comercial</option>
        <option value="genetico" <?= selected($res['origen_tipo'], 'genetico') ?>>Antecedente Genético</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Detalle de Origen</label>
      <textarea name="origen" class="form-control"><?= $res['detalles_origen'] ?></textarea>
    </div>

    <div class="mb-3">
      <label>Alimentación</label>
      <textarea name="alimentacion" class="form-control"><?= $res['alimentacion'] ?></textarea>
    </div>

    <div class="mb-3">
      <label>Ubicación</label>
      <input type="text" name="ubicacion" class="form-control" value="<?= $res['ubicacion'] ?>" required>
    </div>

    <div class="mb-3">
      <label>Vacunas</label>
      <textarea name="vacunas" class="form-control"><?= $res['vacunas'] ?></textarea>
    </div>

    <div class="mb-3">
      <label>Estado de Salud</label>
      <input type="text" name="salud" class="form-control" value="<?= $res['salud'] ?>" required>
    </div>

    <div class="mb-3">
    <label for="precio_final" class="form-label">Precio de la Res ($)</label>
    <input type="number" name="precio_final" class="form-control" required value="<?= $res['precio'] ?>">
</div>


    <div class="mb-3">
      <label>Imagen (opcional)</label>
      <input type="file" name="imagen" class="form-control" accept="image/*">
      <?php if (!empty($res['imagen'])): ?>
  <div class="mt-2">
    <small>Imagen actual:</small><br>
    <img src="<?= $res['imagen'] ?>" alt="Imagen actual" class="img-fluid" style="max-height: 200px;">
  </div>
<?php endif; ?>
      <small>Si no seleccionas imagen, se mantendrá la actual.</small>
    </div>

    <button type="submit" class="btn btn-primary w-100">Actualizar</button>
    <a href="mis_publicaciones.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>

  </form>
</div>
</body>
</html>
