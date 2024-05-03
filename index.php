<?php
require_once './includes/router.php'; // Incluimos el archivo router.php que me permite crear las rutas hacia la API
require_once './includes/db.php'; // Incluimos el archivo db.php donde se encuentra la lógica de conexión
require_once './includes/ActiveRecord.php'; // Incluimos ActiveRecord.php que contiene nuestro patrón de diseño ActiveRecord, el cual permite crear un objeto con la tabla SQL que queremos, en memoria. dicho objeto se sincroniza con los cambios que realizamos, permitiendo así manejar la información.
// Establecer la codificación del archivo como UTF-8
// Establecemos la conexión a la base de datos utilizando nuestro método estático que viene dentro de ActiveRecord
ActiveRecord::setDB(Database::getConnection());

// Instanciamos un nuevo objeto de tipo router
$router = new Router();

// Definimos las rutas que utilizaremos

/*
    -----------------------  RUTAS PARA LA TABLA ORDEN -------------------------
*/


$router->get('/obtener-orden/(\d+)', 'OrdenController@obtenerOrdenPorId'); 
$router->post('/crear-orden', 'OrdenController@crearOrden'); 
$router->put('/actualizar-orden/(\d+)', 'OrdenController@actualizarOrdenPorId'); 
$router->delete('/eliminar-orden/(\d+)', 'OrdenController@eliminarOrdenPorId'); 


// Ejecutamos nuestro enrutador
$router->comprobarRutas();
