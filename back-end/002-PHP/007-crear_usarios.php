<?php session_start(); ?>
<!doctype html>
<html lang="es">
<head>
<title>WePlan - Crear Usuario</title>
<meta charset="utf-8">
<style>
     body,html{padding:0px;margin:0px;width:100%;height:100%;}
     body{display:flex;justify-content:center;align-items:center;text-align:center;background:BlanchedAlmond;}
     form{display:flex;flex-direction:column;width:300px;background:#FFFFFD;padding:20px;align-items:center;gap:10px;justify-content:center;}
     input{width:100%;padding:15px;box-sizing:border-box;border:1px solid lightgrey;}
     div{ font-size:13px;color:black;}
     #fake-footer{font-weight:bold;font-size:20px;}
     .error-message{font-weight:bold;font-size:18px;color:red;}
     .success-message{font-weight:bold;font-size:18px;color:green;}
     .form-row{width:100%;text-align:left;margin-bottom:5px;}
</style>
</head>
<body>
<main>  
<div id="fake-header">
<h1>Crear Nuevo Usuario v0.1 📝</h1>
</div>

<?php
// Mostrar mensajes de sesión
if (isset($_SESSION['error'])) {
    echo '<div class="error-message">';
    echo htmlspecialchars($_SESSION['error']);
    echo '</div>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<div class="success-message">';
    echo htmlspecialchars($_SESSION['success']);
    echo '</div>';
    unset($_SESSION['success']);
}
?>

<form action="procesa_registro.php" method="POST">
    <div class="form-row">
        <label for="nombre">Nombre completo:</label>
        <input type="text" name="nombre" id="nombre" placeholder="Ej: Juan Pérez" required>
    </div>
    
    <div class="form-row">
        <label for="correo_electronico">Correo electrónico:</label>
        <input type="email" name="correo_electronico" id="correo_electronico" placeholder="usuario@ejemplo.com" required>
    </div>
    
    <div class="form-row">
        <label for="nombre_usuario">Nombre de usuario:</label>
        <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="5-20 caracteres" required>
    </div>
    
    <div class="form-row">
        <label for="contrasenya">Contraseña:</label>
        <input type="password" name="contrasenya" id="contrasenya" placeholder="8-16 caracteres" required>
    </div>
    
    <div class="form-row">
        <label for="confirmar_contrasenya">Confirmar contraseña:</label>
        <input type="password" name="confirmar_contrasenya" id="confirmar_contrasenya" placeholder="Repite la contraseña" required>
    </div>
    
    <input type="submit" value="Crear Usuario">
</form>

<div id="form-content">
<p>📋 Requisitos de registro:</p>
<p>• Nombre: Máximo 50 caracteres</p>
<p>• Correo: Formato válido</p>
<p>• Usuario: 5-20 caracteres</p>
<p>• Contraseña: 8-16 caracteres</p>
<p>• Las contraseñas deben coincidir</p>        
</div>

<div style="margin:20px;">
    <a href="index.php" style="color:#333;text-decoration:none;font-weight:bold;">← Volver al Login</a>
</div>

<div id="fake-footer">
       (c) Ayman x Bryan 2025      
</div>
</main>
</body>
</html>