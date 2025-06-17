<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

if (!isset($_GET['id'])) {
    echo "ID de lote no especificado.";
    exit();
}

$id = intval($_GET['id']);
$usuario_id = $_SESSION['usuario_id'];

// Obtener los datos del lote solo si pertenece al usuario actual
$sql = "SELECT * FROM lotes WHERE id = ? AND id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    echo "Lote no encontrado o no autorizado.";
    exit();
}

$lote = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Lote - GanApp</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center">Editar Lote</h2>

    <form action="actualizar_lote.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $lote['id']; ?>">

        <div class="mb-3">
            <label for="edad_promedio" class="form-label">Rango de Edad</label>
            <input type="text" name="edad_promedio" class="form-control" required value="<?php echo $lote['edad_promedio']; ?>">
        </div>

        <div class="mb-3">
            <label for="peso_promedio" class="form-label">Peso Promedio</label>
            <input type="text" name="peso_promedio" class="form-control" required value="<?php echo $lote['peso_promedio']; ?>">
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad de reses</label>
            <input type="number" name="cantidad" class="form-control" required value="<?php echo $lote['cantidad']; ?>">
        </div>

        <div class="mb-3">
            <label for="salud_general" class="form-label">Salud General</label>
            <textarea name="salud_general" class="form-control" required><?php echo $lote['salud_general']; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="alimentacion" class="form-label">Alimentación</label>
            <textarea name="alimentacion" class="form-control" required><?php echo $lote['alimentacion']; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="origen" class="form-label">Origen</label>
            <textarea name="origen" class="form-control" required><?php echo $lote['origen']; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="ubicacion" class="form-label">Zona / Ubicación</label>
            <input type="text" name="ubicacion" class="form-control" required value="<?php echo $lote['ubicacion']; ?>">
        </div>

        <div class="mb-3">
        <label for="precio" class="form-label">Precio del Lote ($)</label>
        <input type="number" name="precio" class="form-control" required value="<?php echo $lote['precio']; ?>">
        </div>


        <div class="mb-3">
            <label for="imagen" class="form-label">Cambiar imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control" accept="image/*">
            <div class="mt-2">
                <small>Imagen actual:</small><br>
                <img src="<?php echo $lote['imagen']; ?>" alt="Imagen actual" class="img-fluid" style="max-height: 200px;">
            </div>
        </div>

        

        <button type="submit" class="btn btn-primary w-100">Actualizar Lote</button>
        <a href="mis_publicaciones.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
    </form>
</div>

</body>
</html>
