<?php
require_once './includes/router.php'; // Incluimos el archivo router.php que me permite crear las rutas hacia la API
require_once './includes/db.php'; // Incluimos el archivo db.php donde se encuentra la lógica de conexión
require_once './includes/ActiveRecord.php'; // Incluimos ActiveRecord.php que contiene nuestro patrón de diseño ActiveRecord, el cual permite crear un objeto con la tabla SQL que queremos, en memoria. dicho objeto se sincroniza con los cambios que realizamos, permitiendo así manejar la información.
// Establecer la codificación del archivo como UTF-8
// Establecemos la conexión a la base de datos utilizando nuestro método estático que viene dentro de ActiveRecord
require_once './includes/funciones.php';
ActiveRecord::setDB(Database::getConnection());


// Instanciamos un nuevo objeto de tipo router
$router = new Router();

// Definimos las rutas que utilizaremos

/*
    -----------------------  RUTAS PARA LA TABLA USUARIOS -------------------------
*/

// Iniciar Sesión
$router->get('/login', 'LoginController@login'); 
$router->post('/login', 'LoginController@login'); 
$router->get('/logout', 'LoginController@logout');

// Recuperar Password
$router->get('/olvide', 'LoginController@olvide');
$router->post('/olvide', 'LoginController@olvide');
$router->get('/recuperar', 'LoginController@recuperar');
$router->post('/recuperar','LoginController@recuperar');

// Crear Cuenta
$router->get('/crear-cuenta', 'LoginController@crear');
$router->post('/crear-cuenta', 'LoginController@crear');

// Confirmar cuenta
$router->get('/confirmar-cuenta', 'LoginController@confirmar');
$router->get('/mensaje', 'LoginController@mensaje');


// Index
$router->get('/index', 'LoginController@index');


/*
    -----------------------  RUTAS PARA LA TABLA ACTAS -------------------------
*/
$router->post('/crear-acta', 'ActaController@crear');


// Ejecutamos nuestro enrutador
$router->comprobarRutas();
