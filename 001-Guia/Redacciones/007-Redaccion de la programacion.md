# 1.- Controladores:
## Plantilla de conexion (000-primera prueba de conexion.php):
 Primero nos intentamos a la conectar a la base de datos usando nuestro usuario creado previamente y permitimos que se conecte y muestre usando HTML cada fila de la tabla "usuarios". Recordamos cerrar conexión al terminar el script

```
<?php
 $host = "localhost";
 $user = "usuario-weplan";
 $pass = "Usuarioweplan123$";
 $db   = "WePlanDB";

 $conexion = new mysqli($host, $user, $pass, $db);
 $sql = "SELECT * FROM usuarios";
 $resultado = $conexion->query($sql);

 while ($fila = $resultado->fetch_assoc()) {
   echo '
     <article>
      <!-- Orden de las filas: nombre,apellidos,telefono,direccion,correo_electronico,pais,contrasenya,fecha_registro -->
      <h2>'.$fila['correo_electronico'].'</h2>
      <h2>'.$fila['contrasenya'].'</h2>
      <br>
      <h3>'.$fila['nombre'].'</h3>
      <p>'.$fila['apellidos'].'</p>
      <p>'.$fila['telefono'].'</p>
      <p>'.$fila['direccion'].'</p>
      <p>'.$fila['correo_electronico'].'</p>
      <p>'.$fila['pais'].'</p>
      <time>'.$fila['fecha_registro'].'</time>
     </article>
   ';
 }
 
 $conexion->close();
?>
```


# Login(004-login.php con 003-procesa_login.php):
 Con login.php, hace que muestre el formulario HTML del login que permite recoge los datos que el usuario ingresa.
 Con procesa_login.php, procesa el formulario del login y donde su flujo es: Recibe POST con email y contraseña, consulta la base de datos. Si es válido, inicia sesión y redirige; Si no, muestra un mensaje de error en HTML ya sea por la contraseña/email es largo, corto o incorrecto.
 Vistas: usuario.php, host.php y admin.php

**Con host.php muestra el dashboard del host (navegador funcional,Grupos con eventos y crear eventos,Actividades+Crear Actividades).**

**Con usuario.php, tenemos el dashboard comun donde alberga navegador funcional,Grupos con eventos,Actividades+Crear Actividades** . Empezamos con session_start(); despues de haber iniciado sesion usando el controlador 004-login.php, tambien conexion con la base de datos, extracion de datos SQL dentro de varias tablas como "usuarios","grupos","eventos",etc. Luego con estos datos, generamos el formato presentable de las tablas:
 -imagen, descripcion,pais,miembros para "grupos",
 -imagen,descripcion,evento,fecha,lugar,nombre para los eventos
 
**Con admin.php, tenemos que muestra las tablas a un lado y el contenido al otro. Tambien las herramientas CRUD para crear, leer, eliminar y actualizar un registro. Ademas un boton de logout necesario para mas seguridad.** Empezamos con un estilo CSS para el nav, tambien para las tablas como los botones. Para la estructura HTML, tenemos un body, un nav, un main que contiene las tablas. Respecto al uso de PHP, tenemos verificador de usuarios, conexion con bases de datos, un SHOW TABLES de SQL para mostrar en el nav el nombre de las tablas, verificar si se ha seleccionado una tabla para luego mostrar sus cabeceras y luego el resto de datos tambien un Redireccionador de enlaces para los botones Lectura, LogOut, Crear, Editar y Eliminar 
 
