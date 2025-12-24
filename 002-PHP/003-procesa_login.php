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
    $_SESSION['usuario'] = 'si';
    header("Location: 005-exito.php");					// En ese caso vamos a la pagina de exito
    exit();
  }else{																		// Si no hay ningun resultado
  	header("Location: 004-login.html");					// En ese caso volvemos al login
  	exit();
  }

  $conexion->close();
  
?>
