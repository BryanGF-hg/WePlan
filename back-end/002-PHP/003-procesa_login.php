<?php
  session_start();
  $correo = trim($_POST['correo_electronico'] ?? '');
  $pass = $_POST['contrasenya'] ?? '';
  
  $correo_min = 5;
  $correo_max = 30;
  $pass_min = 8;
  $pass_max = 16;
  switch(true) {
      case (strlen($correo) < $correo_min):
          $_SESSION['error'] = "El correo debe tener al menos $correo_min caracteres";      
          header("Location: ./004-login.php?error=correo_corto");
          exit(); 
          
      case (strlen($correo) > $correo_max):
          $_SESSION['error'] = "El correo debe tener menos que $correo_max caracteres";       
          header("Location: ./004-login.php?error=correo_largo");
          exit();
          
      case (strlen($pass) < $pass_min):
          $_SESSION['error'] = "La contraseña debe tener al menos $pass_min caracteres";       
          header("Location: ./004-login.php?error=pass_corto");
          exit();
          
      case (strlen($pass) > $pass_max):
          $_SESSION['error'] = "La contraseña debe tener menor que $pass_max caracteres";       
          header("Location: ./004-login.php?error=pass_largo");
          exit();       
  }
  
  $host = "localhost";$user = "usuario-weplan";$pass = "Usuarioweplan123$";$db   = "WePlanDB";
  $conexion = new mysqli($host, $user, $pass, $db);
  
	// Comprobacion exitosa mientras que se mira los datos que vienen del 004-login.php en POST
  $sql = " SELECT * FROM usuarios WHERE
    correo_electronico = '".$_POST['correo_electronico']."'
    AND contrasenya = '".$_POST['contrasenya']."';
  ";
	
  $resultado = $conexion->query($sql);

  if ($fila = $resultado->fetch_assoc()) {	 // Si es cierto que hay un resultado
    $tipo = strtolower(trim($fila['tipo_de_usuario']));
    $_SESSION['usuario'] = 'si'; 
    $_SESSION['tipo_usuario'] = $tipo;
    $_SESSION['correo_usuario'] = $_POST['correo_electronico'];
    $_SESSION['usuario_id'] = $fila['usuario_id'];
    $_SESSION['nombre'] = $fila['nombre'];    
    
    switch($tipo){                          // Dependiendo del tipo de usuario es redigirido a un sitio diferente
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
  }else{																		                  // Si no hay ningun resultado, que muestre que ha habido un error con el correo o la contraseña (porque lo compara contra la base de datos). ADEMÁS de que volvemos a login
   $_SESSION['error'] = "Correo o contraseña incorrectos";
  	header("Location: ./004-login.php");					
  	exit();
  }

  $conexion->close();
?>
