# Isla Transfers
App Web sobre gestión de transfers desarrollada por PHPower(UOCX4).

PREV. 

  Paso 1. En la terminal instalar composer dentro de la carpeta app composer install --> Crea   vendor/  composer
                                                                                                         phpmailer 

  Paso 2. Inicializar el contenedor docker  docker-compose up -d --build --> Hace correr un stack de 3 contendores 

  Paso 3. Se aconseja restaurar la base de datos mydb_transfers.dump en tu administrador de base de datos local. Algunas tablas deben tener valores previos para el optimo funcionamiento de la aplicación


1. Introducción

  Este proyecto consiste en el desarrollo de una aplicación web para la reserva y gestión de transfers entre el aeropuerto y los hoteles de una isla. La aplicación está diseñada para facilitar la coordinación de transfers, mejorando la experiencia tanto para los clientes particulares como para la administración de Isla Transfers.

  Para el entorno de servidor se usa PHP puro (sin frameworks) junto con MySQL como base de datos. En el front-end, se emplean HTML, CSS (utilizando Bootstrap 5) y JavaScript. La organización del proyecto es colaborativa, empleando Trello para la gestión de tareas y GitHub para el control de versiones y la colaboración en el código.

2. Descripción

  La aplicación maneja tres paneles distintos según el tipo de usuario en la sesión: administradores, clientes particulares (viajeros) y clientes corporativos (hoteles). 

    2.1 Administradores 
      Los administradores tienen un panel de gestión completo desde el cual pueden:
        - Gestionar reservas y asignar transfers.
        - Gestionar la flota de vehículos y asignar conductores.
        - Ver todas las reservas en un calendario interactivo y manejar solicitudes en tiempo real.

    2.2  Viajeros 
      Los viajeros pueden:
        - Crear, modificar y eliminar reservas (con un límite de 48 horas de antelación).
        - Ver sus reservas en un calendario, distinguiendo entre las reservas que han realizado ellos mismos y las que han sido gestionadas por el      administrador.
        - Modificar sus datos de perfil.

    2.3 Hoteles 
       
      El panel de hoteles aún no tiene funcionalidad activa, pero permite a los clientes corporativos loguearse. Se prevé un desarrollo futuro en el que los hoteles podrán gestionar las reservas de sus clientes directamente desde su panel.
    
3. Ambiente de desarrollo y configuración
  3.1 Herramientas 
    - Docker: Empleado para crear un entorno de servidor completo con Apache, MySQL y PHPMyAdmin, facilitando el despliegue y pruebas de la aplicación en diferentes entornos.

    - Git y GitHub: Para el control de versiones. El proyecto se organiza en un repositorio remoto en GitHub, que permite la colaboración y el control de versiones mediante ramas de desarrollo.

    - Visual Studio Code (VS Code): Seleccionado como el IDE para el desarrollo completo de la aplicación, con extensiones de PHP que facilitan la codificación y depuración.

   3.2 Planificación 
    El equipo usa Trello para organizar y planificar el desarrollo mediante un tablero Kanban, lo cual permite asignar tareas y roles a cada miembro del equipo. Las tareas se dividen en subtareas y se asignan con responsabilidad clara para asegurar un flujo de trabajo ordenado y eficiente.
    
  3.3 Repositorio
    El repositorio del proyecto está alojado en GitHub. Cada funcionalidad se desarrolla en ramas independientes, las cuales se combinan en una rama principal al final del proyecto para consolidar el producto final.

  3.4 Entorno de desarrollo PHP
    En el entorno de desarrollo se han configurado extensiones en VS Code que incluyen soporte para PHP, lo que permite una experiencia de desarrollo optimizada.

4. Estructura del proyecto

  4.1 Estructura de carpetas
    La estructura de carpetas está organizada para facilitar la separación entre el front-end y el back-end, y sigue la organización Modelo-Vista-Controlador (MVC).

  4.2 DDBB
    La base de datos fue diseñada cuidadosamente para soportar el flujo de trabajo de Isla Transfers, con tablas específicas para gestionar reservas, usuarios, hoteles y vehículos, asegurando una organización eficiente de la información. Durante el desarrollo de la aplicación, se realizaron ajustes en algunos campos para optimizar la inserción y recuperación de datos, mejorando la eficiencia y precisión en el manejo de información.
  
  La estructura final de la base de datos, así como las relaciones entre las tablas, están completamente documentadas para facilitar tanto la implementación inicial como el mantenimiento continuo. Esta documentación proporciona una referencia clara para futuras expansiones y asegura la escalabilidad del sistema conforme crezca la demanda.  

  4.3 Tecnologías usadas
    - Bootstrap 5: Para una interfaz responsiva y moderna, con componentes de UI consistentes.
    - FullCalendar: Permite una visualización de calendario interactiva, que facilita la visualización y gestión de las reservas.
    - PHPMailer: Para el envío de correos electrónicos automáticos, como confirmaciones de reserva, recordatorios y notificaciones.

5. Seguridad y Gestión de Acceso

  5.1 Autenticación de Usuarios 
  Descripción del sistema de autenticación, especificando cómo se protege el acceso mediante contraseñas hasheadas, sesiones y validaciones de entrada para prevenir ataques de fuerza bruta o SQL injection.

  5.2 Roles y Permisos 
  Explicación de los permisos específicos para cada tipo de usuario (administrador, viajero, hotel), y cómo el sistema garantiza que los usuarios acceden solo a las funciones que les corresponden.

6. Implementación y Despliegue

  6.1 Proceso de Despliegue 
  Descripción de cómo se despliega la aplicación en el servidor (pasos específicos, servidores utilizados, etc.).

  6.2 Consideraciones de Hosting: 
  Especificar si la aplicación está diseñada para ser hospedada en un servidor compartido, VPS o en la nube, y cómo se configuraron los permisos y recursos en el servidor.


