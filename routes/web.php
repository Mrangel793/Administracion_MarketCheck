<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstablecimientosController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\ProductoController;

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


Route::resource('establecimiento',EstablecimientosController::class)->middleware('auth');

Route::resource('user',UsersController::class);
Route::resource('producto', ProductoController::class);

Route::resource('producto', ProductoController::class);



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('oferta',OfertaController::class)->middleware('auth');


