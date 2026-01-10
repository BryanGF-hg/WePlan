<?php
  session_start();
  $correo = trim($_POST['correo_electronico'] ?? '');
  $pass = $_POST['contrasenya'] ?? '';
  switch(true) {
      case (strlen($correo) < 5):
          $_SESSION['error'] = "El correo debe tener al menos 5 caracteres";      
          header("Location: ./004-login.php?error=correo_corto");
          exit(); 
          
      case (strlen($correo) > 25):
          $_SESSION['error'] = "El correo debe tener menos que 25 caracteres";       
          header("Location: ./004-login.php?error=correo_largo");
          exit();
          
      case (strlen($pass) < 8):
          $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres";       
          header("Location: ./004-login.php?error=pass_corto");
          exit();
          
      case (strlen($pass) > 16):
          $_SESSION['error'] = "La contraseña debe tener menor que 16 caracteres";       
          header("Location: ./004-login.php?error=pass_largo");
          exit();
  }
  
  $host = "localhost";
  $user = "usuario-weplan";
  $pass = "Usuarioweplan123$";
  $db   = "WePlanDB";
  $conexion = new mysqli($host, $user, $pass, $db);
  
	// Comprobacion exitosa pero mirando los datos que vienen del formulario en POST
  $sql = "
  	SELECT * FROM usuarios WHERE
    correo_electronico = '".$_POST['correo_electronico']."'
    AND contrasenya = '".$_POST['contrasenya']."';
  ";
	
  $resultado = $conexion->query($sql);

  if ($fila = $resultado->fetch_assoc()) {	// Si es cierto que hay un resultado
    $tipo = strtolower(trim($fila['tipo_de_usuario']));
    $_SESSION['usuario'] = 'si'; 
    $_SESSION['tipo_usuario'] = $tipo;
    $_SESSION['correo_usuario'] = $_POST['correo_electronico'];
    
    switch($tipo){      // Dependiendo del tipo de usuario es redigirido a un sitio diferente
      case 'admin':
          header("Location: ./tipo_de_usuario/admin.php");
          break;
      case 'host':
          header("Location: ./tipo_de_usuario/host.php");
          break;
      case 'usuario':
          header("Location: ./tipo_de_usuario/usuario.php");
          break; 
      default:
          header("Location: 005-exito.php");
          break;                             
    }
    exit();
  }else{																		// Si no hay ningun resultado
  	header("Location: ./004-login.php");					// En ese caso volvemos al login
  	exit();
  }

  $conexion->close();
  
?>
