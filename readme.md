\[API\] Like it!
=======================
#### Photos like you've never seen before

----------------------------------------

### Framework

El framework que estamos usando es [Epiphany](https://github.com/jmathai/epiphany) el cual provee una serie de herramientas para la creación de servidores RESTful. La curva de aprendizaje es mínima, mientras que la flexibilidad y claridad que provee es extremadamente valiosa a la hora de hacer un servicio REST.

---------------------------------------- 

### Estructura

La estructura de directorios es la siguiente:

- **api** nuestra applicación
- - **config** archivos de configuración
- - **controller** controlador para nuestras rutas
- - **data** modelos de base de datos y caché
- - **libraries** librerías de nuestra applicación
- - **locale** archivos de idioma
- **http** folder público
- **vendors** librerías de terceros
- - **epi** epiphany framework

---------------------------------------- 

### Git Ignore

El contenido de las siguientes rutas deberá mantenerse fuera del repositorio:

- **/api/config/secure.ini** variables de configuración
- **/http/secure** directorio para pruebas o admin del servidor http

---------------------------------------- 