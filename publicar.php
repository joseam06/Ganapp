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
  <title>Publicar Res o Lote - GanApp</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5" style="max-width: 600px;">
  <h2 class="mb-4 text-center">Publicar Nueva Res o Lote</h2>

  <form action="guardar-res.php" method="POST" enctype="multipart/form-data">

    <!-- Tipo general -->
    <div class="mb-3">
      <label for="tipo_publicacion" class="form-label">Tipo de publicación</label>
      <select id="tipo_publicacion" name="tipo_publicacion" class="form-select" required onchange="mostrarCampos()">
        <option value="">Selecciona</option>
        <option value="res">Res individual</option>
        <option value="lote">Lote</option>
      </select>
    </div>

    <!-- Clasificación -->
    <div class="mb-3">
      <label for="clasificacion" class="form-label">Clasificación</label>
      <select id="clasificacion" name="clasificacion" class="form-select" onchange="cargarTipos()" required>
        <option value="">Selecciona</option>
        <option value="primera">Primera</option>
        <option value="segunda">Segunda</option>
      </select>
    </div>

    <!-- Tipo -->
    <div class="mb-3">
      <label for="tipo" class="form-label">Tipo</label>
      <select id="tipo" name="tipo" class="form-select" required>
        <option value="">Selecciona clasificación primero</option>
      </select>
    </div>

    <!-- Campos comunes -->
    <div id="campos-comunes">
      <div class="mb-3" id="campo-edad">
        <label for="edad" class="form-label">Edad</label>
        <input type="text" name="edad" id="edad" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="peso" class="form-label" id="label-peso">Peso</label>
        <input type="text" name="peso" id="peso" class="form-control" required>
      </div>

      <div class="mb-3" id="campo-raza">
        <label for="raza" class="form-label">Raza</label>
        <input type="text" name="raza" id="raza" class="form-control">
      </div>

      <div class="mb-3">
        <label for="origen_tipo" class="form-label">Origen</label>
        <select id="origen_tipo" name="origen_tipo" class="form-select" onchange="mostrarOrigenGenetico()" required>
          <option value="">Selecciona</option>
          <option value="comercial">Línea Comercial</option>
          <option value="genetico">Con antecedentes genéticos</option>
        </select>
      </div>

      <div class="mb-3" id="origen_genetico_extra" style="display:none;">
        <label for="origen" class="form-label">Detalle del origen genético</label>
        <textarea name="origen" id="origen" class="form-control"></textarea>
      </div>

      <div class="mb-3" id="campo-salud">
        <label for="salud" class="form-label">Estado de Salud</label>
        <input type="text" name="salud" id="salud" class="form-control" required>
    </div>

      <div class="mb-3">
        <label for="alimentacion" class="form-label">Alimentación</label>
        <textarea name="alimentacion" id="alimentacion" class="form-control" required></textarea>
      </div>

      <div class="mb-3">
        <label for="ubicacion" class="form-label">Zona / Ubicación</label>
        <input type="text" name="ubicacion" id="ubicacion" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="vacunas" class="form-label">Vacunas</label>
        <textarea name="vacunas" id="vacunas" class="form-control" required></textarea>
      </div>

      <div class="mb-3">
        <label for="imagen" class="form-label">Imagen</label>
        <input type="file" name="imagen" class="form-control" accept="image/*" required>
      </div>
    </div>

    <!-- Campos solo para lote -->
    <div id="campos-lote" style="display:none;">
        <div class="mb-3">
        <label for="salud_general" class="form-label">Salud General del Lote</label>
        <input type="text" name="salud_general" id="salud_general" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="cantidad" class="form-label">Cantidad de reses</label>
        <input type="number" name="cantidad" id="cantidad" class="form-control">
      </div>
    </div>

    <button type="submit" class="btn btn-success w-100">Guardar</button>
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

function mostrarCampos() {
  const tipo = document.getElementById("tipo_publicacion").value;
  const campoEdad = document.getElementById("campo-edad");
  const campoRaza = document.getElementById("campo-raza");
  const camposLote = document.getElementById("campos-lote");
  const labelPeso = document.getElementById("label-peso");
  const campoSalud = document.getElementById("campo-salud");
  const saludGeneralInput = document.getElementById("salud_general");
  const cantidadInput = document.getElementById("cantidad");
  const saludInput = document.getElementById("salud");
  const razaInput = document.getElementById("raza");

  if (tipo === "res") {
    // Mostrar campos de res
    campoEdad.querySelector("label").textContent = "Edad";
    labelPeso.textContent = "Peso";
    campoRaza.style.display = "block";
    campoSalud.style.display = "block";
    camposLote.style.display = "none";

    // Validación
    saludGeneralInput.removeAttribute("required");
    cantidadInput.removeAttribute("required");
    saludInput.setAttribute("required", "required");
    razaInput.setAttribute("required", "required");
  } else if (tipo === "lote") {
    // Mostrar campos de lote
    campoEdad.querySelector("label").textContent = "Rango de Edad";
    labelPeso.textContent = "Peso Promedio";
    campoRaza.style.display = "none";
    campoSalud.style.display = "none";
    camposLote.style.display = "block";

    // Validación
    saludInput.removeAttribute("required");
    razaInput.removeAttribute("required");
    saludGeneralInput.setAttribute("required", "required");
    cantidadInput.setAttribute("required", "required");
  } else {
    // Inicial
    campoRaza.style.display = "block";
    campoSalud.style.display = "block";
    camposLote.style.display = "none";
  }
}

</script>

</body>
</html>
