<?php
  session_start();
  $host = "localhost";
  $user = "usuario-weplan";
  $pass = "Usuarioweplan123$";
  $db   = "WePlanDB";
  $conexion = new mysqli($host, $user, $pass, $db);
  
	// Comprobacion exitosa pero mirando los datos que vienen del formulario en POST
  $sql = "
  	SELECT 
    *
    FROM usuarios
    WHERE
    correo_electronico = '".$_POST['correo_electronico']."'
    AND
    contrasenya = '".$_POST['contrasenya']."';
  ";
	
  $resultado = $conexion->query($sql);

  if ($fila = $resultado->fetch_assoc()) {	// Si es cierto que hay un resultado
    $tipo = strtolower(trim($fila['tipo_de_usuario']));
    $_SESSION['usuario'] = 'si'; 
    var_dump($fila['tipo_de_usuario']);
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
  	header("Location: 004-login.html");					// En ese caso volvemos al login
  	exit();
  }

  $conexion->close();
  
?>
