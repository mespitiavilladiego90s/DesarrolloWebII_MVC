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
    -----------------------  RUTAS PARA LA TABLA VENDEDOR -------------------------
*/


$router->get('/obtener-vendedor/(\d+)', 'VendedorController@obtenerVendedorPorId'); 
$router->post('/crear-vendedor', 'VendedorController@crearVendedor'); 
$router->put('/actualizar-vendedor/(\d+)', 'VendedorController@actualizarVendedorPorId'); 
$router->delete('/eliminar-vendedor/(\d+)', 'VendedorController@eliminarVendedorPorId'); 


/*
    --------------------------------------------------------------------------------
*/

/*
    -----------------------  RUTAS PARA LA TABLA CLIENTE -------------------------
*/


$router->get('/obtener-cliente/(\d+)', 'ClienteController@obtenerClientePorId'); 
$router->post('/crear-cliente', 'ClienteController@crearCliente'); 
$router->put('/actualizar-cliente/(\d+)', 'ClienteController@actualizarClientePorId'); 
$router->delete('/eliminar-cliente/(\d+)', 'ClienteController@eliminarClientePorId'); 


/*
    --------------------------------------------------------------------------------
*/

/*
    -----------------------  RUTAS PARA LA TABLA ORDENGASOLINA -------------------------
*/


$router->get('/obtener-orden/(\d+)', 'OrdenController@obtenerOrdenPorId'); 
$router->post('/crear-orden', 'OrdenController@crearOrden'); 
$router->put('/actualizar-orden/(\d+)', 'OrdenController@actualizarOrdenPorId'); 
$router->delete('/eliminar-orden/(\d+)', 'OrdenController@eliminarOrdenPorId'); 


/*
    --------------------------------------------------------------------------------
*/


/*
    -----------------------  RUTAS PARA LA TABLA FACTURA -------------------------
*/


$router->get('/obtener-factura/(\d+)', 'FacturaController@obtenerFacturaPorId'); 
$router->post('/crear-factura', 'FacturaController@crearFactura'); 
$router->put('/actualizar-factura/(\d+)', 'FacturaController@actualizarFacturaPorId'); 
$router->delete('/eliminar-factura/(\d+)', 'FacturaController@eliminarFacturaPorId'); 


/*
    --------------------------------------------------------------------------------
*/

/*
    -----------------------  RUTAS PARA LAS 2 CONSULTAS RESTANTES  -------------------------
*/


$router->get('/obtener-datos-cvo/(\d+)', 'APIController@obtenerClienteVendedorOrdenPorIdFactura'); 
$router->get('/obtener-datos-fao/(\d+)', 'APIController@obtenerFacturasPorOrdenPorIdOrden'); 




// Ejecutamos nuestro enrutador
$router->comprobarRutas();
