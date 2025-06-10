<?php
session_start();
session_unset();   // Limpia todas las variables de sesión
session_destroy(); // Elimina la sesión por completo

// Redirige al login
header("Location: login.html");
exit();
?>
