<?php
// Validar tabla
if (!isset($_POST['tabla']) || empty($_POST['tabla'])) {
    die("Error: No se especificó la tabla");
}
$tabla = $_POST['tabla'];

$host = "localhost";
$user = "usuario-weplan";
$pass = "Usuarioweplan123$";
$db   = "WePlanDB";
$conexion = new mysqli($host, $user, $pass, $db);

// Validar que tabla existe
$tabla_valida = $conexion->query("SHOW TABLES LIKE '$tabla'");
if ($tabla_valida->num_rows == 0) {
    die("Error: La tabla '$tabla' no existe");
}

// Obtener columnas de la tabla
$resultado = $conexion->query("SHOW COLUMNS FROM `$tabla`");
$columnas_validas = [];

while ($columna = $resultado->fetch_assoc()) {
    $columnas_validas[] = $columna['Field'];
}

// Preparación de datos para INSERT
$campos = [];
$valores = [];

foreach ($_POST as $campo => $valor) {
    if ($campo == 'tabla') continue;
    
    // Solo incluir campos que existen en la tabla
    if (in_array($campo, $columnas_validas)) {
        $campos[] = "`$campo`";
        $valores[] = "'" . $conexion->real_escape_string($valor) . "'";
    }
}

//Consulta SQL de CREATE
$sql = "INSERT INTO `$tabla` (" . implode(", ", $campos) . ") 
        VALUES (" . implode(", ", $valores) . ")";

// Ejecuta
if ($conexion->query($sql)) {
    echo "Registro creado en '$tabla'<br>";
} else {
    echo "Error: " . $conexion->error . "<br>";
}

$conexion->close();
header("Location: ../../admin.php");												// Volvemos a admin
?>

  
