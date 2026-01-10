<?php session_start(); ?>
<!doctype html>
<html lang="es">
  <head>
    <title>WePlan - Login</title>
    <meta charset="utf-8">
    <style>
     body,html{padding:0px;margin:0px;width:100%;height:100%;}
     body{display:flex;justify-content:center;align-items:center;text-align:center;background:BlanchedAlmond;}
     form{display:flex;flex-direction:column;width:200px;height:200px;background:#FFFFFD;padding:20px;align-items:center;gap:10px;justify-content:center;}
     input{width:100%;padding:15px;box-sizing:border-box;border:1 px solid lightgrey;}
     div{ font-size:13px;color:black;}
      #fake-footer{font-weight:bold;font-size:20px;}
      .error-message{font-weight:bold;font-size:18px;}
    </style>
  </head>
  <body>
    <main>  
      <div id="fake-header">
        <h1>Página de Login v0.1 🔐</h1>
      </div>
      <form action="003-procesa_login.php" method="POST">
        <input type="text" name="correo_electronico" placeholder="Correo electrónico">
        <input type="password" name="contrasenya" placeholder="Contraseña">
        <input type="submit">
      </form>
      <?php
      // Mostrar error de sesión si existe
      if (isset($_SESSION['error'])) {
          echo '<div class="error-message">';
          echo htmlspecialchars($_SESSION['error']);
          echo '</div>';
          unset($_SESSION['error']); // Limpiar después de mostrar
      }
      ?>        
      <div id="form-content">
        <p>📋 Requisitos:</p>
        <p>• Usuario: 5-20 caracteres</p>
        <p>• Contraseña: 8-16 caracteres</p>        
      </div>
      <div id="fake-footer">
       (c) Ayman x Bryan 2025      
      </div>
    </main>
  </body>
</html>

<?php
        // Mostrar estado de validación del correo previo
        if (!empty($correo_previo)) {
            $color_correo = validarCorreoEnTiempoReal($correo_previo);
            $clase_correo = ($color_correo === 'green') ? 'valido' : 'invalido';
            
            echo '<p class="' . $clase_correo . '">';
            echo 'Correo: ' . strlen(trim($correo_previo)) . '/20 caracteres';
            
            if (!filter_var($correo_previo, FILTER_VALIDATE_EMAIL)) {
                echo ' (Formato inválido)';
            }
            echo '</p>';
        }
?>
