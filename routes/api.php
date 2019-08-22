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

Route::name('medico-afip')->get('medico/factura/afip', 'Medico\MedicoController@getAfip');

Route::resource('medicoobrasocial', 'Medico\MedicoObraSocialController'); 
Route::name('medico-obrasocial')->post('medicoobrasocial', 'Medico\MedicoController@postMedicoObraSocial');
Route::name('medico-obrasocial')->get('medico/obrasocial/todos', 'Medico\MedicoObraSocialController@byIdMedicoTodos');
Route::name('medico-obrasocial')->get('medico/obrasocial/byobrasocial', 'Medico\MedicoObraSocialController@getMedicoByObraSocialHabilitado');
Route::name('medico-obrasocia')->get('medico/obrasocial/bymedico', 'Medico\MedicoObraSocialController@getObraSocialByMedicoHabilitado');
Route::name('medico-obrasocia')->get('medico/obrasocial/bymedico/todos', 'Medico\MedicoObraSocialController@getObraSocialByMedicoTodos');
Route::name('filter-medico-obra-social')->get('medicoobrasocial/bymedico/{id}', 'Medico\MedicoObraSocialController@byIdMedico');
Route::name('filter-medico-obra-social-hablitado')->get('medicoobrasocial/bymedicohabilitado/{id}', 'Medico\MedicoObraSocialController@byIdMedicoHabilitado');
Route::resource('estudio', 'Estudio\EstudioController');



/**AGENDA **/
Route::resource('agenda', 'Agenda\AgendaController');


