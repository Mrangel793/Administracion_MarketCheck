<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\RolApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\ImageApiController;
use App\Http\Controllers\Api\OfertaApiController;
use App\Http\Controllers\Api\ListasApiController;
use App\Http\Controllers\Api\ComprasApiController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\CategoriaApiController;
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

Auth::routes(['verify' => true]);

Route::get('api/importe', [CargaInventarioApiController::class, 'index'])->name('api.importe.index')->middleware('auth:api');
Route::post('importe/importar', [CargaInventarioApiController::class, 'importar'])->name('api.importe.importar')->middleware('auth:api');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::apiResource('establecimiento',EstablecimientoApiController::class)->middleware("auth:api");
Route::get('establecimiento/{establecimiento_id}/ofertas/{oferta_id}', [EstablecimientoApiController::class, 'showOffer'])->middleware('auth:api');

Route::put('/establecimiento/activate/{id}', [EstablecimientoApiController::class, 'activateOrDestivateStore'])->middleware('auth:api');
Route::put('/establecimiento/deactivate/{id}', [EstablecimientoApiController::class, 'activateOrDestivateStore'])->middleware('auth:api');

Route::put('/establecimiento/images/{id}', [EstablecimientoApiController::class, 'updateImageField'])->middleware('auth:api');
Route::get('/establecimiento/showCategoriesByStore/{id}',[EstablecimientoApiController::class,'showCategoriesByStore']);

//OFERTAS API CONTROLLER---------------------------------------------------------------------------------------------------

Route::prefix('ofertas')->group(function () {
    Route::get('/mobile-app/{id}', [OfertaApiController::class, 'offersMobileApp']);
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
Route::put('/user/{id}/change-password', [UserApiController::class, 'changePassword'])->middleware("auth:api"); 
Route::get('/user/userStores/{id}',[UserApiController::class, 'UserStores'])->middleware("auth:api"); 
Route::get('/user/userProducts/{id}',[UserApiController::class, 'UserMostPurchasedProducts'])->middleware("auth:api"); 

//-------------------------------------------------------------------------------**--//

Route::apiResource('rol',RolApiController::class); //<--- AUTENTICABLE???

Route::apiResource('categoria',CategoriaApiController::class); //<--- AUTENTICABLE???

Route::apiResource('compra',CompraController::class); //<--- AUTENTICABLE???

Route::apiResource('images', ImageApiController::class);



Route::prefix('productos')->group(function () {

    Route::get('/store-products/{id}', [ProductoApiController::class, 'productsByStoreMobileApp'])/*->middleware('auth:api')*/;
    Route::post('/scanner-product', [ProductoApiController::class, 'productByStoreAndScanner'])/*->middleware('auth:api')*/;

    Route::get('/', [ProductoApiController::class, 'index'])->middleware('auth:api');
    Route::get('/uncategorized', [ProductoApiController::class, 'getUncategorizedProducts']);

    Route::get('/{id}', [ProductoApiController::class, 'show']);
    Route::post('/', [ProductoApiController::class, 'store'])->middleware('auth:api');
    Route::put('/{id}', [ProductoApiController::class, 'update'])->middleware('auth:api');
    Route::delete('/{id}', [ProductoApiController::class, 'destroy'])->middleware('auth:api'); 
    Route::put('/activate/{id}', [ProductoApiController::class, 'activate'])->middleware('auth:api'); 
    Route::put('/deactivate/{id}', [ProductoApiController::class, 'deactivate'])->middleware('auth:api'); 
    Route::get('/getProductsfilter/{searchTerm}',[ProductoApiController::class,'getProductsfilter'])->middleware('auth:api');
    Route::post('/assignCategory', [ProductoApiController::class, 'assignCategory'])->middleware('auth:api'); 
    Route::get('/productsCategories/{id_establecimiento}/{id_categoria}', [ProductoApiController::class, 'productosConCategoria']);

});
    Route::get('/productos-sin-categoria', [ProductoApiController::class, 'productosSinCategoria'])->middleware('auth:api');





Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class,'login']);
    Route::post('signup', [AuthController::class,'signUp']);
    Route::post('mobile-app',[AuthController::class, 'addUserMovil']);
  
    Route::group([
      'middleware' => ['auth:api','verified']
    ], function() {
        Route::get('logout', [AuthController::class,'logout']);
        Route::get('user', [AuthController::class,'user']);
    });
});

// LISTAS API CONTROLLER----------------------------------------------------------------

Route::apiResource('/listas',ListasApiController::class)->middleware('auth:api');

//COMPRAS API CONTROLLER--------------------------------------------------------------------------------------------------- 

Route::prefix('compras')->group(function () {
    Route::get('/open-purchases-app', [ComprasApiController::class, 'openPurchasesApp'])->middleware('auth:api');
    Route::get('/close-purchases-app', [ComprasApiController::class, 'closePurchasesApp'])->middleware('auth:api');
    Route::get('/new-purchase/{storeId}', [ComprasApiController::class, 'newPurchaseMobileApp'])->middleware('auth:api');

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

