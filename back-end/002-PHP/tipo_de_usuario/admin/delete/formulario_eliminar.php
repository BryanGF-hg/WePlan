<?php
  // Recibe y valida parámetros
  if (!isset($_GET['tabla']) || empty($_GET['tabla'])) {
      die("Error: No se especificó la tabla");
  }
  if (!isset($_GET['id']) || empty($_GET['id'])) {
      die("Error: No se especificó el ID");
  }

  $tabla = $_GET['tabla'];
  $id = $_GET['id'];
  $id_column = 'usuario_id';

  $host = "localhost";
  $user = "usuario-weplan";
  $pass = "Usuarioweplan123$";
  $db   = "WePlanDB";
  $conexion = new mysqli($host, $user, $pass, $db);	 

// Estructura para Nombre de la columna ID
$id_column = 'usuario_id';                       // El por de defecto
if ($tabla == 'grupos') $id_column = 'grupo_id';
// Tablas normales
elseif ($tabla == 'categorias_actividades') $id_column = 'categoria_id';
elseif ($tabla == 'subcategorias_actividades') $id_column = 'subcategoria_id';
elseif ($tabla == 'aficiones') $id_column = 'aficion_id';
elseif ($tabla == 'eventos') $id_column = 'evento_id';
// tablas de relación 
elseif ($tabla == 'usuario_grupo' || $tabla == 'usuario_aficion' || 
        $tabla == 'grupo_subcategoria' || $tabla == 'evento_participantes') {
    echo "<p>Error: Las tablas de relación requieren manejo especial.</p>";
    echo "<a href='../../admin.php?tabla=".urlencode($tabla)."'>Volver</a>";
}
    
  // Accion SQL para DELETE    
  $sql = "DELETE FROM `$tabla` WHERE $id_column = " . intval($id);								
  
  if($conexion->query($sql)) {
      echo " Registro eliminado correctamente<br>";
      echo "<a href='../../admin.php'>Volver</a>";
  } else {
      echo "Error: " . $conexion->error . "<br>";
      echo "<a href='../../admin.php'>Volver</a>";
  }

  $conexion->close();																       
?>
