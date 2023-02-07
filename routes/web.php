<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CalendarioController;

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
    return view('welcome');
});


Auth::routes();



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//RUTAS CLIENTES
Route::resource('cliente',ClienteController::class)->middleware('auth');

Route::get('estadocliente',[App\Http\Controllers\ClienteController::class, 'updateStatusCliente'])->name('updateStatusCliente');

//RUTAS PROVEEDORES

Route::resource('proveedor',ProveedorController::class)->middleware('auth');

Route::get('/detalleproveedor', [App\Http\Controllers\ProveedorController::class, 'show']);

Route::get('detallefactura/pdf',[App\Http\Controllers\ProveedorController::class, 'pdf'])->name('proveedor.pdf');

Route::get('estadoproveedor',[App\Http\Controllers\ProveedorController::class, 'updateStatusProveedor'])->name('proveedor.updateStatusProveedor');
//Route::get('proveedor', [App\Http\Controllers\ProveedorController::class, 'update_status'])->name('Editar_estado_proveedor');


// RUTAS CALENDARIO

//Route::resource('calendario', [App\Http\Controllers\CalendarioController::class, 'Pindex']);

Route::resource('calendario',CalendarioController::class);



