<?php 
 session_start();
  if(!isset($_SESSION['usuario'])){
     die("Te has colado, pero no te has colado exitosamente");
  }
?>

<html lang="es">
<body>
 <main>
  <p>Un día con más PHP (tristemente), y mañana no PHP</p>
 </main>
</body>
</html>