Route::name('agenda-gestion')->get('agenda/horarios/turno/medico/sobreturno', 'Agenda\AgendaController@getAgendaAtByFechaUsuarioSobreTurno'); 
//Route::name('agenda-gestion')->get('agenda/horarios/turno/medico/sobreturno', 'Agenda\AgendaController@getAgendaAtByFechaUsuarioSobreTurno'); 
Route::name('agenda-gestion')->get('agenda/horarios/turno', 'Agenda\AgendaController@getAgendaAtencionByFechaTodos');
Route::name('agenda-gestion')->get('agenda/horarios/turno/medico', 'Agenda\AgendaController@getAgendaAtencionByFechaAndMedico'); 
Route::name('agenda-gestion')->get('agenda/horarios/turno/medico/noestado', 'Agenda\AgendaController@getAgendaAtencionByFechaAndMedicoSinEstado');
Route::name('agenda-gestion')->get('agenda/horarios/turno/todos/noestado', 'Agenda\AgendaController@getAgendaAtencionByFechaTodosSinEstado'); 
Route::name('agenda-gestion')->get('agenda/horarios/turno/todos/noestado/bydates', 'Agenda\AgendaController@getAgendaAtencionByFechaTodosSinEstadoBetweenDates');
Route::name('agenda-gestion')->get('agenda/horarios/turno/nuevo', 'Agenda\AgendaController@getAgendaAtByFechaTodosTurnos');
Route::name('agenda-gestion')->get('agenda/horarios/turno/nuevo/usuario', 'Agenda\AgendaController@getAgendaAtByFechaMedicoTurnos');
Route::name('agenda-gestion')->get('agenda/horarios/turno/nuevo/usuario/todos', 'Agenda\AgendaController@getAgendaAtByFechaMedicoTurnosTodos');
Route::name('agenda-gestion')->get('agenda/horarios', 'Agenda\AgendaController@getAgendaByHorarios');
Route::name('agenda-gestion')->get('agenda/medico/dia', 'Agenda\AgendaController@getAgendaByMedicoAndDia'); 
Route::name('agenda-gestion')->put('agenda/deshabilitar/{id}', 'Agenda\AgendaController@DeshabilitarHorarioByMedico'); 
Route::name('agenda-gestion')->get('agenda/medico/todo', 'Agenda\AgendaController@getAgendaByMedicoAndDiaTodoEstado'); 
Route::name('agenda-gestion')->get('agenda/medico/disponilible', 'Agenda\AgendaController@getAgendaByMedicoAndDiaDisponible'); 
Route::name('agenda-gestion')->get('agenda/todos/disponilible', 'Agenda\AgendaController@getAgendaByDiaDisponible'); 
Route::name('agenda-gestion')->post('agenda/crear/medico', 'Agenda\AgendaController@generarHorarioAgenda'); 
Route::name('agenda-gestion')->get('agenda/crearhorario', 'Agenda\AgendaController@crearAgendaByHorario');
Route::name('agenda-gestion')->post('agenda/asignar/turno', 'Agenda\AgendaController@asignarTurno');
Route::name('agenda-gestion')->get('agenda/horarios/dias', 'Agenda\AgendaController@getDias'); 
Route::name('agenda-gestion')->get('agenda/horarios/periodo', 'Agenda\AgendaController@getHorario');
Route::name('agenda-gestion')->get('agenda/horarios/horarios/bloquear/turno', 'Agenda\AgendaController@bloquearAgendaTurno'); 
Route::name('agenda-gestion')->post('agenda/horarios/horarios/bloquear/periodo', 'Agenda\AgendaController@bloquearAgenda'); 
Route::name('agenda-gestion')->get('agenda/horarios/horarios/bloquear/medico', 'Agenda\AgendaController@getAgendaBloqueoByMedicoAndDiaTodoEstado'); 
Route::name('agenda-gestion')->get('agenda/horarios/medico/bloqueado', 'Agenda\AgendaController@getAgendaBloqueo');  
Route::name('agenda-gestion')->get('agenda/horarios/paciente/historia/{id}', 'Agenda\AgendaController@getHistoriaPaciente');  
Route::name('agenda-gestion')->get('agenda/horarios/paciente/cancelar/{id}', 'Agenda\AgendaController@cancelarTurno');
Route::name('agenda-gestion')->get('agenda/horarios/turno/eliminado', 'Agenda\AgendaController@getAgendaEliminados');   
Route::name('agenda-gestion')->get('agenda/horarios/turno/todos', 'Agenda\AgendaController@getAgendaAtencionByFechaTurnosTodos');
Route::name('agenda-gestion')->put('agenda/horarios/turno/operacioncobro/actualizar/{id}', 'Agenda\AgendaController@updateAgendaOperacionCobro'); 
Route::name('agenda-gestion')->get('agenda/horarios/turno/todos', 'Agenda\AgendaController@getAgendaAtencionByFechaTurnosTodos');
Route::name('agenda-gestion')->get('agenda/horarios/bloqueo/turno', 'Agenda\AgendaController@getHorarioBloqueoByMedico');
Route::name('agenda-gestion')->get('agenda/horarios/bloqueo/dia', 'Agenda\AgendaController@getDiasBloqueados');
Route::name('agenda-gestion')->get('agenda/horarios/cancelar/horario/{id}', 'Agenda\AgendaController@deleteAgendaMedicoHorario');
Route::name('agenda-gestion')->get('agenda/horarios/cancelar/agenda/{id}', 'Agenda\AgendaController@deleteAgendaMedico');


/** CIRUGIA **/
Route::name('historia-clinica')->get('cirugia/historia/{id}', 'Cirugia\CirugiaController@getHistoriaClinicaByPaciente');
Route::name('historia-clinica')->get('cirugia/historia/actualizar/paciente', 'Cirugia\CirugiaController@actualizarRegistroHistoriaClinica'); 
Route::name('historia-clinica')->post('cirugia/historia/registro/insertar', 'Cirugia\CirugiaController@setHistoriaClinicaFicha');
Route::name('historia-clinica')->put('cirugia/historia/registro/actualizar/{id}', 'Cirugia\CirugiaController@updHistoriaClinicaById');