## 004-login.php:
```
<?php session_start(); ?>
<!doctype html>
<html lang="es">
  <head>
    <title>WePlan - Login</title>
    <meta charset="utf-8">
    <style>
     body,html{padding:0px;margin:0px;width:100%;height:100%;}
     body{display:flex;justify-content:center;align-items:center;text-align:center;background:BlanchedAlmond;}
     form{display:flex;flex-direction:column;width:200px;height:200px;background:#FFFFFD;padding:20px;align-items:center;gap:10px;justify-content:center;}
     input{width:100%;padding:15px;box-sizing:border-box;border:1 px solid lightgrey;}
     div{ font-size:13px;color:black;}
      #fake-footer{font-weight:bold;font-size:20px;}
      .error-message{font-weight:bold;font-size:18px;}
    </style>
  </head>
  <body>
    <main>  
      <div id="fake-header">
        <h1>Página de Login v0.1 🔐</h1>
      </div>
      <form action="003-procesa_login.php" method="POST">
        <input type="text" name="correo_electronico" placeholder="Correo electrónico">
        <input type="password" name="contrasenya" placeholder="Contraseña">
        <input type="submit">
      </form>
      <?php
      // Mostrar error de sesión si existe
      if (isset($_SESSION['error'])) {
          echo '<div class="error-message">';
          echo htmlspecialchars($_SESSION['error']);
          echo '</div>';
          unset($_SESSION['error']); // Limpiar después de mostrar
      }
      ?>        
      <div id="form-content">
        <p>📋 Requisitos:</p>
        <p>• Usuario: 5-20 caracteres</p>
        <p>• Contraseña: 8-16 caracteres</p>        
      </div>
      <div id="fake-footer">
       (c) Ayman x Bryan 2025      
      </div>
    </main>
  </body>
</html>

<?php
        // Mostrar estado de validación del correo previo
        if (!empty($correo_previo)) {
            $color_correo = validarCorreoEnTiempoReal($correo_previo);
            $clase_correo = ($color_correo === 'green') ? 'valido' : 'invalido';
            
            echo '<p class="' . $clase_correo . '">';
            echo 'Correo: ' . strlen(trim($correo_previo)) . '/20 caracteres';
            
            if (!filter_var($correo_previo, FILTER_VALIDATE_EMAIL)) {
                echo ' (Formato inválido)';
            }
            echo '</p>';
        }
?>
```

## 003-procesa_login.php:
```
<?php
  session_start();
  $correo = trim($_POST['correo_electronico'] ?? '');
  $pass = $_POST['contrasenya'] ?? '';
  
  $correo_min = 5;
  $correo_max = 30;
  $pass_min = 8;
  $pass_max = 16;
  switch(true) {
      case (strlen($correo) < $correo_min):
          $_SESSION['error'] = "El correo debe tener al menos $correo_min caracteres";      
          header("Location: ./004-login.php?error=correo_corto");
          exit(); 
          
      case (strlen($correo) > $correo_max):
          $_SESSION['error'] = "El correo debe tener menos que $correo_max caracteres";       
          header("Location: ./004-login.php?error=correo_largo");
          exit();
          
      case (strlen($pass) < $pass_min):
          $_SESSION['error'] = "La contraseña debe tener al menos $pass_min caracteres";       
          header("Location: ./004-login.php?error=pass_corto");
          exit();
          
      case (strlen($pass) > $pass_max):
          $_SESSION['error'] = "La contraseña debe tener menor que $pass_max caracteres";       
          header("Location: ./004-login.php?error=pass_largo");
          exit();       
  }
  
  $host = "localhost";$user = "usuario-weplan";$pass = "Usuarioweplan123$";$db   = "WePlanDB";
  $conexion = new mysqli($host, $user, $pass, $db);
  
	// Comprobacion exitosa mientras que se mira los datos que vienen del 004-login.php en POST
  $sql = " SELECT * FROM usuarios WHERE
    correo_electronico = '".$_POST['correo_electronico']."'
    AND contrasenya = '".$_POST['contrasenya']."';
  ";
	
  $resultado = $conexion->query($sql);

  if ($fila = $resultado->fetch_assoc()) {	 // Si es cierto que hay un resultado
    $tipo = strtolower(trim($fila['tipo_de_usuario']));
    $_SESSION['usuario'] = 'si'; 
    $_SESSION['tipo_usuario'] = $tipo;
    $_SESSION['correo_usuario'] = $_POST['correo_electronico'];
    $_SESSION['usuario_id'] = $fila['usuario_id'];
    $_SESSION['nombre'] = $fila['nombre'];    
    
    switch($tipo){                          // Dependiendo del tipo de usuario es redigirido a un sitio diferente
      case 'admin':
          header("Location: ./tipo_de_usuario/admin.php");
          break;
      case 'host':
          header("Location: ./tipo_de_usuario/host.php");
          break;
      case 'usuario':
          header("Location: ./tipo_de_usuario/usuario.php");
          break; 
      default:
          header("Location: 005-exito.php");
          break;                             
    }
    exit();
  }else{																		                  // Si no hay ningun resultado, que muestre que ha habido un error con el correo o la contraseña (porque lo compara contra la base de datos). ADEMÁS de que volvemos a login
   $_SESSION['error'] = "Correo o contraseña incorrectos";
  	header("Location: ./004-login.php");					
  	exit();
  }

  $conexion->close();
?>
``` 
 
