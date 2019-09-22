<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
//Auth::routes(['register' => false]);

Route::name('user-info')->get('user/password', 'User\UserController@getPassword'); 
Route::name('user-info')->get('user/info/menu', 'User\UserController@getUserDataAndMenu'); 
Route::name('user-info')->get('user/menu', 'User\UserController@getMenu');
Route::name('user-info')->post('user/menu/add/{id}', 'User\UserController@agregarMenuUsuario');
Route::name('user-info')->delete('user/menu/{id}', 'User\UserController@borrarMenuUsuario');
Route::resource('user', 'User\UserController');
Route::resource('medico', 'Medico\MedicoController');
/********************* */


/**FACTURACION**/

Route::resource('liquidacion/entidad', 'Liquidacion\EntidadFacturaController');
Route::name('facturacion')->get('facturacion/comprobante/by/numero', 'Articulo\ArticuloController@getMovimientoByComprobanteNro');
Route::name('facturacion')->get('facturacion/comprobante/by/fecha', 'Articulo\ArticuloController@getMovimientoByComprobanteFecha'); 
Route::name('facturacion')->get('facturacion/comprobante/tipo', 'Articulo\ArticuloController@getComprobanteTipo');
Route::name('facturacion')->post('facturacion/comprobante', 'Articulo\ArticuloController@crearComprobante'); 
Route::name('facturacion')->put('facturacion/comprobante/{id}', 'Articulo\ArticuloController@actualizarComprobante');
Route::name('facturacion')->put('facturacion/comprobante/movimiento/{id}', 'Articulo\ArticuloController@actualizarMovimientoComprobante');
Route::name('facturacion')->get('facturacion/articulo/anular', 'Articulo\ArticuloController@anularComprobante');


Route::name('articulo')->get('articulo', 'Articulo\ArticuloController@getArticulos');
Route::name('articulo')->get('articulo/activo', 'Articulo\ArticuloController@getArticulosActivo');
Route::name('articulo')->get('articulo/tipo', 'Articulo\ArticuloController@getArticuloTipo');
Route::name('articulo')->post('articulo/tipo', 'Articulo\ArticuloController@setArticuloTipo'); 
Route::name('articulo')->put('articulo/tipo/{id}', 'Articulo\ArticuloController@actualizarArticuloTipo');
Route::name('articulo')->post('articulo', 'Articulo\ArticuloController@setArticulo'); 
Route::name('articulo')->put('articulo/{id}', 'Articulo\ArticuloController@actualizarArticulo');

/**PACIENTE**/

Route::resource('paciente', 'Paciente\PacienteController'); 
Route::name('paciente-consulta')->get('paciente/by/consulta', 'Paciente\PacienteController@getPacienteByQuery');
Route::name('paciente-consulta')->get('paciente/obrasocial/habilitada/{id}', 'Paciente\PacienteController@pacienteAndObraSocialEsHabilitada');
Route::name('paciente-consulta')->get('paciente/obrasocial/habilitada/todas/{id}', 'Paciente\PacienteController@pacienteAndObraSocialEsTodas');


/** FILE MANAGER **/
Route::name('archivos')->post('/multiuploads/estudios', 'Upload\UploadController@showUploadFile');
Route::name('archivos')->post('/multiuploads/estudios/datos', 'Upload\UploadController@showUploadFileDatos');
Route::name('archivos')->post('/multiuploads/texto', 'Files\FilesController@createTestTextFile'); 
Route::name('archivos')->post('/multiuploads/texto/cirugia', 'Files\FilesController@createTestTextFileCirugia'); 
Route::name('archivos')->get('/multiuploads/estudios/verimagen', 'Upload\UploadController@getEstudioImagenes'); 

/** MOVIMIENTO **/