/** ASESORAMIENTO **/
Route::name('cirugia')->get('cirugia/ficha/ficha/estado/{estado}', 'Cirugia\CirugiaController@getFichaQuirurgica');
Route::name('cirugia')->get('cirugia/ficha/completa/{estado}', 'Cirugia\CirugiaController@getFichaQuirurgicaCompleta');
Route::name('cirugia')->get('cirugia/ficha/grupomedico/{id}', 'Cirugia\CirugiaController@getFichaQuirurgicaGrupoMedico');
Route::name('cirugia')->get('cirugia/ficha/estudios/{id}', 'Cirugia\CirugiaController@ getFichaQuirurgicaEstudios');
Route::name('cirugia')->get('cirugia/ficha/anestesia/{id}', 'Cirugia\CirugiaController@getFichaQuirurgicaAnestesia');
Route::name('cirugia')->get('cirugia/ficha/practica/{id}', 'Cirugia\CirugiaController@ getFichaQuirurgicaPractica');
Route::name('cirugia')->get('cirugia/ficha/rendicion/{id}', 'Cirugia\CirugiaController@getFichaQuirurgicaRendicion');
Route::name('cirugia')->get('cirugia/ficha/lente/{id}', 'Cirugia\CirugiaController@getFichaQuirurgicaLente');
Route::name('cirugia')->get('cirugia/ficha/cirugia/estado', 'Cirugia\CirugiaController@getCirugiaEstado'); 

Route::name('cirugia')->post('cirugia/ficha/derivar', 'Cirugia\CirugiaController@crearRegistroCirugia');
Route::name('cirugia')->post('cirugia/grupomedico', 'Cirugia\CirugiaController@crearRegistroGrupoMedico');
Route::name('cirugia')->post('cirugia/estudios', 'Cirugia\CirugiaController@crearRegistroCirugiaGrupoMedico');
Route::name('cirugia')->post('cirugia/anestesia', 'Cirugia\CirugiaController@crearRegistroAnestesia');
Route::name('cirugia')->put('cirugia/practica/{id}', 'Cirugia\CirugiaController@actualizarRegistroCirugiaPractica'); 
Route::name('cirugia')->put('cirugia/practica/estado/{id}', 'Cirugia\CirugiaController@actualizarRegistroCirugiaEstado');
Route::name('cirugia')->post('cirugia/registro/lente', 'Cirugia\CirugiaController@crearRegistroLente');


Route::name('cirugia')->put('cirugia/grupomedico/{id}', 'Cirugia\CirugiaController@actualizarRegistroCirugiaGrupoMedico');
Route::name('cirugia')->put('cirugia/anestesia/{id}',   'Cirugia\CirugiaController@actualizarRegistroCirugiaEstudios');

Route::name('cirugia')->get('cirugia/ficha/registro/delete',    'Cirugia\CirugiaController@destroyRegistroLenteFichaQuirugica');
Route::name('cirugia')->get('cirugia/ficha/derivar/listado',    'Cirugia\CirugiaController@getFichAsesoramientoDerivados');
Route::name('cirugia')->put('cirugia/ficha/derivar/listado/atender/{id}',    'Cirugia\CirugiaController@actualizarFichAsesoramientoDerivados');
Route::name('cirugia')->delete('cirugia/ficha/listado/{id}', 'Cirugia\CirugiaController@destroyCirugiaListado');



/**********listado de cirugia previa */
//Route::name('cirugia')->get('cirugia/listado/quirofano',    'Cirugia\CirugiaController@getFichaQuirurgicaListadoQuirofano');
//Route::name('cirugia')->put('cirugia/listado/quirofano',    'Cirugia\CirugiaController@actualizarRegistroCirugiaAnestesia');
Route::name('cirugia')->post('cirugia/listado/quirofano', 'Cirugia\CirugiaController@createListadoQuirofano');
Route::name('cirugia')->get('cirugia/listado/quirofano',    'Cirugia\CirugiaController@getListadoQuirofano');
Route::name('cirugia')->get('cirugia/listado/quirofano/realizado',    'Cirugia\CirugiaController@getFichaQuirurgicaRealizado');
Route::name('cirugia')->put('cirugia/listado/quirofano/{id}',    'Cirugia\CirugiaController@updateListadoQuirofano');



/** CONVENIOS **/
Route::resource('obrasocial', 'ObraSocial\ObraSocialController');
Route::name('obra-social')->get('obrasocialby/obrasocialpmo', 'ObraSocial\ObraSocialController@obraSocialByIdAndPmoId'); 
Route::name('obra-social')->get('obrasocialby/generarcoseguro', 'ObraSocial\ObraSocialController@generarCoseguros'); 
Route::name('obra-social')->get('obrasocialby/actualizarcoseguro', 'ObraSocial\ObraSocialController@actualizarCoseguros'); 
Route::name('obra-social')->get('obrasocialby/insertarcoseguro', 'ObraSocial\ObraSocialController@insertarConvenioCoseguro'); 
Route::name('obra-social')->get('obrasocialby/actualizar/distribucion', 'ObraSocial\ObraSocialController@ActualizarValoresDistribucion'); 

