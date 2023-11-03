<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OfertaApiController;
use App\Http\Controllers\Api\EstablecimientoApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\API\RolApiController;
use App\Http\Controllers\API\CategoriaApiController;
use App\Http\Controllers\API\SubCategoriaApiController;




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

Route::apiResource('oferta', OfertaApiController::class);
Route::apiResource('establecimiento',EstablecimientoApiController::class);
Route::apiResource('user',UserApiController::class);
Route::apiResource('rol',RolApiController::class);
Route::apiResource('categoria',CategoriaApiController::class);
Route::apiResource('subcategoria',SubCategoriaApiController::class);

