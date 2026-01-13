"Buscador": Que permita buscar grupos y actividades





"Usuarios similares a ti": Comparando usuario_aficion

"Eventos recomendados": Basado en grupos a los que perteneces (usuario_grupo) y sus eventos (eventos)

"Grupos de tu zona": Usando usuarios.direccion y grupos con eventos locales

"Próximos eventos": Consultando eventos.fecha_hora y evento_participantes

"Actividades populares": Contando participantes en eventos por subcategorias_actividades


-- Página de inicio: Mostrar grupos populares
SELECT * FROM vista_grupos_con_miembros 
ORDER BY miembros DESC 
LIMIT 10;

-- Calendario en notificaciones.html: Próximos eventos
SELECT * FROM vista_eventos_proximos 
WHERE fecha_hora BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY);

-- Perfil de usuario en usuario.php: Mostrar sus intereses
SELECT aficiones FROM vista_usuario_aficiones 
WHERE usuario_id = 123;

-- Buscar compañeros por aficiones en home.php:
SELECT * FROM vista_usuario_aficiones 
WHERE aficiones LIKE '%senderismo%';

# USUARIO.PHP:

Opciones posibles:
Listado de todos los usuarios (como un directorio)

Búsqueda/filtro de usuarios por nombre, país, aficiones

Perfil público de usuario (al hacer clic en un usuario)

Gestión de usuarios (solo para administradores)

Sistema de amigos/conexiones

Recomendación de usuarios con intereses similares

Basándome en tu base de datos, podría incluir:
Nombre, país, fecha de registro

Grupos a los que pertenece

Aficiones/intereses

Eventos a los que asiste

Tipo de usuario (admin/host/usuario)

# BBDD:

-- Vista para todos los eventos
CREATE VIEW vista_eventos_completa AS
SELECT 
    e.evento_id,
    e.titulo,
    e.fecha_hora,
    e.lugar,
    e.grupo_id,
    e.subcategoria_id,
    s.nombre AS actividad,
    s.descripcion AS descripcion_actividad,
    s.categoria_id,
    c.nombre AS categoria,
    g.nombre AS grupo_nombre,
    g.descripcion AS grupo_descripcion,
    g.creador_id,
    u.nombre AS creador,
    u.pais AS creador_pais,
    -- Contar participantes
    (SELECT COUNT(*) FROM evento_participantes ep WHERE ep.evento_id = e.evento_id) AS participantes
FROM eventos e
LEFT JOIN subcategorias_actividades s ON e.subcategoria_id = s.subcategoria_id
LEFT JOIN categorias_actividades c ON s.categoria_id = c.categoria_id
LEFT JOIN grupos g ON e.grupo_id = g.grupo_id
LEFT JOIN usuarios u ON g.creador_id = u.usuario_id;

1. Para eventos del usuario:
php
$sql_eventos_usuario = "
    SELECT v.* 
    FROM vista_eventos_completa v
    INNER JOIN evento_participantes ep ON v.evento_id = ep.evento_id
    WHERE ep.usuario_id = $usuario_id
    AND v.fecha_hora >= NOW()
    ORDER BY v.fecha_hora ASC
    LIMIT 5
";
2. Para eventos próximos (todos):
php
$sql_eventos_proximos = "
    SELECT * FROM vista_eventos_completa 
    WHERE fecha_hora >= NOW()
    ORDER BY fecha_hora ASC
    LIMIT 10
";
