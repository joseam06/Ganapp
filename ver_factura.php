<?php
session_start();
include 'db.php';

$id_factura = intval($_GET['id']);
$id_usuario = $_SESSION['usuario_id'];

// Verificar que la factura le pertenece al usuario
$factura = $conexion->query("SELECT * FROM facturas WHERE id = $id_factura AND id_usuario = $id_usuario");
if ($factura->num_rows == 0) {
    echo "<h2 class='text-center mt-5'>Factura no encontrada.</h2>";
    exit();
}

$factura = $factura->fetch_assoc();

$detalle = $conexion->query("
    SELECT fd.*, r.imagen AS res_imagen, r.edad AS res_edad, r.salud AS res_salud, r.peso AS res_peso,
           l.imagen AS lote_imagen, l.cantidad AS lote_cantidad, l.edad_promedio, l.salud_general, l.peso_promedio
    FROM factura_detalle fd
    LEFT JOIN reses r ON fd.tipo = 'res' AND fd.id_objeto = r.id
    LEFT JOIN lotes l ON fd.tipo = 'lote' AND fd.id_objeto = l.id
    WHERE fd.id_factura = $id_factura
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Factura - GanApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Factura N° <?php echo $factura['numero_factura']; ?></h2>
    <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i:s', strtotime($factura['fecha_hora'])); ?></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Tipo</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $detalle->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if ($row['tipo'] == 'res'): ?>
                        <img src="<?php echo $row['res_imagen']; ?>" style="width:100px;">
                    <?php else: ?>
                        <img src="<?php echo $row['lote_imagen']; ?>" style="width:100px;">
                    <?php endif; ?>
                </td>
                <td><?php echo ($row['tipo'] == 'res') ? 'Res individual' : 'Lote'; ?></td>
                <td>
                    <?php if ($row['tipo'] == 'res'): ?>
                        Edad: <?php echo $row['res_edad']; ?> |
                        Salud: <?php echo $row['res_salud']; ?> |
                        Peso: <?php echo $row['res_peso']; ?>
                    <?php else: ?>
                        Cantidad: <?php echo $row['lote_cantidad']; ?> |
                        Edad promedio: <?php echo $row['edad_promedio']; ?> |
                        Salud general: <?php echo $row['salud_general']; ?> |
                        Peso promedio: <?php echo $row['peso_promedio']; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="mis_compras.php" class="btn btn-secondary">Volver a Mis Compras</a>
    </div>
</div>

</body>
</html>
