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

// Guardar detalles
if (!empty($res_ids)) {
    foreach ($res_ids as $res_id) {
        $conexion->query("INSERT INTO factura_detalle (id_factura, tipo, id_objeto) VALUES ($id_factura, 'res', $res_id)");
        $conexion->query("UPDATE reses SET vendido = 1 WHERE id = $res_id");  // eliminamos del catálogo

    }
}

if (!empty($lote_ids)) {
    foreach ($lote_ids as $lote_id) {
        $conexion->query("INSERT INTO factura_detalle (id_factura, tipo, id_objeto) VALUES ($id_factura, 'lote', $lote_id)");
        $conexion->query("UPDATE lotes SET vendido = 1 WHERE id = $lote_id");  // eliminamos del catálogo
    }
}

// Recuperar detalles para mostrar factura
$detalle = $conexion->query("
    SELECT fd.*, r.imagen AS res_imagen, r.edad AS res_edad, r.salud AS res_salud, r.peso AS res_peso,
           l.imagen AS lote_imagen, l.cantidad AS lote_cantidad, l.edad_promedio, l.salud_general, l.peso_promedio
    FROM factura_detalle fd
    LEFT JOIN reses r ON fd.tipo = 'res' AND fd.id_objeto = r.id
    LEFT JOIN lotes l ON fd.tipo = 'lote' AND fd.id_objeto = l.id
    WHERE fd.id_factura = $id_factura
");

$comprador = $_SESSION['nombre'];
$correoComprador = isset($_SESSION['correo']) ? $_SESSION['correo'] : 'correo-no-disponible@ejemplo.com';
$fecha_hora = date('d/m/Y H:i:s');

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

    <div class="mb-3">
        <strong>Factura N°:</strong> <?php echo $factura_numero; ?><br>
        <strong>Fecha y hora:</strong> <?php echo $fecha_hora; ?><br>
    </div>

    <div class="mb-3">
        <strong>Comprador:</strong> <?php echo $comprador; ?><br>
        <strong>Correo:</strong> <?php echo $correoComprador; ?><br>
    </div>

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
        <h4 class="text-success">¡Gracias por su compra en GanApp!</h4>
        <a href="index.php" class="btn btn-primary mt-3">Volver al catálogo</a>
    </div>
</div>

<?php
// Limpiar carrito después de generar la factura
unset($_SESSION['carrito']);
?>

</body>
</html>
