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
    exit;
}

// Obtener datos actuales del registro
$sql = "SELECT * FROM `$tabla` WHERE $id_column = " . intval($id);
$resultado = $conexion->query($sql);

 echo '<h2>Actualización de un registro  en '.htmlspecialchars($tabla).'</h2>';
?>

<form action="./admin/update/formulario_actualizar.php" method="POST">
 <div class="controlformulario">
      <p>Introduce el ID del elemento que quieres <b>actualizar</b>:</p>
      <!-- Input que no se muestran-->
      <input type="hidden" name="tabla" value="<?php echo htmlspecialchars($tabla); ?>">
      <input type="hidden" name="id_column" value="<?php echo $id_column; ?>">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
      <!-- Input que muestran generados con PHP-->
    <?php
    $fila = $resultado->fetch_assoc();
    // Generar campos dinámicamente
    foreach ($fila as $campo => $valor) {
        if ($campo == $id_column) continue;
        if ($campo == 'fecha_registro' || $campo == 'fecha_creacion') continue;
        
        $label = ucwords(str_replace('_', ' ', $campo));
        $tipo = 'text';
        
        if (strpos($campo, 'email') !== false) $tipo = 'email';
        elseif (strpos($campo, 'telefono') !== false) $tipo = 'tel';
        elseif (strpos($campo, 'contrasenya') !== false) $tipo = 'password';
        elseif (strpos($campo, 'descripcion') !== false) {
            echo '<div class="controlformulario">
                <label for="'.$campo.'">'.$label.'</label>
                <textarea name="'.$campo.'" id="'.$campo.'">'.htmlspecialchars($valor).'</textarea>
            </div>';
            continue;
        }
        
        echo '<div class="controlformulario">
            <label for="'.$campo.'">'.$label.'</label>
            <input type="'.$tipo.'" name="'.$campo.'" id="'.$campo.'" 
                   value="'.htmlspecialchars($valor).'">
        </div>';
    }
    ?>      
      <input type="submit" value="Actualizar registro">
      <a href="../tipo_de_usuario/admin.php?tabla=<?php echo urlencode($tabla); ?>" style="margin-left:10px;">Cancelar</a>
 </div>
</form>

<style>
 div{padding:3px;}
 .controlformulario{font-weight:bold;font-size:15px;display:inline-block;}
</style>
