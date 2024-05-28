<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once './includes/router.php'; 
require_once './includes/db.php'; 
require_once './includes/ActiveRecord.php';
require_once './includes/funciones.php';
require_once './classes/JWT.php';
require_once './classes/AuthMiddleware.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

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



// Registra el middleware para la ruta protegida
$router->get('/index', 'LoginController@index', ['middleware' => 'AuthMiddleware']);



/*
    -----------------------  RUTAS PARA LA TABLA ACTAS -------------------------
*/
$router->get('/crear-reunion', 'ReunionController@crear');
$router->post('/crear-reunion', 'ReunionController@crear');
$router->get('/obtenerreuniones', 'ReunionController@obtenerReuniones');
$router->put('/actualizarreunion', 'ReunionController@actualizarReunion');


// Ejecutamos nuestro enrutador
$router->comprobarRutas();
