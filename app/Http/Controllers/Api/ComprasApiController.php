<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\ComprasProductos;

use Carbon\Carbon;


//EN REVISION. HACE FALTA INDEX y STORE DE COMPRAS USUARIO APP
class ComprasApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();     
        if($user && isset($user->establecimiento_id)){ 
            $purchases = Compra::where('establecimiento_id', $user->establecimiento_id)->get();
            return response()->json(['purchases'=> $purchases], 200);
        }else{
            return response()->json(['message' => 'El Usuario no tiene permisos para visualizar este Contenido'], 403);
        }
    }

    public function productosCompra($compraid)
    {   
        $user = Auth::user();

        if($user && isset($user->establecimiento_id)){
            try {
                $purchaseItems = ComprasProductos::where('compra_id', $compraid)
                ->where('establecimiento_id', $user->establecimiento_id)
                ->findOrFail();
                return response()->json(['items'=>$purchaseItems], 200);
    
            } catch (NotFound $e) {
                return response()->json(['message' => 'Datos no encontrados'], 404);
    
            } catch (\Exception $e) {
                return response()->json(['message'=>'Error al procesar la solicitud'], 500);
            }
        }
        return response()->json(['message' => 'El Usuario no tiene permisos para visualizar este Contenido'], 403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //session()->forget('productos_acumulados');
        //session(['compra_id' => $compra->id]);
        $user = Auth::user();

        if($user && isset($user-> establecimiento_id)){
            $compra = Compra::create([
                'hora' => now()->format('H:i:s'),
                'fecha' => Carbon::now(),
                'total' => 0,
                'estado' => 0,
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
            
            if($purchase-> estado == 1){
                return response()->json(['message' => 'No se pueden agregar productos. Compra Finalizada'], 403);
            }

            if($product-> numeroStock < $request-> itemsCount){
                return response()->json(['message' => 'El stock es insuficiente para la transaccion'], 403);
            }

            if($user && isset($user-> rol_id) && $user-> rol_id != 1){
   
                $purchasePrice= createOrUpdatePurchaseItem($product, $request, $purchaseId, $itemId);
                
                $purchase->update([
                    'total' => $purchasePrice
                ]);
            
                return response()->json(['message' => 'Productos agregados con éxito'], 201);    
            }

            return response()->json(['message' => 'El Usuario no tiene permisos para realizar esta accion'], 403);   
            
        } catch (NotFound $e) {
            return response()->json(['message' => 'Datos no encontrados'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        }      
    }

    private function createOrUpdatePurchaseItem(Producto $product, Request $request, $purchaseId, $itemId){

        $purchaseItem = ComprasProductos::where('compra_id', $purchaseId)->where('producto_id', $itemId)->first();

        if($purchaseItem){
            $purchaseItemAmount= $purchaseItem-> cantidad + $request-> itemsCount;
            $purchaseItemPrice= $purchaseItem-> precio * $purchaseItemAmount;
            
            $purchaseItem->update([
                'cantidad' => $purchaseItemAmount,
                'total' => $purchaseItemPrice
            ]);
            return $purchaseItemPrice;
            
        }else{   
            $purchaseItemPrice= $request-> $itemsCount * $product-> precio;
            $purchaseItem= ComprasProductos::create([
                'producto_id' => $product-> id,
                'compra_id' => $purchaseId,
                'nombre' => $product-> nombreProducto,
                'cantidad' => $request-> itemsCount,
                'precio' => $product-> precioProducto,
                'total' => $purchaseItemPrice
            ]);
            return $purchaseItemPrice;
        }
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
        $usuario = Auth::user();
        try {
            $purchase = Compra::findOrFail($purchaseId);
            $purchase-> update([
                'estado' => 1
            ]);
            
            $itemList = $compra-> productos;
            updateStock($itemList, $purchaseId);

            return response()->json(['message' => 'Compra Finalizada. Productos descontados del Stock'], 201);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Compra no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
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
        try {
            $purchase = Compra::FindOrFail($id);
            return response()->json(['purchase' => $purchase], 200);   

        } catch (NotFound $e) {
            return response()->json(['message' => 'Compra no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
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

            if($purchase-> estado === 0){
                $purchase->delete();
                return response()->json(['message' => 'Compra Eliminada!', 'purchase'=> $purchase], 200);
            }
            return response()->json(['message' => 'No se puede realizar esta accion!'], 403);   

        } catch (NotFound $e) {
            return response()->json(['message' => 'Compra no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud'], 500);
        }
    }
}
