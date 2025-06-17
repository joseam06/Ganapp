<?php
session_start();
include 'db.php';

$id_factura = $_GET['id'] ?? null;
if (!$id_factura) {
    echo "ID de factura no especificado.";
    exit();
}

$factura_query = $conexion->query("SELECT f.*, u.nombre, u.correo FROM facturas f JOIN usuarios u ON f.id_usuario = u.id WHERE f.id = $id_factura");
$factura = $factura_query->fetch_assoc();

if (!$factura) {
    echo "Factura no encontrada.";
    exit();
}

$detalle = $conexion->query("
    SELECT fd.*, 
           r.raza, r.edad AS res_edad, r.peso AS res_peso, r.salud AS res_salud, r.clasificacion, r.tipo AS tipo_res, 
           r.precio AS res_precio, r.imagen AS res_imagen, ru.nombre AS vendedor_res_nombre, ru.correo AS vendedor_res_correo,

           l.cantidad, l.edad_promedio, l.peso_promedio, l.salud_general, l.precio AS lote_precio, l.imagen AS lote_imagen,
           lu.nombre AS vendedor_lote_nombre, lu.correo AS vendedor_lote_correo

    FROM factura_detalle fd
    LEFT JOIN reses r ON fd.tipo = 'res' AND fd.id_objeto = r.id
    LEFT JOIN usuarios ru ON r.id_usuario = ru.id
    LEFT JOIN lotes l ON fd.tipo = 'lote' AND fd.id_objeto = l.id
    LEFT JOIN usuarios lu ON l.id_usuario = lu.id
    WHERE fd.id_factura = $id_factura
");

date_default_timezone_set('America/Bogota');
$fecha_hora = date('d/m/Y H:i:s', strtotime($factura['fecha_hora']));

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ver Factura - GanApp</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="text-center mb-4">Factura - GanApp</h2>

  <div class="mb-4">
    <strong>Factura Nº:</strong> <?= $factura['numero_factura'] ?><br>
    <strong>Fecha y hora:</strong> <?= $fecha_hora ?><br><br>

    <strong>Comprador:</strong> <?= $factura['nombre'] ?><br>
    <strong>Correo:</strong> <?= $factura['correo'] ?><br>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Imagen</th>
        <th>Tipo</th>
        <th>Detalle</th>
        <th>Precio</th>
        <th>Vendedor</th>

      </tr>
    </thead>
    <tbody>
      <?php while ($row = $detalle->fetch_assoc()): ?>
        <tr>
          <td><img src="<?= $row['tipo'] === 'res' ? $row['res_imagen'] : $row['lote_imagen']; ?>" width="100"></td>
          <td><?= $row['tipo'] === 'res' ? 'Res individual' : 'Lote'; ?></td>
          <td>
            <?php if ($row['tipo'] === 'res'): ?>
              <strong>Clasificación:</strong> <?= $row['clasificacion'] ?><br>
              <strong>Tipo:</strong> <?= $row['tipo'] ?><br>
              <strong>Raza:</strong> <?= $row['raza'] ?><br>
              <strong>Edad:</strong> <?= $row['res_edad'] ?> años<br>
              <strong>Peso:</strong> <?= $row['res_peso'] ?> <br>
              <strong>Salud:</strong> <?= $row['res_salud'] ?>
              
               
            <?php else: ?>
              <strong>Cantidad:</strong> <?= $row['cantidad'] ?><br>
              <strong>Edad promedio:</strong> <?= $row['edad_promedio'] ?><br>
              <strong>Peso promedio:</strong> <?= $row['peso_promedio'] ?><br>
              <strong>Salud:</strong> <?= $row['salud_general'] ?>
            <?php endif; ?>
          </td>
          <td>
            $<?= number_format($row['tipo'] === 'res' ? $row['res_precio'] : $row['lote_precio'], 0, ',', '.') ?>
          </td>

          <td>
  <?php if ($row['tipo'] === 'res'): ?>
    <?= $row['vendedor_res_nombre'] ?><br>
    <small><?= $row['vendedor_res_correo'] ?></small>
  <?php else: ?>
    <?= $row['vendedor_lote_nombre'] ?><br>
    <small><?= $row['vendedor_lote_correo'] ?></small>
  <?php endif; ?>
</td>

        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="text-end">
    <h4>Total pagado: <span class="text-success">$<?= number_format($factura['total'], 0, ',', '.') ?></span></h4>
  </div>

  <div class="text-center mt-4">
    <a href="index.php" class="btn btn-primary">Volver al catálogo</a>
  </div>
</div>
</body>
</html>
