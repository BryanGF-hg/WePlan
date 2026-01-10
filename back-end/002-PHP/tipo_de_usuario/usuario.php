<?php
session_start();
$conn = new mysqli('localhost', 'usuario-weplan', 'Usuarioweplan123$', 'WePlanDB');
                                      // Verificador de log-in de usuario
if (!isset($_SESSION['usuario_id'])) {
                                      // Redirigir al login si no hay sesión activa
    header("Location: ../004-login.php"); // Ajusta la ruta CUANDO SE CAMBIE DE DIRECTORIO
    exit();}


//Obtenemos datos del usuario dentro de la tabla "usuario"
$usuario_id = intval($_SESSION['usuario_id']);
$sql_usuario = "SELECT nombre, pais FROM usuarios WHERE usuario_id = $usuario_id";
$resultado_usuario = $conn->query($sql_usuario);

if ($resultado_usuario->num_rows > 0) {
    $usuario = $resultado_usuario->fetch_assoc();
    $nombre = $usuario['nombre'];
    $pais = $usuario['pais'];
} else {
    $nombre = "Usuario";
    $pais = "Desconocido";
}

//Obtenemos datos de los grupos dentro de la tabla "grupos" para mostraserlo al usuario
$sql_grupos = "
    SELECT g.grupo_id, g.nombre AS grupo_nombre, g.descripcion, 
           COUNT(ug.usuario_id) AS miembros
    FROM grupos g
    INNER JOIN usuario_grupo ug ON g.grupo_id = ug.grupo_id
    WHERE ug.usuario_id = $usuario_id
    GROUP BY g.grupo_id
    LIMIT 5
";
$resultado_grupos = $conn->query($sql_grupos);

//Obtenemos datos de los grupos dentro de la tabla "eventos" para mostraserlo al usuario

$sql_eventos = "
    SELECT 
        e.evento_id,
        e.titulo,
        e.fecha_hora,
        e.lugar,
        s.nombre AS actividad,
        s.descripcion AS descripcion_actividad,
        g.nombre AS grupo_nombre,
        u.nombre AS creador
    FROM eventos e
    LEFT JOIN subcategorias_actividades s ON e.subcategoria_id = s.subcategoria_id
    LEFT JOIN grupos g ON e.grupo_id = g.grupo_id
    LEFT JOIN usuarios u ON g.creador_id = u.usuario_id
    WHERE 1=1
";

$sql_eventos .= " ORDER BY e.fecha_hora ASC LIMIT 5";

