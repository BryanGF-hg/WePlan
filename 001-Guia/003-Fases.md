Descripción:
La idea es crear una aplicación en la que las personas puedan buscar la categoría o el tipo de actividad que desean realizar y unirse a otras personas, y también puedan crear actividades a partir de sus propias ideas y publicarlas para que otras personas se unan a ellas.
No hay límites para las ideas: puede tratarse de quedar para tomar algo o viajar a Marruecos para escalar el Toubkal, y la persona que crea la actividad puede establecer un presupuesto para la misma y encargarse de todo, o puede ser de gasto libre, en cuyo caso solo publica la idea y los demás pueden gastar lo que quieran.

Fase 1: Análisis
Requerimientos funcionales:
Buscador de categorías y actividades
Barra de selección de presupuesto

Requirimientos no funcionales:
Debe ser posible visualizarlo en dispositivos móviles o cualquier navegador
Debe ser fácil de entender y usar

Herramientas a usar:
Frontend: HTML,CSS, PHP
Backend: PHP-Python
Base de Datos: MySQL

Funcionalidad de cada página:
 5 paginas= Home, Explorar, Notificaciones, Mensajes, Actividades 
 
  Para Home, tendrá un nav-bar que contenga un buscador [usando PHP]
  Para Actividades, que haya un boton de categoria "Tiempo": [Hoy,Mañana,Fin de Semana, Próximos]
  
Diseño de la Base de Datos:

CREATE TABLE Usuario{
 id INT AUTO_INCREMENT PRIMARY KEY,
 direccion TEXT,
 email VARCHAR(255),
 pais TEXT,
 usuario_id INT
};

Fase 2: Diseño
Diseño de la Interfaz de Usuario:

*~A desarrollar~*
  
