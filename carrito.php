<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

$carrito = $_SESSION['carrito'] ?? [];
$tiene_productos = !empty($carrito);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito - GanApp</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <h2 class="mb-4 text-center">
        Mi Carrito (<?= $tiene_productos ? count($carrito) : 0 ?> ítem<?= ($tiene_productos && count($carrito) != 1) ? 's' : '' ?>)
    </h2>

    <?php if ($tiene_productos): ?>
    <form action="eliminar_seleccionados.php" method="POST">
        <div class="mb-3 d-flex justify-content-between">
            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleSeleccion()">Seleccionar/Deseleccionar Todos</button>
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar los elementos seleccionados?')">Eliminar Seleccionados</button>
        </div>

        <div class="row">
            <?php
            foreach ($carrito as $clave) {
                if (strpos($clave, 'res-') === 0) {
                    $id = intval(str_replace('res-', '', $clave));
                    $consulta = $conexion->query("SELECT * FROM reses WHERE id = $id");
                    $producto = $consulta->fetch_assoc();
                    $tipo = 'res';
                } elseif (strpos($clave, 'lote-') === 0) {
                    $id = intval(str_replace('lote-', '', $clave));
                    $consulta = $conexion->query("SELECT * FROM lotes WHERE id = $id");
                    $producto = $consulta->fetch_assoc();
                    $tipo = 'lote';
                } else {
                    continue;
                }

                if ($producto) {
                    echo "<div class='col-md-4'>";
                    echo "<div class='card mb-4 shadow-sm'>";
                    echo "<img src='{$producto['imagen']}' class='card-img-top' style='height: 200px; object-fit: cover;'>";
                    echo "<div class='card-body'>";
                    echo "<div class='form-check mb-2'>";
                    echo "<input type='checkbox' name='seleccionados[]' value='$clave' class='form-check-input'>";
                    echo "<label class='form-check-label'><strong>" . strtoupper($tipo) . "</strong></label>";
                    echo "</div>";

                    if ($tipo === 'res') {
                        echo "<p>Edad: {$producto['edad']}</p>";
                        echo "<p>Peso: {$producto['peso']}</p>";
                        echo "<p>Raza: {$producto['raza']}</p>";
                        echo "<p>Clasificación: {$producto['clasificacion']} ({$producto['tipo']})</p>";
                    } else {
                        echo "<p>Cantidad: {$producto['cantidad']}</p>";
                        echo "<p>Edad Promedio: {$producto['edad_promedio']}</p>";
                        echo "<p>Peso Promedio: {$producto['peso_promedio']}</p>";
                        echo "<p>Alimentación: {$producto['alimentacion']}</p>";
                    }

                    echo "<p>Ubicación: {$producto['ubicacion']}</p>";
                    echo "</div></div></div>";
                }
            }
            ?>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="generar_factura.php" class="btn btn-primary">Generar Factura</a>
    </div>

    <?php else: ?>
        <h4 class='text-center text-muted mt-5'>No hay productos en el carrito.</h4>
        <div class='text-center'><a href='index.php' class='btn btn-success mt-3'>Volver al catálogo</a></div>
    <?php endif; ?>
</div>

<script>
function toggleSeleccion() {
    const checkboxes = document.querySelectorAll('input[name="seleccionados[]"]');
    const allChecked = Array.from(checkboxes).every(ch => ch.checked);
    checkboxes.forEach(ch => ch.checked = !allChecked);
}
</script>

</body>
</html>