$resultado_eventos = $conn->query($sql_eventos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WePlan</title>
    <link rel="icon" type="image/png" href="https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/favicon-weplan.png?raw=true">
    
    <link rel="stylesheet" type="text/css" href="./usuario/estilos.css">
</head>
<body>
 <nav>
     <div class="div2">
      <img src="https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/weplan-high-resolution-logo-grayscale.png?raw=true" alt="WePlan Logo">
      
      <div class="search-container">
             <input type="text" class="search-box" placeholder="Buscar...">
      </div>
      <!--- A mejorar despues de mejora v0.1.3------->
      
            <div class="bottons-container">
             <button class="boton">
                 <a class="icono" href="/home.php">🏠</a>
             </button>
                 <! ----------------------------------- CAMBIAR CUANDO SE COLOQUE EL ARCHIVO EN OTRO LADO ---------------------------------->
             <button class="boton">
                 <a class="icono" href="/actividades.php">🧗</a>
             </button>
                 <! ----------------------------------- CAMBIAR CUANDO SE COLOQUE EL ARCHIVO EN OTRO LADO ---------------------------------->            
             <button class="boton">
                 <a class="icono" href="/notificaciones.php">🔔</a>
             </button>
                 <! ----------------------------------- CAMBIAR CUANDO SE COLOQUE EL ARCHIVO EN OTRO LADO ---------------------------------->
              <button class="boton-usuario">
                 <a style="text-decoration:none; color:inherit;" href="usuario.php">

                         <?php                                               // Mostrar primera letra del nombre en mayúscula
        echo strtoupper(substr($nombre, 0, 1)); ?>
                 </a>
              </button>
              <button class="boton">
               <a href="../006-logout.php" id="boton-logout">Logout </a>              
              </button>
                 <! ----------------------------------- CAMBIAR CUANDO SE COLOQUE EL ARCHIVO EN OTRO LADO ---------------------------------->              
             </div>
     </div>   
 </nav>
    <div class="parent">

        <div class="div4">
        <div class="titulo-inicio">INICIO</div>
        
        <div class="usuario-container">
            <div class="foto-usuario" id="fotoUsuario">
        <?php                                               // Mostrar primera letra del nombre en mayúscula
        echo strtoupper(substr($nombre, 0, 1)); ?>
            </div>
            
            <div class="nombre-usuario" id="nombreUsuario">
             <?php                                         // Mostrar nombre y país
        echo htmlspecialchars($nombre) . " | " . htmlspecialchars($pais)
             ?>
            </div>
        </div>
        
    </div>
    <h3> Tus grupos</h3>
     <div class="actividades-container"> 
     <!------------------------------------------------------------------------------------------->
     <!------------------------ CONTENIDO PHP PARA GENERAR ACTIVIDADES DEL USUARIO --------------->
     <!------------------------------------------------------------------------------------------->
    <?php
    if ($resultado_grupos && $resultado_grupos->num_rows > 0) {
        // Array de imágenes por tipo de grupo (puedes personalizarlo)
        $imagenes_grupos = [
            'Fútbol' => 'https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/Football7Valencia.jpg?raw=true',
            'Café' => 'https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/coffee%20and%20chill.jpg?raw=true',
            'Pádel' => 'https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/padel.jpg?raw=true',
            'Conciertos' => 'https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/concierto.jpg?raw=true',
            'Running' => 'https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/running.jpg?raw=true',
            'Idiomas' => 'https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/idiomas.jpg?raw=true'
        ];
        
        $default_image = 'https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/default-group.jpg?raw=true'; // Cambiar si queremos mas diseño visual
        
        while($grupo = $resultado_grupos->fetch_assoc()) {
            // Determinar imagen según el nombre del grupo
            $imagen = $default_image;
            foreach($imagenes_grupos as $key => $url) {
                if (stripos($grupo['grupo_nombre'], $key) !== false) {
                    $imagen = $url;
                    break;
                }
            }
            
            // Mostrar cada grupo
            echo '<div class="actividad">';
            echo '  <div class="imagen-actividad">';
            echo '    <img src="' . $imagen . '" alt="' . htmlspecialchars($grupo['grupo_nombre']) . '">';
            echo '  </div>';
            echo '  <div class="descripcion-actividad">';
            echo '    <h3>' . htmlspecialchars($grupo['grupo_nombre']) . '</h3>';
            echo '    <p>⭐⭐⭐⭐⭐ 5.0</p>';
            echo '    <p>📌 ' . htmlspecialchars($pais) . '</p>';
            echo '    <p>👥 ' . $grupo['miembros'] . ' Miembros - Público</p>';
            echo '  </div>';
            echo '</div>';
        }
    } else {
        echo '<p>No perteneces a ningún grupo todavía.</p>';
    }
    ?> 
     </div>
        <!-- Div10 -  btn-grupo -->
     <div class="div10">
        <button class="btn-grupo"><img src="fotos/crear_grupo_emoji.png">Crear Nuevo Grupo</button>
    </div>
    <div>
    <h3> Tus actividades</h3>
     <div class="actividades-container"> 
        <?php
        if ($resultado_eventos && $resultado_eventos->num_rows > 0) {
            // Array de imágenes por tipo de actividad
            $imagenes_actividades = [
                'Fútbol' => 'https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/Football7Valencia.jpg?raw=true',
                'Pádel' => 'https://images.unsplash.com/photo-1595435934247-5d33b7f92c70?w=400&h=300&fit=crop',
                'Running' => 'https://images.unsplash.com/photo-1552674605-db6ffd8facb5?w=400&h=300&fit=crop',
                'Café' => 'https://github.com/BryanGF-hg/WePlan/blob/main/Front-end/Pagina%20de%20inicio/fotos/coffee%20and%20chill.jpg?raw=true',
                'Concierto' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400&h=300&fit=crop',
                'Idiomas' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=300&fit=crop',
                'Senderismo' => 'https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=400&h=300&fit=crop',
                'Yoga' => 'https://images.unsplash.com/photo-1562771379-eafdca7a02f8?w=400&h=300&fit=crop',
                'Cocina' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop',
                'Bicicleta' => 'https://images.unsplash.com/photo-1536922246289-88c42f957773?w=400&h=300&fit=crop',
                'Pintura' => 'https://images.unsplash.com/photo-1518609878373-06d740f60d8b?w=400&h=300&fit=crop'
            ];
            
            $default_image = 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400&h=300&fit=crop';
            
            while($evento = $resultado_eventos->fetch_assoc()) {
                // Determinar imagen según tipo de actividad
                $imagen = $default_image;
                $actividad_nombre = $evento['actividad'] ?? $evento['titulo'];
                
                foreach($imagenes_actividades as $palabra_clave => $url) {
                    if (stripos($actividad_nombre, $palabra_clave) !== false || 
                        stripos($evento['titulo'], $palabra_clave) !== false) {
                        $imagen = $url;
                        break;
                    }
                }
                
                // Formatear fecha
                $fecha_formateada = date('d/m/Y H:i', strtotime($evento['fecha_hora']));
                
                // Mostrar cada actividad
                echo '<div class="actividad">';
                echo '  <div class="imagen-actividad">';
                echo '    <img src="' . $imagen . '" alt="' . htmlspecialchars($evento['titulo']) . '">';
                echo '  </div>';
                echo '  <div class="descripcion-actividad">';
                echo '    <h3>' . htmlspecialchars($evento['titulo']) . '</h3>';
                
                if (!empty($evento['actividad'])) {
                    echo '    <p><strong>Tipo:</strong> ' . htmlspecialchars($evento['actividad']) . '</p>';                }
                
                if (!empty($evento['descripcion_actividad'])) {
                    echo '    <p>' . htmlspecialchars($evento['descripcion_actividad']) . '</p>';
                } else {
                    echo '    <p>Actividad organizada por el grupo.</p>';
                }
                
                echo '    <p><strong>📅 Fecha:</strong> ' . $fecha_formateada . '</p>';
                
                if (!empty($evento['lugar'])) {
                    echo '    <p><strong>📍 Lugar:</strong> ' . htmlspecialchars($evento['lugar']) . '</p>';                }
                
                if (!empty($evento['grupo_nombre'])) {
                    echo '    <p><strong>👥 Grupo:</strong> ' . htmlspecialchars($evento['grupo_nombre']) . '</p>';                }
                
                echo '  </div>';
                echo '</div>';
            }
        } else {
            echo '<div class="sin-actividades">';
            echo '  <p>No tienes actividades próximas.</p>';
            echo '  <p><a href="actividades.php">Explora actividades disponibles</a></p>';            // Cambiar mas tarde
            echo '</div>';
        }
        ?>
     </div>   
<?php $conn->close(); ?>    
</body>
</html>

