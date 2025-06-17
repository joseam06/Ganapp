<?php
session_start();
include 'db.php';

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<h2 class='text-center mt-5'>No hay productos en el carrito.</h2>";
    echo "<div class='text-center'><a href='index.php' class='btn btn-success mt-3'>Volver al catálogo</a></div>";
    exit();
}

$carrito = $_SESSION['carrito'];
$res_ids = [];
$lote_ids = [];

foreach ($carrito as $item) {
    if (strpos($item, 'res-') === 0) {
        $res_ids[] = intval(str_replace('res-', '', $item));
    } elseif (strpos($item, 'lote-') === 0) {
        $lote_ids[] = intval(str_replace('lote-', '', $item));
    }
}

// Crear factura principal
$factura_numero = uniqid('FAC-');
$id_usuario = $_SESSION['usuario_id'];
$conexion->query("INSERT INTO facturas (id_usuario, numero_factura) VALUES ($id_usuario, '$factura_numero')");
$id_factura = $conexion->insert_id;

// Guardar detalles y marcar como vendidos
if (!empty($res_ids)) {
    foreach ($res_ids as $res_id) {
        $conexion->query("INSERT INTO factura_detalle (id_factura, tipo, id_objeto) VALUES ($id_factura, 'res', $res_id)");
        $conexion->query("UPDATE reses SET vendido = 1 WHERE id = $res_id");
    }
}

if (!empty($lote_ids)) {
    foreach ($lote_ids as $lote_id) {
        $conexion->query("INSERT INTO factura_detalle (id_factura, tipo, id_objeto) VALUES ($id_factura, 'lote', $lote_id)");
        $conexion->query("UPDATE lotes SET vendido = 1 WHERE id = $lote_id");
    }
}

// Obtener detalles
$detalle = $conexion->query("
    SELECT fd.*, 
           r.imagen AS res_imagen, r.edad AS res_edad, r.salud AS res_salud, r.peso AS res_peso, 
           r.precio AS res_precio, r.raza AS raza, r.clasificacion AS clasificacion, r.tipo AS tipo_res, 
           ru.nombre AS vendedor_res_nombre, ru.correo AS vendedor_res_correo,

           l.imagen AS lote_imagen, l.cantidad AS lote_cantidad, l.edad_promedio, l.salud_general, 
           l.peso_promedio, l.precio AS lote_precio, 
           lu.nombre AS vendedor_lote_nombre, lu.correo AS vendedor_lote_correo

    FROM factura_detalle fd
    LEFT JOIN reses r ON fd.tipo = 'res' AND fd.id_objeto = r.id
    LEFT JOIN usuarios ru ON r.id_usuario = ru.id

    LEFT JOIN lotes l ON fd.tipo = 'lote' AND fd.id_objeto = l.id
    LEFT JOIN usuarios lu ON l.id_usuario = lu.id

    WHERE fd.id_factura = $id_factura
");


$usuario_id = $_SESSION['usuario_id'];
$usuario_query = $conexion->query("SELECT nombre, correo FROM usuarios WHERE id = $usuario_id");
$usuario = $usuario_query->fetch_assoc();
$comprador = $usuario['nombre'];
$correoComprador = $usuario['correo'];
date_default_timezone_set('America/Bogota');
$fecha_hora = date('d/m/Y H:i:s');
$total = 0;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura - GanApp</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Factura Digital - GanApp</h2>

    <div class="mb-4">
        <strong>Factura N°:</strong> <?= $factura_numero ?><br>
        <strong>Fecha y hora:</strong> <?= $fecha_hora ?><br><br>

        <strong>Comprador:</strong> <?= $comprador ?><br>
        <strong>Correo:</strong> <?= $correoComprador ?><br>
    </div>


<<table class="table table-bordered">
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
        <td>
          <img src="<?= ($row['tipo'] === 'res') ? $row['res_imagen'] : $row['lote_imagen']; ?>" style="width:100px;">
        </td>
        <td><?= ($row['tipo'] === 'res') ? 'Res individual' : 'Lote'; ?></td>
        <td>
          <?php if ($row['tipo'] === 'res'): ?>
            <strong>Raza:</strong> <?= $row['raza'] ?? '' ?><br>
            <strong>Edad:</strong> <?= $row['res_edad'] ?> años<br>
            <strong>Peso:</strong> <?= $row['res_peso'] ?> kg<br>
            <strong>Salud:</strong> <?= $row['res_salud'] ?><br>
            <strong>Clasificación:</strong> <?= $row['clasificacion'] ?><br>
            <strong>Tipo:</strong> <?= $row['tipo_res'] ?><br>
          <?php else: ?>
            <strong>Cantidad:</strong> <?= $row['lote_cantidad'] ?><br>
            <strong>Edad promedio:</strong> <?= $row['edad_promedio'] ?><br>
            <strong>Peso promedio:</strong> <?= $row['peso_promedio'] ?><br>
            <strong>Salud:</strong> <?= $row['salud_general'] ?>
          <?php endif; ?>
        </td>
        <td>
          <?php
          $precio = ($row['tipo'] === 'res') ? $row['res_precio'] : $row['lote_precio'];
          echo "$" . number_format($precio, 0, ',', '.');
          $total += $precio;
          ?>
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
  <h4>Total a pagar: <span class="text-success">$<?= number_format($total, 0, ',', '.') ?></span></h4>
</div>

<?php

$conexion->query("UPDATE facturas SET total = $total WHERE id = $id_factura");
?>

    <div class="text-center mt-4">
        <h5 class="text-success">¡Gracias por su compra en GanApp!</h5>
        <a href="index.php" class="btn btn-primary mt-3">Volver al catálogo</a>
    </div>
</div>

<?php unset($_SESSION['carrito']); ?>
</body>
</html>
