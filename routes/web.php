<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

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
})->middleware('auth');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth');


Route::resource('compra', App\Http\Controllers\CompraController::class)->middleware('auth');
Route::resource('usuario', App\Http\Controllers\UsuarioController::class)->middleware('auth');
Route::resource('/login', App\Http\Controllers\CompraController::class)->middleware('auth');
Route::resource('clientes', App\Http\Controllers\ClienteController::class)->middleware('auth');
Route::resource('proveedor', App\Http\Controllers\ProveedorController::class)->middleware('auth');
Route::resource('insumos', App\Http\Controllers\InsumoController::class)->middleware('auth');
Route::resource('pedidos', App\Http\Controllers\PedidoController::class);
Route::resource('figuras', App\Http\Controllers\FiguraController::class);
Route::resource('clientes', App\Http\Controllers\ClienteController::class);
Route::resource('Proveedor', App\Http\Controllers\ProveedorController::class);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Auth::routes();
