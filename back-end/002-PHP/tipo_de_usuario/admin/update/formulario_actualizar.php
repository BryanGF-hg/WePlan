<?php
if (!isset($_POST['tabla']) || empty($_POST['tabla'])) {
    die("Error: No se especificó la tabla");
}
if (!isset($_POST['id']) || empty($_POST['id'])) {
    die("Error: No se especificó el ID");
}

$tabla = $_POST['tabla'];
$id = $_POST['id'];

$host = "localhost";
$user = "usuario-weplan";
$pass = "Usuarioweplan123$";
$db   = "WePlanDB";
$conexion = new mysqli($host, $user, $pass, $db);

// Determinar columna ID
$id_column = isset($_POST['id_column']) ? $_POST['id_column'] : 'usuario_id';

// Construir consulta UPDATE dinámica
$actualizaciones = [];
foreach ($_POST as $campo => $valor) {
    if ($campo == 'tabla' || $campo == 'id' || $campo == 'id_column') continue;
    
    $actualizaciones[] = "`$campo` = '" . $conexion->real_escape_string($valor) . "'";
}

if (empty($actualizaciones)) {
    die("Error: No hay campos para actualizar");
}


$sql = "UPDATE `$tabla` SET " . implode(", ", $actualizaciones) . 
       " WHERE $id_column = " . intval($id);


if($conexion->query($sql)) {
      echo " Registro actualizado correctamente<br>";
      echo "<a href='../../admin.php'>Volver</a>";
  } else {
      echo "Error: " . $conexion->error . "<br>";
      echo "<a href='../../admin.php'>Volver</a>";
}
	
  $conexion->close();
?>