## usuario.php: 
```
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
``` 
 
## admin.php:
```
<!doctype html>
<html lang="es">
	<head>
  	<style>
    	html,body{width:100%;height:100%;padding:0px;margin:0px;background:Aliceblue;}
    	body{display:flex;}
    	nav{background:BurlyWood;color:black;padding:20px;gap:20px;display:flex;flex:1;flex-direction:column;height:max-content;}
    	nav a{background:#FFFFFF;color:black;text-decoration:none;padding:10px;}
    	a#boton-read,a#boton-logout{padding:14px;margin:8px;} 
    	
    	main{padding:20px;flex:4;}
     table td{padding:10px;}
     table{border:2px solid #39D0BD;width:100%;}   
     th{background:BurlyWood;color:black;padding:10px;}
     
     #crear{	position:absolute;  bottom:20px;  right:20px;  background:burlywood;  color:black; 
	      height:40px; border-radius:40px;
       text-align:center;  font-size:30px;  line-height:40px;  text-decoration:none;  font-weight:bold;}
     .eliminar,.editar{	height:20px;  background:BlanchedAlmond;  border-radius:30px;  color:black;
       line-height:20px;  text-decoration:none; text-align:center;  display:block;  }  
     #botones{border-radius:30px;display:flex;align-items:center;background:BlanchedAlmond;}
    </style>
  </head>
  <body>
    <?php
    //Verificador de Usuarios ///////////////////
    session_start();
    if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'si') {
    header("Location: ../004-login.php");
    exit();
    }

    // Verificar 2: ¿Es admin?
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
        // No es admin - mostrar error
        echo '<h1>Acceso Denegado</h1>';
        echo '<p>Solo los administradores pueden acceder a esta página.</p>';
        echo '<a href="../004-login.php">Volver al inicio</a>';
        exit();
    }
    ?>
    
    <?php
        $host = "localhost";$user = "usuario-weplan";$pass = "Usuarioweplan123$";$db   = "WePlanDB";$conexion = new mysqli($host, $user, $pass, $db);
    ?>

    <nav>
     <div id="botones">
      <h2>admin.php </h2>
      <a href="./admin/read/leer.php" id="boton-read">Lectura</a>                                 <!-- accion leer (READ) -->
      <a href="../006-logout.php" id="boton-logout">Logout </a>
     </div>
    <?php
      // Listado de las tablas de la base de datos- WePlanDB
        $resultado = $conexion->query("SHOW TABLES;");
        while ($fila = $resultado->fetch_assoc()) {
          echo '<a href="?tabla='.$fila['Tables_in_'.$db].'">'.$fila['Tables_in_'.$db].'</a>';
        }
    ?>
    </nav>
    <main>
   
      <?php
      // Verificar si se ha seleccionado una tabla para luego mostrar sus cabeceras
      if (isset($_GET['tabla']) && !empty($_GET['tabla'])) {
         $tabla = $conexion->real_escape_string($_GET['tabla']);

         $resultado = $conexion->query("SELECT * FROM ".$_GET['tabla']." LIMIT 1;");    /// Solo quiero un ELEMENTO !!!//////
         if ($resultado && $resultado->num_rows > 0) {
           echo "<table>";                         // Inicio de tabla
           echo "<tr>";
           $fila = $resultado->fetch_assoc();
           foreach($fila as $clave=>$valor){
             echo "<th>".$clave."</th>";
           }
           echo "<th>Editar</th><th>Eliminar</th>";
           echo "</tr>";
          }
       // Y LUEGO EL RESTO DE DATOS ////////////
         $resultado = $conexion->query("
           SELECT * FROM ".$_GET['tabla'].";
         ");
         while ($fila = $resultado->fetch_assoc()) {
           echo "<tr>";

           foreach($fila as $clave=>$valor){
             echo "<td>".htmlspecialchars($valor)."</td>";
           }
           
           $id = reset($fila);
           echo "<td><a href='?tabla=".urlencode($tabla)."&accion=editar&id=".urlencode($id)."' class='editar' title='Cuidado que vas a editar un dato'>🖋</a></td>";
           echo "<td><a href='?tabla=".urlencode($tabla)."&accion=eliminar&id=".urlencode($id)."' class='eliminar' title='Cuidado EXTREMO que vas a ELIMINAR un dato'>✖</a></td>";            
           echo "</tr>";
          }
          echo "</table>";                          // Fin de tabla
      } 
      ?>
      
     	<?php
      	                                            // Redirecionador de enlaces
      	if(isset($_GET['accion'])){
      	  $tabla_actual = isset($_GET['tabla']) ? $_GET['tabla']: '';
      	  
        	if($_GET['accion'] == "crear"){           // formulario crear (CREATE)
        	 if(!empty($tabla_actual)){
          	 include "./admin/create/formulario.php";
          	} else {
          	 echo "<p>Error, se debe seleccionar una tabla para CREAR </p>";
          	}
          	
          }else if($_GET['accion'] == "eliminar"){ // acción eliminar (DELETE)
           if(!empty($tabla_actual) && isset($_GET['id'])){
           	include "./admin/delete/eliminar.php";	
           } else {
          	 echo "<p>Error, se debe seleccionar una tabla para ELIMINAR </p>";           
           }
           
          } else if($_GET['accion'] == "editar"){   // acción actualizar (UPDATE)
           if(!empty($tabla_actual) && isset($_GET['id'])){
           	include "./admin/update/actualizar.php";	
           } else {
          	 echo "<p>Error, se debe seleccionar una tabla para ACTUALIZAR </p>";           
           }
          }                                         // Fin del condicional
          
        }                                           // Fin de conseguir acciones
        ?>
        
    <?php
     //  Muestra el boton si se ha "presionado" una tabla
     if(isset($_GET['tabla']) && !empty($_GET['tabla'])) {
        echo '<a href="?tabla='.urlencode($_GET['tabla']).'&accion=crear" id="crear">Crear</a>';
     }
    ?>
    </main>
  </body>
</html>
``` 

