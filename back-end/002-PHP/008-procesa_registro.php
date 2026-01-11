<?php
session_start();

function validarRegistro($datos) {
    $errores = [];
    
    // Validar nombre
    if (empty($datos['nombre']) || strlen(trim($datos['nombre'])) > 50) {
        $errores[] = "El nombre es requerido y debe tener máximo 50 caracteres";
    }
    
    // Validar correo
    if (empty($datos['correo_electronico']) || !filter_var($datos['correo_electronico'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico inválido";
    }
    
    // Validar nombre de usuario
    $usuario_len = strlen(trim($datos['nombre_usuario']));
    if ($usuario_len < 5 || $usuario_len > 20) {
        $errores[] = "El nombre de usuario debe tener entre 5 y 20 caracteres";
    }
    
    // Validar contraseña
    $pass_len = strlen($datos['contrasenya']);
    if ($pass_len < 8 || $pass_len > 16) {
        $errores[] = "La contraseña debe tener entre 8 y 16 caracteres";
    }
    
    // Validar que las contraseñas coincidan
    if ($datos['contrasenya'] !== $datos['confirmar_contrasenya']) {
        $errores[] = "Las contraseñas no coinciden";
    }
    
    return $errores;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = validarRegistro($_POST);
    
    if (empty($errores)) {
        // Aquí iría la lógica para guardar en la base de datos
        // Por ahora solo simulamos el éxito
        
        $_SESSION['success'] = "¡Usuario creado exitosamente!";
        
        // Redirigir al login
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = implode("<br>", $errores);
        // Redirigir de vuelta al formulario
        header("Location: crear_usuario.php");
        exit();
    }
} else {
    header("Location: crear_usuario.php");
    exit();
}
?>