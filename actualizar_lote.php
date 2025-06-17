<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

if (!isset($_POST['id'])) {
    echo "ID de lote no especificado.";
    exit();
}

$id = intval($_POST['id']);
$usuario_id = $_SESSION['usuario_id'];

// Verificar que el lote pertenezca al usuario
$verificar = $conexion->prepare("SELECT imagen FROM lotes WHERE id = ? AND id_usuario = ?");
$verificar->bind_param("ii", $id, $usuario_id);
$verificar->execute();
$resultado = $verificar->get_result();

if ($resultado->num_rows !== 1) {
    echo "Lote no encontrado o no autorizado.";
    exit();
}

$loteActual = $resultado->fetch_assoc();
$rutaImagen = $loteActual['imagen'];

// Si se subió una nueva imagen
if (!empty($_FILES['imagen']['name'])) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
    if (!in_array($_FILES['imagen']['type'], $permitidos)) {
        echo "Formato de imagen no permitido.";
        exit();
    }

    $directorio = "img/reses/";
    $nombreImagen = uniqid() . "_" . basename($_FILES["imagen"]["name"]);
    $nuevaRuta = $directorio . $nombreImagen;

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $nuevaRuta)) {
        // Eliminar imagen anterior si existe y es diferente
        if ($rutaImagen && file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
        $rutaImagen = $nuevaRuta;
    } else {
        echo "Error al subir la nueva imagen.";
        exit();
    }
}

// Capturar los demás datos
$edad_promedio   = $_POST['edad_promedio'];
$peso_promedio   = $_POST['peso_promedio'];
$cantidad        = intval($_POST['cantidad']);
$salud_general   = $_POST['salud_general'];
$alimentacion    = $_POST['alimentacion'];
$origen          = $_POST['origen'];
$ubicacion       = $_POST['ubicacion'];
$precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;


// Validación básica
if (empty($edad_promedio) || empty($peso_promedio) || empty($salud_general) || empty($alimentacion) || empty($origen) || empty($ubicacion)) {
    echo "Todos los campos obligatorios deben estar completos.";
    exit();
}

// Actualizar en la base de datos
$sql = "UPDATE lotes SET 
    edad_promedio = ?, 
    peso_promedio = ?, 
    cantidad = ?, 
    salud_general = ?, 
    alimentacion = ?, 
    origen = ?, 
    ubicacion = ?, 
    imagen = ?, 
    precio = ?
    WHERE id = ? AND id_usuario = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssisssssdi",

     $edad_promedio,
    $peso_promedio,
    $cantidad,
    $salud_general,
    $alimentacion,
    $origen,
    $ubicacion,
    $rutaImagen,
    $precio,
    $id,
    $usuario_id
);

if ($stmt->execute()) {
    header("Location: mis_publicaciones.php?actualizado=lote");

    exit();
} else {
    echo "Error al actualizar: " . $stmt->error;
}
?>
