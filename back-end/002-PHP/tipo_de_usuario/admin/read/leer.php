<?php
	session_start(); // Arranco una sesion
 $host = "localhost";
 $user = "usuario-weplan";
 $pass = "Usuarioweplan123$";
 $db   = "WePlanDB";
 $conexion = new mysqli($host, $user, $pass, $db);

	
 // Lista de las tablas en la base de datos


  $resultado = $conexion->query("SHOW TABLES;");
  echo "<a href='../../admin.php' id='volver-btn'>Volver</a>";  
  while ($fila = $resultado->fetch_assoc()) {
    echo "<div>";  
    echo '<a href="?tabla='.$fila['Tables_in_'.$db].'">'.$fila['Tables_in_'.$db].'</a>';
    echo "</div>";
  }
if(isset($_GET['tabla']) && !empty($_GET['tabla'])) {
    $tabla = $conexion->real_escape_string($_GET['tabla']);
    $sql = "SELECT * FROM ".$_GET['tabla']." LIMIT 1;";	
     $resultado = $conexion->query($sql);
     while ($fila = $resultado->fetch_assoc()) {
     	var_dump($fila);
     }
	  }
	  
  $conexion->close();
?>
<style>
 a{padding:2px;text-decoration:none;font-weight:bold;}
 div{display:flex;}
 #volver-btn{font-size:25px;}
</style>
