# Isla Transfers

Configuración inicial para arrancar la aplicación:

  Paso 1. En la terminal instalar composer dentro de la carpeta app <b>composer install</b> --> Crea   vendor/  composer
                                                                                                         phpmailer 
  Paso 2. Inicializar el contenedor docker  <b>docker-compose up -d --build</b> --> Hace correr un stack de 3 contendores 

  Paso 3. Se aconseja restaurar la base de datos <b>mydb_transfers.dump</b> en tu administrador de base de datos local. Algunas tablas deben tener valores previos para el optimo funcionamiento de la aplicación
