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
?>
<?php include 'navbar.php'; ?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil - GanApp</title>
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
      background-color: #f0f0f0;
    }
    .btn-editar {
      margin-top: 30px;
      text-align: center;
    }
    .btn-editar a {
      background-color: #00796b;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      display: inline-block;
    }
    .btn-editar a:hover {
      background-color: #004d40;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Mi Perfil</h2>

  <label for="nombre">Nombre</label>
  <input type="text" id="nombre" value="<?php echo $usuario['nombre']; ?>" readonly>

  <label for="correo">Email</label>
  <input type="email" id="correo" value="<?php echo $usuario['correo']; ?>" readonly>

  <div class="btn-editar">
    <a href="editar-perfil.php">Editar Perfil</a>
  </div>
</div>

</body>
</html>