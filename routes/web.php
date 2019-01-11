<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('autenticacion/login');
});

Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::resource('registro', 'Auth\RegistroController');

Route::group(['namespace' => 'soap'], function() 
{
    Route::resource('dashboard', 'DashboardController');
    Route::resource('ticketnuevo', 'Ticket_Nuevo_Controller');
    Route::resource('ticketbuscar', 'Ticket_Buscar_Controller');
    route::get('descargar/{id_detalle_ticket}', 'Ticket_Buscar_Controller@descargar_archivos')->name('descargar');
    Route::resource('ticketasignar', 'Ticket_Asignar_Controller');
    Route::resource('ticketasignados', 'Ticket_Asignados_Controller');
    Route::resource('tickethistorial', 'Ticket_Historial_Controller');
    
    Route::resource('marcas', 'Marca_Controller');
    Route::resource('proveedor', 'Proveedor_Controller');
    Route::resource('facturas', 'Factura_Controller');
    Route::resource('items', 'Item_Controller');
    Route::resource('movimientos', 'Movimiento_Controller');
});


 
