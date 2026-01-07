<!doctype html>
<html lang="es">
	<head>
  	<style>
    	html,body{width:100%;height:100%;padding:0px;margin:0px;}
    	body{display:flex;}
    	nav{background:#FF511C;padding:20px;gap:20px;display:flex;flex:1;flex-direction:column;height:max-content;}
    	nav a{background:#FFFFFF;color:#39D0BD;text-decoration:none;padding:10px;}
    	main{padding:20px;flex:4;}
     table td{padding:10px;}
     table{border:2px solid #39D0BD;width:100%;}   
     th{background:#FF511C;color:white;padding:10px;}
     
     #crear{	position:absolute;  bottom:20px;  right:20px;  background:#39D0BD;  color:#FF511C; 
	      height:40px; border-radius:40px;
       text-align:center;  font-size:30px;  line-height:40px;  text-decoration:none;  font-weight:bold;}
     .eliminar,.editar{	height:20px;  background:#39D0BD;  border-radius:30px;  color:#FF511C;
       line-height:20px;  text-decoration:none; text-align:center;  display:block;  }  
     #botones{color:#FFEAD4;border-radius:30px;display:flex;align-items:center;background:#40e0d0;}
    </style>
  </head>
  <body>
    <?php
        $host = "localhost";$user = "usuario-weplan";$pass = "Usuarioweplan123$";$db   = "WePlanDB";$conexion = new mysqli($host, $user, $pass, $db);
    ?>

    <nav>
     <div id="botones">
      <h2>admin.php </h2>
      <a href="./admin/read/leer.php" id="read-btn">Lectura</a>                                 <!-- accion leer (READ) -->
      <a href="../006-logout.php" class="logout-btn" id="boton-logout">Cerrar Sesión</a>
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
           echo "<table>";                         // Inicio de tabl
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
