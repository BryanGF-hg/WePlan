import mysql.connector

conexion = mysql.connector.connect(
    host="localhost",
    user="usuario-weplan",
    password="Usuarioweplan123$",
    database="WePlanDB"
)  

cursor = conexion.cursor()
cursor.execute("SELECT*FROM usuarios;")

filas = cursor.fetchall()

print(filas) 
