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
$detalles_origen = $_POST['origen'] ?? '';
$salud = $_POST['salud'] ?? '';
$salud_general = $_POST['salud_general'] ?? '';
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : null;
$precio_final = isset($_POST['precio_final']) ? floatval($_POST['precio_final']) : 0;

if ($tipo_publicacion === 'lote') {
    // Guardar lote
    $sql = "INSERT INTO lotes (
        imagen, cantidad, edad_promedio, peso_promedio,
        salud_general, alimentacion, ubicacion,
        id_usuario, fecha_publicacion, vendido, precio
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0, ?)";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        echo "Error en prepare (lote): " . $conexion->error;
        exit();
    }

    $stmt->bind_param(
    "sisssssid",
    $rutaImagen,
    $cantidad,
    $edad,
    $peso,
    $salud_general,
    $alimentacion,
    $ubicacion,
    $usuario_id,
    $precio_final
);

} else {
    // Guardar res individual
   $raza = $_POST['raza'] ?? '';

$sql = "INSERT INTO reses (
    edad, vacunas, salud, peso, alimentacion,
    ubicacion, imagen, id_usuario,
    clasificacion, tipo, raza, origen_tipo, detalles_origen, precio
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo "Error en prepare (res): " . $conexion->error;
    exit();
}


$stmt->bind_param(
    "sssssssisssssd",
    $edad,
    $vacunas,
    $salud,
    $peso,
    $alimentacion,
    $ubicacion,
    $rutaImagen,
    $usuario_id,
    $clasificacion,
    $tipo,
    $raza,
    $origen_tipo,
    $detalles_origen,
    $precio_final
);

}


if ($stmt->execute()) {
    header("Location: index.php?publicado=ok");
    exit();
} else {
    echo "Error al guardar: " . $stmt->error;
}
?>