Route::resource('convenio', 'ObraSocial\ConvenioObraSocialController');
Route::name('convenio-obra-social')->get('convenio/byobrasocial/{id}', 'ObraSocial\ConvenioObraSocialController@findByObraSocial'); 
Route::name('convenio-obra-social')->get('convenio/bypmo/{id}', 'ObraSocial\ConvenioObraSocialController@findByPmo'); 
Route::name('convenio-obra-social')->get('convenio/by/obrasocialandcoseguro', 'ObraSocial\ConvenioObraSocialController@findByObraSocialAndCoseguro'); 


Route::resource('pmo', 'ObraSocial\PmoController');

/**FACTURACION**/

Route::resource('liquidacion/entidad', 'Liquidacion\EntidadFacturaController');
Route::name('liquidacion')->get('liquidacion/detalle', 'OperacionCobro\OperacionCobroController@getLiquidacionDetalle');
Route::name('liquidacion')->post('liquidacion/detalle/prefactura', 'OperacionCobro\OperacionCobroController@getListadoPreFactura'); 
Route::name('liquidacion')->post('liquidacion/detalle/prefactura/cirugia', 'OperacionCobro\OperacionCobroController@getListadoPreFacturaCirugia'); 
Route::name('liquidacion')->get('liquidacion/detalle/prefactura/desafectar', 'OperacionCobro\OperacionCobroController@desafectarPresentacion');


/**PACIENTE**/

Route::resource('paciente', 'Paciente\PacienteController'); 
Route::name('paciente-consulta')->get('paciente/by/consulta', 'Paciente\PacienteController@getPacienteByQuery');
Route::name('paciente-consulta')->get('paciente/obrasocial/habilitada/{id}', 'Paciente\PacienteController@pacienteAndObraSocialEsHabilitada');
Route::name('paciente-consulta')->get('paciente/obrasocial/habilitada/todas/{id}', 'Paciente\PacienteController@pacienteAndObraSocialEsTodas');
Route::resource('pacienteobrasocial', 'Paciente\PacienteObraSocialController');
Route::resource('pacienteagenda', 'Paciente\PacienteAgendaController');
Route::name('pacienteagenda')->get('pacienteagenda/bydate/today', 'Paciente\PacienteAgendaController@byDateToday');
Route::name('pacienteagenda')->get('pacienteagenda/bydateselected/{fecha}','Paciente\PacienteAgendaController@byDateSelected');
Route::name('pacienteagenda')->get('pacienteagenda/bydatedni/{dni}','Paciente\PacienteAgendaController@byDni'); 

/**OPERACION DE COBRO**/
Route::resource('operacioncobro', 'OperacionCobro\OperacionCobroController');
Route::name('operacioncobro')->post('operacioncobro/registros', 'OperacionCobro\OperacionCobroController@registroOperacionCobro');
Route::name('operacioncobro')->get('operacioncobro/registros/by/dates', 'OperacionCobro\OperacionCobroController@getOperacionCobroRegistrosBetweenDates'); 
Route::name('operacioncobro')->get('operacioncobro/registros/by/dates/medico', 'OperacionCobro\OperacionCobroController@getOperacionCobroRegistrosBetweenDatesAndMedico'); 
Route::name('operacioncobro')->get('operacioncobro/registros/by/id', 'OperacionCobro\OperacionCobroController@getOperacionCobroRegistrosById');
Route::name('operacioncobro')->get('operacioncobro/registros/by/liquidacion/numero', 'OperacionCobro\OperacionCobroController@getPresentacionDetalleById');
Route::name('operacioncobro')->get('operacioncobro/registros/by/distribucion', 'OperacionCobro\OperacionCobroController@getOperacionCobroRegistroDistribucionById');
Route::name('operacioncobro')->get('operacioncobro/registros/by/distribucion/prefactura', 'OperacionCobro\OperacionCobroController@getOperacionCobroRegistroDistribucionByIdPrefactura');

