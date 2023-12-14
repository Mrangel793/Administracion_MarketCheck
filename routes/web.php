<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstablecimientosController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CargaInventarioController;
use App\Http\Controllers\ComprasController;


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
    return view('auth.login');
});


Route::resource('establecimiento',EstablecimientosController::class)->middleware('auth');



Route::resource('user',UsersController::class);
Route::resource('producto', ProductoController::class);
Route::get('/compras', [App\Http\Controllers\ComprasController::class, 'index'])->name('compras.index');
Route::get('/compras/create', [App\Http\Controllers\ComprasController::class, 'create'])->name('compras.create');
Route::post('/compras/store', [App\Http\Controllers\ComprasController::class, 'store'])->name('compras.store');
Route::match(['get', 'post'], '/compras/consultar', [App\Http\Controllers\ComprasController::class, 'consultar'])->name('compras.consultar');
Route::post('compras/guardar', [App\Http\Controllers\ComprasController::class, 'guardar'])->name('compras.guardar');





Route::get('importe', [App\Http\Controllers\CargaInventarioController::class, 'index'])->name('importe')->middleware('auth');;
Route::post('importe/importar', [App\Http\Controllers\CargaInventarioController::class, 'importar'])->middleware('auth');;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('oferta',OfertaController::class)->middleware('auth');
Route::get('/oferta/{ofertaId}/agregar-productos', [OfertaController::class, 'agregarProductos'])->name('oferta.agregar_productos');
Route::post('/oferta/{ofertaId}/guardar-productos', [OfertaController::class, 'guardarProductos'])->name('oferta.guardar_productos');
Route::post('/oferta/{ofertaId}/finalizar-agregar-productos', [OfertaController::class,'finalizarAgregarProductos'])->name('oferta.finalizar_agregar_productos');
Route::put('/ofertas/desactivar/{ofertaId}', [OfertaController::class,'desactivarOferta'])->name('oferta.desactivar');
Route::put('/ofertas/activar/{ofertaId}', [OfertaController::class,'activarOferta'])->name('oferta.activar');



Route::get('importe', [App\Http\Controllers\CargaInventarioController::class, 'index'])->name('importe')->middleware('auth');
Route::post('importe/importar', [App\Http\Controllers\CargaInventarioController::class, 'importar'])->middleware('auth');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
