<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OfertaApiController;
use App\Http\Controllers\Api\EstablecimientoApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\API\RolApiController;
use App\Http\Controllers\API\CategoriaApiController;
use App\Http\Controllers\API\SubCategoriaApiController;
use App\Http\Controllers\AuthController;




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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('oferta', OfertaApiController::class)->middleware("auth:api");;
Route::apiResource('establecimiento',EstablecimientoApiController::class)->middleware("auth:api");
Route::apiResource('user',UserApiController::class);
Route::apiResource('rol',RolApiController::class);
Route::apiResource('categoria',CategoriaApiController::class);
Route::apiResource('subcategoria',SubCategoriaApiController::class);

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class,'login']);
    Route::post('signup', [AuthController::class,'signUp']);
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', [AuthController::class,'logout']);
        Route::get('user', [AuthController::class,'user']);
    });
});