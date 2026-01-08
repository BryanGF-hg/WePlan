WePlanDB:Usuario (Usuario que puede Leer las tablas; Puede Crear Actividades) ->
 Configuración -> 
 {
  [nombre, apellidos, teléfono, dirección, correo electrónico, país]
 },
 
 Grupos ->{
  [nombre,descripción,miembros]
 },
 
 Actividades ->
 {
  Deporte[Fútbol,Pádel,Baloncesto,etc],
  Relajación[Café,Charla,Cena,etc],
  Música[Conciertos,Karaoke,etc],
  Educación[Idiomas,Artes,Danza,etc]
 },
 
 Aficiones[]
 
WePlanDB:Administrador de grupos (usuario que puede Leer y ACTUALIZAR las tablas; Puede Crear Actividades) -> 
 Grupos ->{
  [nombre,descripción,miembros]
 }
 
WePlan:Administrador (usuario que puede Crear,Leer,ACTUALIZAR y ELIMINAR las tablas) [nosotros]


TABLAS CREADAS:
 Importantes:
  -usuarios
  -grupos
  -categorias_actividades
  -subcategorias_actividades
  -aficiones
  -eventos
  -evento_participantes
 Relaciones:
  -usuario_grupo
  -usuario_aficion
  -grupo_subcategorias

INDICES CREADOS:
 Indice para busqueda frecuentes:
  idx_usuarios_email
  idx_usuarios_nombre
  idx_grupos_nombre
  idx_eventos_fecha
  idx_eventos_grupo
 
 Indices para relaciones: 
  idx_usuarios_grupo_usuario
  idx_usuarios_grupo_grupo

VISTAS CREADAS:
 vista_grupo_con_miembros
 vista_eventos_proximos
 vista_usuario_aficiones
