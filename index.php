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
$router->get('/obtenerinforeuniones', 'LoginController@obtenerReuniones', ['middleware' => 'AuthMiddleware']);


/*
    -----------------------  RUTAS PARA LA TABLA USUARIOS (CREAR PERSONAS) -------------------------
*/
$router->get('/crear-persona', 'PersonaController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-persona', 'PersonaController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obtenerpersonas', 'PersonaController@obtenerPersonas', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizarpersonas', 'PersonaController@actualizarPersona', ['middleware' => 'AuthMiddleware']);



/*
    -----------------------  RUTAS PARA LA TABLA REUNION -------------------------
*/
$router->get('/crear-reunion', 'ReunionController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-reunion', 'ReunionController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obtenerreuniones', 'ReunionController@obtenerReuniones', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizarreunion', 'ReunionController@actualizarReunion', ['middleware' => 'AuthMiddleware']);

/*
    -----------------------  RUTAS PARA LA TABLA ASISTENTES -------------------------
*/
$router->get('/crear-asistente', 'AsistenteController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-asistente', 'AsistenteController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obtenerasistentes', 'AsistenteController@obtenerAsistentes', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizarasistente', 'AsistenteController@actualizarAsistente', ['middleware' => 'AuthMiddleware']);

/*
    -----------------------  RUTAS PARA LA TABLA ACTAS -------------------------
*/
$router->get('/crear-acta', 'ActaController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-acta', 'ActaController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obteneractas', 'ActaController@obtenerActas', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizaracta', 'ActaController@actualizarActa', ['middleware' => 'AuthMiddleware']);

/*
    -----------------------  RUTAS PARA LA TABLA COMPROMISOS -------------------------
*/
$router->get('/crear-compromiso', 'CompromisoController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-compromiso', 'CompromisoController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obtenercompromisos', 'CompromisoController@obtenerCompromisos', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizarcompromiso', 'CompromisoController@actualizarCompromiso', ['middleware' => 'AuthMiddleware']);



// Ejecutamos nuestro enrutador
$router->comprobarRutas();
