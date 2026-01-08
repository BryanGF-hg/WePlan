<?php
$tabla = $_GET['tabla'] ?? '';
$id = $_GET['id'] ?? '';

if(empty($tabla) || empty($id)) {
    // Redirigir con error
    header("Location: ../../admin.php?error=faltan_datos");
    exit;
}

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
    

// Ejecutar DELETE
$sql = "DELETE FROM `$tabla` WHERE $id_column = " . intval($id);
$conexion->query($sql);
$conexion->close();

echo "<script>
    // Muestrame mensaje de éxito
    alert('Registro eliminado!');
    
    // Recargame la página manteniendo la misma tabla
    window.location.href = './admin.php?tabla=" . urlencode($tabla) . "';
</script>";
?>
