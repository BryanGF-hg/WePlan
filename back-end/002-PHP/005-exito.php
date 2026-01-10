<?php 
 session_start();
  if(!isset($_SESSION['usuario'])){
     die("Te has colado, pero no te has colado exitosamente");
  }
?>

<html lang="es">
<body>
 <main>
  <p>Si estás viendo, eso significa que tu correo y contraseña son correctos pero tu tipo de usuario no esta definido
  <br><b>Contacta a un administrador inmediatamente, no dudes en escribirnos!</b>
  </p>
 </main>
</body>
</html>

