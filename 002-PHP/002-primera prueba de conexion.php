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
