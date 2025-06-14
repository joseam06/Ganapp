<?php
header('Content-Type: application/json');
include 'db.php';

// Validar parámetros requeridos
if (
    !isset($_POST['clasificacion']) ||
    !isset($_POST['tipo']) ||
    !isset($_POST['peso']) ||
    !isset($_POST['edad'])
) {
    echo json_encode(['error' => 'Faltan parámetros obligatorios.']);
    exit;
}

$clasificacion = $_POST['clasificacion'];
$tipo = $_POST['tipo'];
$peso = floatval($_POST['peso']);
$edad = floatval($_POST['edad']);
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

// Llamar a la función estimar_valor_res
$sql = "SELECT estimar_valor_res(?, ?, ?, ?) AS valor";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssdd", $clasificacion, $tipo, $peso, $edad);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$valor_unitario = $row['valor'] ?? 0;
$valor_total = $valor_unitario * $cantidad;

echo json_encode([
    'valor_unitario' => $valor_unitario,
    'valor_total' => $valor_total,
    'cantidad' => $cantidad
]);
