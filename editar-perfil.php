<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}
include 'db.php';

$id = $_SESSION['usuario_id'];
$sql = "SELECT nombre, correo FROM usuarios WHERE id = $id";
$result = $conexion->query($sql);
$usuario = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNombre = $_POST['nombre'];
    $nuevoCorreo = $_POST['correo'];

    $sql = "UPDATE usuarios SET nombre='$nuevoNombre', correo='$nuevoCorreo' WHERE id=$id";
    if ($conexion->query($sql)) {
        $_SESSION['nombre'] = $nuevoNombre; // actualiza nombre en sesión
        header("Location: perfil.php");
        exit();
    } else {
        $error = "Error al actualizar el perfil.";
    }
}
?>
<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil - GanApp</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      background-color: #f5f6fa;
      font-family: Arial, sans-serif;
    }
    .container {
      max-width: 500px;
      margin: 80px auto;
      background-color: #fff;
      border-radius: 10px;
      padding: 30px 40px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    h2 {
      color: #004d40;
      text-align: center;
      margin-bottom: 30px;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
      margin-top: 15px;
    }
    input[type="text"],
    input[type="email"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      background-color: #00796b;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      margin-top: 30px;
      width: 100%;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover {
      background-color: #004d40;
    }
    .error {
      color: red;
      margin-top: 10px;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Editar Perfil</h2>

  <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

  <form method="POST">
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>

    <label for="correo">Correo</label>
    <input type="email" id="correo" name="correo" value="<?php echo $usuario['correo']; ?>" required>

    <button type="submit">Guardar Cambios</button>
  </form>
</div>

</body>
</html>