Route::name('operacioncobro')->post('operacioncobro/facturacion/auditarorden', 'OperacionCobro\OperacionCobroController@auditarOrdenes');
Route::name('operacioncobro')->post('operacioncobro/afectar/orden', 'OperacionCobro\OperacionCobroController@afectarOperacionCobro');
Route::name('operacioncobro')->delete('operacioncobro/practica/{id}', 'OperacionCobro\OperacionCobroController@destroyByPracticaById');
Route::name('operacioncobro')->put('operacioncobro/practica/actualizar/{id}', 'OperacionCobro\OperacionCobroController@updateOperacionCobroPractica'); 
Route::name('operacioncobro')->put('operacioncobro/practica/anular/{id}', 'OperacionCobro\OperacionCobroController@updateOperacionCobroPracticaAnular'); 
Route::name('operacioncobro')->put('operacioncobro/operacioncobro/actualizar/{id}', 'OperacionCobro\OperacionCobroController@updateOperacionCobroPrincipal'); 
Route::name('operacioncobro')->put('operacioncobro/presentacion/actualizar/{id}', 'OperacionCobro\OperacionCobroController@updatePresentacion'); 
Route::name('operacioncobro')->get('operacioncobro/consulta/varios', 'OperacionCobro\OperacionCobroController@operacionCobroByCondicion');
Route::name('operacioncobro')->get('operacioncobro/recalcular/by/fecha', 'OperacionCobro\OperacionCobroController@updateOperacionCobroRecalcularValoresBetweenDates');
Route::name('operacioncobro')->put('operacioncobro/registro/prestacion/{id}', 'OperacionCobro\OperacionCobroController@updateOperacionCobroPrestacion');


/**FACTURACION **/

Route::resource('practica', 'Practica\PracticaController');
Route::name('practica')->get('practica/byobrasocial/{id}', 'Practica\PracticaController@byobrasocial');
Route::name('practica')->get('practica/by/{obrasocialmedico}', 'Practica\PracticaController@byObrasocialAndMedico');
Route::name('practica')->get('practica/by/fecha/{orden}', 'Practica\PracticaController@showBetweenDate');
Route::name('practica')->get('practica/by/liquidacion/{id}', 'Practica\PracticaController@byLiquidacionId');
Route::name('practica')->get('practica/by/agenda/{id}', 'Practica\PracticaController@byAgendaId');

Route::resource('liquidacion', 'Liquidacion\LiquidacionController');
Route::name('liq')->get('liquidacion/by/generacion/{id}', 'Liquidacion\LiquidacionController@byLiquidacionGenerada');

Route::resource('liquidaciongenerada', 'Liquidacion\LiquidacionGeneradaController');

Route::resource('practicadistribucion', 'Practica\PracticaDistribucionController');
Route::name('practicadistribucion')->get('practicadistribucion/byconvenioospmo/{id}', 'Practica\PracticaDistribucionController@bypractica');

/** STOCK **/
Route::name('stock')->get('stock/lente/by/todos', 'Cirugia\CirugiaController@GetLentes');
Route::name('stock')->post('stock/lente', 'Cirugia\CirugiaController@crearLente');
Route::name('stock')->put('stock/lente/{id}', 'Cirugia\CirugiaController@actualizarLente');
Route::name('stock')->get('lente/lente', 'Cirugia\CirugiaController@getLenteTipo');

/** FILE MANAGER **/
Route::name('archivos')->post('/multiuploads/estudios', 'Upload\UploadController@showUploadFile');
Route::name('archivos')->post('/multiuploads/estudios/datos', 'Upload\UploadController@showUploadFileDatos');
Route::name('archivos')->post('/multiuploads/texto', 'Files\FilesController@createTestTextFile'); 
Route::name('archivos')->post('/multiuploads/texto/cirugia', 'Files\FilesController@createTestTextFileCirugia'); 
Route::name('archivos')->get('/multiuploads/estudios/verimagen', 'Upload\UploadController@getEstudioImagenes'); 

