<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\ComprasProductos;

use Carbon\Carbon;


//EN REVISION. DEFINIR SI STORE DE APP VA A MANEJAR LA MISMA LOGICA **IMPORTANTE** <--- ELIMINAR EN PRODUCCION
class ComprasApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function openPurchasesApp() 
    {
        $user = Auth::user();     
        if($user && isset($user-> id)){ 
            $purchase = Compra::where('user_id', $user-> id)->where('estado', 0)->first();

            return response()->json(['openPurchase'=> $purchase], 200);
        }
        return response()->json(['message' => 'Error al procesar la solicitud'], 500);
        
    }

    public function closePurchasesApp() 
    {
        $user = Auth::user();     
        if($user && isset($user-> id)){ 
            $purchases = Compra::where('user_id', $user-> id)->where('estado', 1)->get();

            return response()->json(['closePurchases'=> $purchases], 200);
        }
        return response()->json(['message' => 'Error al procesar la solicitud'], 500);
        
    } 

    public function newPurchaseMobileApp($storeId){
        $user = Auth::user();

        if($user && isset($user-> id)){
            $compra = Compra::create([
                'hora' => now()->format('H:i:s'), 
                'fecha' => Carbon::now(),
                'total' => 0,
                'estado' => 0,
                'pin' => mt_rand(100000,999999),
                'establecimiento_id' => $storeId,
                'user_id' => $user-> id
            ]);   
            return response()->json(['message' => 'Compra creada con éxito.', 'id'=> $compra-> id, 'pin'=> $compra-> pin], 201);
        }

        return response()->json(['message' => 'Error al procesar la solicitud'], 500);   
    }

    public function index()
    {
        $user = Auth::user();     
        if($user && isset($user->establecimiento_id)){ 
            $purchases = Compra::where('establecimiento_id', $user->establecimiento_id)->get();
            return response()->json(['purchases'=> $purchases], 200);
        }
        return response()->json(['message' => 'El Usuario no tiene permisos para visualizar este Contenido'], 403);
        
    }

    public function productosCompra($compraid)
    {   
        try {
            $purchaseItems = ComprasProductos::where('compra_id', $compraid)->get();
            return response()->json(['items'=>$purchaseItems], 200);

        } catch (\Exception $e) {
            return response()->json(['message'=> 'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }

    public function getTopSellingProducts(Request $request)
{
    $userEstablishmentId = $request->user()->establecimiento_id;

    $topSellingProducts = DB::table('compras_productos')
        ->select('producto_id', DB::raw('SUM(cantidad) as total_quantity'), 'nombre')
        ->whereIn('compra_id', function ($query) use ($userEstablishmentId) {
            $query->select('id')
                ->from('compras')
                ->where('estado',1)
                ->where('establecimiento_id', $userEstablishmentId);
        })
        ->groupBy('producto_id', 'nombre')
        ->orderByDesc('total_quantity')
        ->limit(10)
        ->get();

    return response()->json(['topSellingProducts' => $topSellingProducts]);
    }

    public function getDailySales(Request $request)
    {
        $userEstablishmentId = $request->user()->establecimiento_id; 
    
        $fechaActual = Carbon::now();
        $diaActual = $fechaActual->day;
    
        $totalVentasDelDia = Compra::where('establecimiento_id', $userEstablishmentId)
            ->whereDate('fecha', $fechaActual->toDateString())
            ->where('estado',1)
            ->sum('total');
    
        return response()->json(['totalVentasDelDia' => $totalVentasDelDia, 'diaActual' => $diaActual]);
    }



public function getSalesLast10Months(Request $request)
{
    $userEstablishmentId = $request->user()->establecimiento_id;
    $today = now();
    $last10MonthsSales = [];

    for ($i = 1; $i <= 10; $i++) {
        $monthStartDate = $today->copy()->subMonthsNoOverflow($i)->startOfMonth();
        $monthEndDate = $today->copy()->subMonthsNoOverflow($i)->endOfMonth();

        // Configura la localización para strftime
        setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'es');

        $totalSales = Compra::where('establecimiento_id', $userEstablishmentId)
            ->whereBetween('fecha', [$monthStartDate, $monthEndDate])
            ->where('estado', 1)
            ->sum('total');

        $last10MonthsSales[] = [
            'month' => strftime('%B %Y', $monthStartDate->timestamp), // Formato completo del mes y año
            'total_sales' => $totalSales,
        ];
    }

    return response()->json(['last_10_months_sales' => $last10MonthsSales], 200);
}

public function getPurchasePin($pin) {
    $user = Auth::user();

    if ($user && isset($user->establecimiento_id)) {
        $purchases = Compra::where('establecimiento_id', $user->establecimiento_id)
                           ->where('pin', $pin)
                           ->get();

        if ($purchases->isEmpty()) {
            return response()->json(['message' => 'No se encontraron compras con el PIN proporcionado.'], 404);
        }

        return response()->json(['purchases' => $purchases], 200);
    }

    return response()->json(['message' => 'El usuario no tiene permisos para visualizar este contenido.'], 403);
}

    public function getMonthlySales(Request $request)
{
    $userEstablishmentId = $request->user()->establecimiento_id;

    $today = now();
    $startOfMonth = $today->firstOfMonth()->toDateString();
    $endOfMonth = $today->endOfMonth()->toDateString();

    $monthlySales = Compra::where('establecimiento_id', $userEstablishmentId)
        ->whereBetween('fecha', [$startOfMonth, $endOfMonth])
        ->where('estado',1)
        ->sum('total');

    return response()->json(['ventas del mes' => $monthlySales]);
}


    public function getAnnualSales(Request $request)
    {
        $userEstablishmentId = $request->user()->establecimiento_id;

        $today = now();
        $startOfYear = $today->firstOfYear()->toDateString();
        $endOfYear = $today->endOfYear()->toDateString();

        $annualSales = Compra::where('establecimiento_id', $userEstablishmentId)
            ->whereBetween('fecha', [$startOfYear, $endOfYear])
            ->where('estado',1)
            ->sum('total');

        return response()->json(['annualSales' => $annualSales]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $user = Auth::user();
        //TODO: VALIDAR QUE NO SEA ADMIN
        if($user && isset($user-> establecimiento_id)){
            $compra = Compra::create([
                'hora' => now()->format('H:i:s'),
                'fecha' => Carbon::now(),
                'total' => 0,
                'estado' => 0,
                'pin' => mt_rand(100000,999999),
                'establecimiento_id' => $user-> establecimiento_id,
                'seller_id' => $user-> id
            ]);   
            return response()->json(['message' => 'Compra creada con éxito.', 'id'=>$compra->id], 201);
        }

        return response()->json(['message' => 'El Usuario no tiene permisos para realizar esta accion'], 403);   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request, $purchaseId, $itemId)
    {    
        $user = Auth::user();

        try {
            $purchase = Compra::findOrFail($purchaseId);
            $product = Producto::findOrFail($itemId);
            $purchasePrice = 0;
            $stock= $product-> numeroStock;
            
            if($purchase-> estado === 1){
                return response()->json(['message' => 'No se pueden agregar productos. Compra Finalizada'], 403);
            }

            if($request-> itemsCount <= 0){
                return response()->json(['message' => "La compra debe contener un producto. Cantidad:($itemCount)"], 403);
            }

            if($stock < $request-> itemsCount){
                return response()->json(['message' => "El stock es insuficiente para la transaccion. Stock disponible($stock)"], 403);
            }

            if($user && isset($user-> rol_id) && $user-> rol_id != 1){

                $purchaseItemPrice= $this-> createOrUpdatePurchaseItem($product, $request-> itemsCount, $purchaseId, $itemId, $stock);
                if($purchaseItemPrice === null){
                    return response()->json(['message' => "El stock es insuficiente para la transaccion. Stock disponible($stock)"], 403);
                } 
                if($purchaseItemPrice === 0){
                    return response()->json(['message' => "La cantidad minima es 1."], 400);
                }

                $purchaseTotal= $purchaseItemPrice + $purchase-> total;
                $purchase->update([
                    'total' => $purchaseTotal
                ]);
            
                return response()->json(['message' => 'Compra actualizada.'], 201);    
            }

            return response()->json(['message' => 'El Usuario no tiene permisos para realizar esta accion'], 403);   
            
        } catch (NotFound $e) {
            return response()->json(['message' => 'Datos no encontrados'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }  
    }

    private function createOrUpdatePurchaseItem(Producto $product, $itemsCount, $purchaseId, $itemId, $stock)
    {
        $purchaseItem = ComprasProductos::where('compra_id', $purchaseId)->where('producto_id', $itemId)->first();

        if($purchaseItem){
            if($itemsCount < 0){
                $purchaseItemAmount= $itemsCount + $purchaseItem-> cantidad;
                if($purchaseItemAmount <= 0) return 0;
            }else{
                $purchaseItemAmount= $purchaseItem-> cantidad + $itemsCount; 
                if($stock < $purchaseItemAmount) return null;
            }

            $purchaseItemPrice= $purchaseItem-> precio * $purchaseItemAmount;
            
            $purchaseItem->update([
                'cantidad' => $purchaseItemAmount,
                'total' => $purchaseItemPrice
            ]);
            return $purchaseItem-> precio * $itemsCount;     
        }  

        $purchaseItemPrice= $itemsCount * $product-> precioProducto;
        $purchaseItem= ComprasProductos::create([
            'producto_id' => $product-> id,
            'compra_id' => $purchaseId,
            'nombre' => $product-> nombreProducto,
            'cantidad' => $itemsCount,
            'precio' => $product-> precioProducto,
            'total' => $purchaseItemPrice
        ]);
        return $purchaseItemPrice;
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function finalizarCompra(Request $request, $purchaseId)
    {
        try {
            $purchase = Compra::findOrFail($purchaseId);
            /* DEPENDE SI SE VA A REUTILIZAR PARA MOVIL
            
            $user = Auth::user();
            if(!$user || $purchase-> establecimiento_id !== $user-> establecimiento_id){
                return response()->json(['message' => 'El Usuario no tiene permisos para realizar esta accion'], 403);       
            }*/

            $itemList = $purchase-> productos;
            if($itemList->count() < 1){
                return response()->json(['message' => 'No se puede procesar la solicitud. No hay productos.'], 400);
            }
            $stockUpdate= $this->updateStock($itemList, $purchaseId);
            
            $purchase-> update([
                'estado' => 1
            ]);
            return response()->json(['message' => 'Compra Finalizada. Productos descontados del Stock'], 201);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Compra no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }     
    }
    
    private function updateStock($itemList, $purchaseId){
        foreach ($itemList as $item) {
            $itemId = $item->id;

            $product = ComprasProductos::where('producto_id', $itemId)->where('compra_id', $purchaseId)->first();
    
            if ($product) {
                $purchaseItemAmount = $product-> cantidad;
    
                $currentProduct = Producto::find($itemId);
    
                if ($currentProduct) {
                    $nuevaCantidad = $currentProduct-> numeroStock - $purchaseItemAmount;
                    
                    $currentProduct-> update([
                        'numeroStock' => max($nuevaCantidad, 0)
                    ]);
                }
            }
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCompra($id){
        $user = Auth::user();

        try {
            $purchase = Compra::findOrFail($id);
            if ((Auth::user()->rol_id == 2||Auth::user()->rol_id == 3) && $purchase ->establecimiento_id == Auth::user()->establecimiento_id||$purchase->user_id == Auth::id()) {

            return response()->json(['purchase' => $purchase], 200);   

        }
        return response()->json(['message'=>'El usuario no tiene permisos para ejecutar esta acción'], 403);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Compra no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    try {
        $purchase = Compra::findOrFail($id);

        if ((Auth::user()->rol_id == 2||Auth::user()->rol_id == 3) && $purchase ->establecimiento_id == Auth::user()->establecimiento_id||$purchase->user_id == Auth::id()) {
            if ($purchase->estado === 0) {
                    $purchase->productos()->detach();
                    $purchase->delete();

                    return response()->json(['message' => 'Compra Eliminada!', 'purchase' => $purchase], 200);
                } else {
                    return response()->json(['message' => 'No se puede realizar esta accion!'], 403);
                }    
            }
 else {
            return response()->json(['message' => 'No tienes permisos para eliminar esta compra'], 403);
        }
    } catch (NotFound $e) {
        return response()->json(['message' => 'Compra no encontrada'], 404);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al procesar la solicitud', 'error' => $e], 500);
    }
}

    public function destroyPurchaseItem($purchaseId, $itemId)
    {   
        try {
            $purchase = Compra::findOrFail($purchaseId);
            if ((Auth::user()->rol_id == 2||Auth::user()->rol_id == 3) && $purchase ->establecimiento_id == Auth::user()->establecimiento_id||$purchase->user_id == Auth::id()) {

            if($purchase-> estado === 0){      
                $product = ComprasProductos::where('producto_id', $itemId)->where('compra_id', $purchaseId)->first();
                if($product){        
                    $product->delete();

                    $total= 0;
                    $purchaseItems= ComprasProductos::where('compra_id', $purchaseId)->get();

                    foreach ($purchaseItems as $item) {
                        $total+= $item-> total;
                    }
                    $purchase->update([
                        'total'=> $total
                    ]);

                    return response()->json(['message' => 'Producto eliminado!', 'Item'=> $product], 200);
                }

            }
        }else{ return response()->json(['message' => 'No tienes permisos para eliminar esta compra'], 403);
        }

            return response()->json(['message' => 'No se puede realizar esta accion!'], 403);   

        } catch (NotFound $e) {
            return response()->json(['message' => 'Compra no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }
}
