<?php
session_start();
include 'db.php';

$id_usuario = $_SESSION['usuario_id'];

$facturas = $conexion->query("SELECT * FROM facturas WHERE id_usuario = $id_usuario ORDER BY fecha_hora DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Compras - GanApp</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Mis Compras</h2>

    <?php if ($facturas->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Número de factura</th>
                    <th>Fecha y hora</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($factura = $facturas->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $factura['numero_factura']; ?></td>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($factura['fecha_hora'])); ?></td>
                    <td>
                        <a href="ver_factura.php?id=<?php echo $factura['id']; ?>" class="btn btn-primary btn-sm">Ver detalles</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">No tienes compras registradas.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Volver al catálogo</a>
    </div>
</div>

</body>
</html>
