Hay una jeraquía de usuarios:
 -Usuario (Puede leer y usar vistas)
 -Host    (Puede leer-crear y usar vistas. Para eliminar cosas, debe contact al administrador)
 -Admin   (Puede leer-crear-eliminar-actualizar la base de datos y usar vistas)
 
Todos los usuarios pasan por un login.html (hoy 24-12-2025, esa pagina es 004-login.html) que le rediriges a sus sitios correspondientes:
 -Usuario (redirigido a home.php)
 -Host (redirigo a home.php donde tendrá la posibilidad de ver una opción extra en el navegador a añadir en el futuro)
 -Admin (redirigido a admin.php donde le aparece un navegador lateral que contiene las cabeceras de las tablas, un boton de LECTURA y un boton de LOG-OUT
 -       tambien tiene a mano derecha el contenido delas tablas que aparecera SI le da click a unas de las cabeceras)
         


