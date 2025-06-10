<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$usuario_id = $_SESSION['usuario_id'];

// Subir imagen
$directorio = "img/reses/";
$nombreImagen = uniqid() . "_" . basename($_FILES["imagen"]["name"]);
$rutaImagen = $directorio . $nombreImagen;

if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaImagen)) {
    echo "Error al subir la imagen.";
    exit();
}

// Capturar datos comunes
$tipo_publicacion = $_POST['tipo_publicacion'];
$clasificacion = $_POST['clasificacion'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$edad = $_POST['edad'] ?? '';
$peso = $_POST['peso'] ?? '';
$alimentacion = $_POST['alimentacion'] ?? '';
$ubicacion = $_POST['ubicacion'] ?? '';
$vacunas = $_POST['vacunas'] ?? '';
$origen_tipo = $_POST['origen_tipo'] ?? '';
$origen = $_POST['origen'] ?? '';
$salud = $_POST['salud'] ?? '';
$salud_general = $_POST['salud_general'] ?? '';
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : null;

if ($tipo_publicacion === 'lote') {
    // Guardar lote
    $sql = "INSERT INTO lotes (
        imagen, cantidad, edad_promedio, peso_promedio,
        salud_general, alimentacion, origen, ubicacion,
        id_usuario, fecha_publicacion, vendido
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0)";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        echo "Error en prepare (lote): " . $conexion->error;
        exit();
    }

    $stmt->bind_param(
        "sissssssi",
        $rutaImagen,
        $cantidad,
        $edad,
        $peso,
        $salud_general,
        $alimentacion,
        $origen,
        $ubicacion,
        $usuario_id
    );
} else {
    // Guardar res individual
   $raza = $_POST['raza'] ?? '';

$sql = "INSERT INTO reses (
    edad, vacunas, salud, peso, alimentacion,
    origen, ubicacion, imagen, id_usuario,
    clasificacion, tipo, raza, origen_tipo
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo "Error en prepare (res): " . $conexion->error;
    exit();
}

$stmt->bind_param(
    "ssssssssissss",
    $edad,
    $vacunas,
    $salud,
    $peso,
    $alimentacion,
    $origen,
    $ubicacion,
    $rutaImagen,
    $usuario_id,
    $clasificacion,
    $tipo,
    $raza,
    $origen_tipo
);

}


if ($stmt->execute()) {
    header("Location: index.php?publicado=ok");
    exit();
} else {
    echo "Error al guardar: " . $stmt->error;
}
?>
