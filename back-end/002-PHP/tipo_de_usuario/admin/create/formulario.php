<?php
// Conexión a la base de datos
$host = "localhost";
$user = "usuario-weplan";
$pass = "Usuarioweplan123$";
$db   = "WePlanDB";
$conexion = new mysqli($host, $user, $pass, $db);

// Obtener la tabla desde la URL
if (!isset($_GET['tabla']) || empty($_GET['tabla'])) {
    echo "<p>Error: No se seleccionó una tabla. Vuelve atrás y selecciona una tabla.</p>";
    echo '<a href="../../admin.php">← Volver</a>';
    exit;
}
$tabla = $conexion->real_escape_string($_GET['tabla']);

// Obtener estructura de la tabla
$resultado = $conexion->query("SHOW COLUMNS FROM `$tabla`");
if (!$resultado) {
    echo "<p>Error: No se pudo obtener información de la tabla '$tabla'</p>";
    echo '<a href="../../admin.php">← Volver</a>';
    exit;
}

echo '<form action="./admin/create/procesaformulario.php" method="POST">';
echo '<input type="hidden" name="tabla" value="'.htmlspecialchars($tabla).'">';
echo '<h2>Creación de registro en tabla "'.htmlspecialchars($tabla).'"</h2>';

while ($columna = $resultado->fetch_assoc()) {
    $nombre = $columna['Field'];
    $tipo = $columna['Type'];
    
    // Saltar columnas auto_increment
    if (strpos($columna['Extra'], 'auto_increment') !== false) {
        continue;
    }
    
    // Saltar timestamps automáticos
    if (($nombre == 'fecha_registro' || $nombre == 'fecha_creacion') && 
        strpos($columna['Default'], 'CURRENT_TIMESTAMP') !== false) {
        continue;
    }
    
    // Crear label amigable
    $label = ucwords(str_replace('_', ' ', $nombre));
    
    // Determinar tipo de input
    $tipo_input = 'text';
    $es_textarea = false;
    
    // Por nombre de campo
    if (strpos(strtolower($nombre), 'email') !== false) {
        $tipo_input = 'email';
    } elseif (strpos(strtolower($nombre), 'telefono') !== false) {
        $tipo_input = 'tel';
    } elseif (strpos(strtolower($nombre), 'password') !== false || strpos(strtolower($nombre), 'contrasenya') !== false) {
        $tipo_input = 'password';
    } elseif (strpos(strtolower($nombre), 'fecha') !== false && strpos(strtolower($nombre), 'hora') !== false) {
        $tipo_input = 'datetime-local';
    } elseif (strpos(strtolower($nombre), 'fecha') !== false) {
        $tipo_input = 'date';
    } elseif (strpos(strtolower($tipo), 'int') !== false || strpos(strtolower($nombre), 'id_') !== false) {
        $tipo_input = 'number';
    } elseif (strpos(strtolower($nombre), 'descripcion') !== false) {
        $es_textarea = true;
    }
    
    // Por tipo de dato MySQL
    if (strpos(strtolower($tipo), 'text') !== false) {
        $es_textarea = true;
    }
    
    echo '<div class="controlformulario">';
    echo '<label for="'.$nombre.'">'.$label.'</label>';
    
    if ($es_textarea) {
        echo '<textarea name="'.$nombre.'" id="'.$nombre.'" ';
        if ($columna['Null'] == 'NO' && !$columna['Default']) {
            echo 'required';
        }
        echo ' placeholder="'.$label.'"></textarea>';
    } else {
        echo '<input type="'.$tipo_input.'" name="'.$nombre.'" id="'.$nombre.'" ';
        
        // Atributos adicionales
        if ($tipo_input == 'number') {
            if (strpos(strtolower($nombre), 'id_') !== false) {
                echo 'min="1" ';
            } elseif ($nombre == 'max_miembros') {
                echo 'min="1" max="1000" value="50" ';
            }
        }
        
        if ($columna['Null'] == 'NO' && !$columna['Default']) {
            echo 'required';
        }
        
        echo ' placeholder="'.$label.'">';
    }
    
    echo '</div>';
}

$conexion->close();

echo "<div>";
echo "<input type='submit' value='crear Registros'>";
echo '<a href="../tipo_de_usuario/admin.php?tabla=<?php echo urlencode($tabla); ?>" style="margin-left:10px;">Cancelar</a>';
echo "</div>";
echo "</form>";
?>
<style>
 .controlformulario{padding:3px;width:100px;display:inline-block;}
 label{font-weight:bold;font-size:15px;}
</style>
