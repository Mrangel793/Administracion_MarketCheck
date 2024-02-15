<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\RolApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\ImageApiController;
use App\Http\Controllers\Api\OfertaApiController;
use App\Http\Controllers\Api\ComprasApiController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\API\CategoriaApiController;
use App\Http\Controllers\API\SubCategoriaApiController;
use App\Http\Controllers\Api\EstablecimientoApiController;
use App\Http\Controllers\Api\CargaInventarioApiController;







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

Route::get('api/importe', [CargaInventarioApiController::class, 'index'])->name('api.importe.index')->middleware('auth:api');
Route::post('importe/importar', [CargaInventarioApiController::class, 'importar'])->name('api.importe.importar')->middleware('auth:api');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::apiResource('establecimiento',EstablecimientoApiController::class)/*->middleware("auth:api")*/;
Route::get('establecimiento/{establecimiento_id}/ofertas/{oferta_id}', [EstablecimientoApiController::class, 'showOffer'])->middleware('auth:api');

Route::put('/establecimiento/activate/{id}', [EstablecimientoApiController::class, 'activateOrDestivateStore']); //<--- AUTENTICABLE???
Route::put('/establecimiento/deactivate/{id}', [EstablecimientoApiController::class, 'activateOrDestivateStore']); //<--- AUTENTICABLE???

Route::put('/establecimiento/images/{id}', [EstablecimientoApiController::class, 'updateImageField']); //<--- AUTENTICABLE???

//OFERTAS API CONTROLLER---------------------------------------------------------------------------------------------------

Route::prefix('ofertas')->group(function () {
    Route::get('/mobile-app', [OfertaApiController::class, 'offersMobileApp']);
    Route::get('/show-offer/{id}', [OfertaApiController::class, 'showOfferMobileApp']); //<--- AUTENTICABLE???

    Route::get('/', [OfertaApiController::class, 'index'])->middleware('auth:api');
    Route::post('/', [OfertaApiController::class, 'store'])->middleware('auth:api');
    Route::get('/{id}', [OfertaApiController::class, 'show'])->middleware('auth:api');
    Route::put('/{id}', [OfertaApiController::class, 'update'])->middleware('auth:api');
    Route::get('/{ofertaId}/productos', [OfertaApiController::class, 'productosOferta'])->middleware('auth:api');
    
    Route::put('/image/{id}', [OfertaApiController::class, 'updateImageField'])->middleware('auth:api');
    
    Route::put('/activate/{ofertaId}', [OfertaApiController::class, 'activateOrDesactivateOffer'])->middleware('auth:api');
    Route::post('/{ofertaId}/guardar-productos', [OfertaApiController::class, 'guardarProductos'])->middleware('auth:api');
    Route::delete('/{id}', [OfertaApiController::class, 'destroy'])->middleware('auth:api');
    Route::delete('/{ofertaId}/productos/{productoId}', [OfertaApiController::class, 'eliminarProducto'])->middleware('auth:api');
    
});

//USER CRUD ---------------------------------------------------------------------//

Route::apiResource('user',UserApiController::class)->middleware("auth:api");
Route::put('/user/{id}/change-password', [UserApiController::class, 'changePassword']); //<--- AUTENTICABLE???
//-------------------------------------------------------------------------------**--//

Route::apiResource('rol',RolApiController::class); //<--- AUTENTICABLE???

Route::apiResource('categoria',CategoriaApiController::class); //<--- AUTENTICABLE???

Route::apiResource('compra',CompraController::class); //<--- AUTENTICABLE???

Route::apiResource('images', ImageApiController::class);

Route::apiResource('subcategoria',SubCategoriaApiController::class); //<--- AUTENTICABLE???
Route::get('subcategoria/categoria/{id_categoria}', [SubCategoriaApiController::class, 'indexporCategoria']); //<--- AUTENTICABLE???


Route::prefix('productos')->group(function () {

    Route::get('/store-products/{id}', [ProductoApiController::class, 'productsByStoreMobileApp'])/*->middleware('auth:api')*/;
    Route::post('/scanner-product', [ProductoApiController::class, 'productByStoreAndScanner'])/*->middleware('auth:api')*/;

    Route::get('/', [ProductoApiController::class, 'index'])->middleware('auth:api');
    Route::get('/uncategorized', [ProductoApiController::class, 'getUncategorizedProducts']);

    Route::get('/{id}', [ProductoApiController::class, 'show']);
    Route::post('/', [ProductoApiController::class, 'store'])->middleware('auth:api');
    Route::put('/{id}', [ProductoApiController::class, 'update'])->middleware('auth:api');
    Route::delete('/{id}', [ProductoApiController::class, 'destroy']); 
    Route::put('/activate/{id}', [ProductoApiController::class, 'activate'])->middleware('auth:api'); //<--- SOLO UNA FUNCION
    Route::put('/deactivate/{id}', [ProductoApiController::class, 'deactivate']);
    Route::get('/getProductsfilter/{searchTerm}',[ProductoApiController::class,'getProductsfilter'])->middleware('auth:api');
          
});




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

//COMPRAS API CONTROLLER--------------------------------------------------------------------------------------------------- 

Route::prefix('compras')->group(function () {
    Route::get('/mobile-app', [ComprasApiController::class, 'purchasesMobileApp'])->middleware('auth:api');
    Route::post('/new-purchase', [ComprasApiController::class, 'newPurchaseMobileApp'])->middleware('auth:api');

    Route::get('/', [ComprasApiController::class, 'index'])->middleware('auth:api');
    Route::get('/compra/{compraid}', [ComprasApiController::class, 'showCompra'])->middleware('auth:api');
    Route::get('/{compraid}', [ComprasApiController::class, 'productosCompra'])->middleware('auth:api');

    Route::post('/new', [ComprasApiController::class, 'store'])->middleware('auth:api');
    Route::post('/{idCompra}/producto/{productoId}', [ComprasApiController::class, 'guardar'])->middleware('auth:api');

    Route::put('/{idCompra}/finalizarCompra',[ComprasApiController::class, 'finalizarCompra'])->middleware('auth:api');
    Route::delete('/{idCompra}',[ComprasApiController::class, 'destroy'])->middleware('auth:api');
    Route::delete('/{idCompra}/item{itemId}',[ComprasApiController::class, 'destroyPurchaseItem'])->middleware('auth:api');

    Route::get('/sales/daily', [ComprasApiController::class, 'getDailySales'])->middleware('auth:api');
    Route::get('/sales/monthly', [ComprasApiController::class, 'getMonthlySales'])->middleware('auth:api');
    Route::get('/sales/yearly', [ComprasApiController::class, 'getAnnualSales'])->middleware('auth:api');
    Route::get('/sales/topProducts', [ComprasApiController::class, 'getTopSellingProducts'])->middleware('auth:api');
    Route::get('/sales/getSalesLast10Months', [ComprasApiController::class, 'getSalesLast10Months'])->middleware('auth:api');
    Route::get('/sales/getPurchasePin/{pin}',[ComprasApiController::class, 'getPurchasePin'])->middleware('auth:api');

});

