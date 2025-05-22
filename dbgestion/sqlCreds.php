<?php
// Configuración para MySQL local
define('DBHOST', 'localhost');
define('DBNAME', 'tienda_online');
define('DBUSER', 'sie');
define('DBPWD',  'siepwd');

/* El usuario lo tenéis que crear en mysql con esa contrasena y 
darle privilegios en la base de datos para que deje conectarse y crear tablas:

sudo mysql -u root -p

CREATE USER 'admin_tienda'@'localhost' IDENTIFIED BY 'una_contraseña_muy_segura';

GRANT ALL PRIVILEGES ON tienda_online.* TO 'admin_tienda'@'localhost';

FLUSH PRIVILEGES;
*/