# 2.- Lógica de WePlan:
 Usuario inicia sesion o create una cuenta después con los controladores de actividades, grupos, login,etc permite al usuario ver un modelo que contiene su vista respectiva: para usuarios, tiene grupos+actividades+eventos; para host, tiene grupos+actividades+eventos+crear; para el admin tiene grupos+actividades+eventos+crear+leer+actualizar+eliminar.



# 3.- Flujo de la información:
Usuario --> login.php-> procesa_login.php-> (dependiendo del nivel del usuario)-> usuario.php/host.php/admin.php -> logout.php (vuelve otra vez a login.php)


Tenemos un login.php que muestra un formulario que consigue los datos mediante el metodo POST y luego en procesa_login.php, consiguemos los datos que vienen del formulario comparandolos contra la base de de datos al hacer una **consulta** tambien hemos añadido al principio condiciones de log-in ya sea que la contraseña o usuario sea mas largo o mas corto de lo permitido. Al usar los datos del formulario, hemos permitido que dependiendo del usuario, sea redirigido a usuario.php, host.php o admin.php según su nivel de acceso.

También tenemos un log-out.php para que los usuarios salgan de la aplicacion en caso de que quieran ver diferentes grupos a los recomendados o quieren crear una nueva cuenta para crear grupos, etc. Usa el session_start(); acumulado de las paginas de los usuarios, guarda esa informacion, "destruye" la sesion y redirige al login

## 006-logout.php:
```
<?php
session_start();

// Guardar información del usuario para mostrar mensaje
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';

// Destruir sesión
session_unset();
session_destroy();

// Redirigir al login con mensaje
header("Location: 004-login.php?mensaje='Has salido con exito'");
exit();
?>
```
