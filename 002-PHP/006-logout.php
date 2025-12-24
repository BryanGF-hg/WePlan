<?php
session_start();

// Guardar información del usuario para mostrar mensaje
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';

// Destruir sesión
session_unset();
session_destroy();

// Redirigir al login con mensaje
header("Location: 004-login.html?logout=1&user=" . urlencode($nombre));
exit();
?>
