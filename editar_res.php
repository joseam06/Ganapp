<?php
session_start();
require 'db.php';
include 'navbar.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_res = $_GET['id'] ?? null;

if (!$id_res) {
    echo "ID no proporcionado.";
    exit;
}

// Verificar que la res le pertenezca al usuario
$stmt = $conn->prepare("SELECT * FROM reses WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id_res, $id_usuario]);
$res = $stmt->fetch();

if (!$res) {
    echo "No tienes permiso para editar esta publicación.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Res - GanApp</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="card-container">
        <h2>Editar publicación de Res</h2>
        <form action="actualizar_res.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $res['id'] ?>">

            <label>Clasificación</label>
            <select name="clasificacion" onchange="cargarTipos()" id="clasificacion" required>
                <option value="">Selecciona</option>
                <option value="primera" <?= $res['clasificacion'] === 'primera' ? 'selected' : '' ?>>Primera</option>
                <option value="segunda" <?= $res['clasificacion'] === 'segunda' ? 'selected' : '' ?>>Segunda</option>
            </select>

            <label>Tipo</label>
            <select name="tipo" id="tipo" required>
                <option value="<?= $res['tipo'] ?>"><?= $res['tipo'] ?></option>
            </select>

            <label>Edad</label>
            <input type="text" name="edad" value="<?= $res['edad'] ?>" required>

            <label>Peso</label>
            <input type="text" name="peso" value="<?= $res['peso'] ?>" required>

            <label>Raza</label>
            <input type="text" name="raza" value="<?= $res['raza'] ?>" required>

            <label>Origen</label>
            <select name="origen_tipo" id="origen_tipo" onchange="mostrarOrigenGenetico()" required>
                <option value="">Selecciona</option>
                <option value="comercial" <?= $res['origen_tipo'] === 'comercial' ? 'selected' : '' ?>>Línea Comercial</option>
                <option value="genetico" <?= $res['origen_tipo'] === 'genetico' ? 'selected' : '' ?>>Con antecedentes genéticos</option>
            </select>

            <div id="origen_genetico_extra" style="display:<?= $res['origen_tipo'] === 'genetico' ? 'block' : 'none' ?>">
                <label>Detalle del origen genético</label>
                <textarea name="origen"><?= $res['origen'] ?></textarea>
            </div>

            <label>Alimentación</label>
            <textarea name="alimentacion" required><?= $res['alimentacion'] ?></textarea>

            <label>Zona / Ubicación</label>
            <input type="text" name="ubicacion" value="<?= $res['ubicacion'] ?>" required>

            <label>Vacunas</label>
            <textarea name="vacunas" required><?= $res['vacunas'] ?></textarea>

            <label>Imagen (sube una nueva si deseas reemplazar)</label>
            <input type="file" name="imagen" accept="image/*">

            <button type="submit">Actualizar publicación</button>
        </form>
    </div>

<script>
function cargarTipos() {
    const clasificacion = document.getElementById("clasificacion").value;
    const tipoSelect = document.getElementById("tipo");
    tipoSelect.innerHTML = "";

    let tipos = [];
    if (clasificacion === "primera") {
        tipos = ["ML", "MC", "TO", "BM", "HL", "HV", "VP", "VE"];
    } else if (clasificacion === "segunda") {
        tipos = ["ML", "MC", "HL", "HV", "VP", "VE"];
    }

    tipos.forEach(tipo => {
        const option = document.createElement("option");
        option.value = tipo;
        option.text = tipo;
        tipoSelect.appendChild(option);
    });
}

function mostrarOrigenGenetico() {
    const tipo = document.getElementById("origen_tipo").value;
    document.getElementById("origen_genetico_extra").style.display = tipo === "genetico" ? "block" : "none";
}
</script>
</body>
</html>
