<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\ComprasProductos;

use Carbon\Carbon;


//EN REVISION. HACE FALTA INDEX y STORE DE COMPRAS USUARIO APP <--- ELIMINAR EN PRODUCCION
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
        }
        return response()->json(['message' => 'El Usuario no tiene permisos para visualizar este Contenido'], 403);
        
    }

    public function productosCompra($compraid)
    {   
        try {
            $purchaseItems = ComprasProductos::where('compra_id', $compraid)->get();
            return response()->json(['items'=>$purchaseItems], 200);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
            $stock= $product-> numeroStock;
            
            if($purchase-> estado === 1){
                return response()->json(['message' => 'No se pueden agregar productos. Compra Finalizada'], 403);
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
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
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
            $purchase = Compra::findOrFail($id);
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
                $purchase->productos()->detach();
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

    public function destroyPurchaseItem($purchaseId, $itemId)
    {   
        try {
            $purchase = Compra::findOrFail($purchaseId);

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
            return response()->json(['message' => 'No se puede realizar esta accion!'], 403);   

        } catch (NotFound $e) {
            return response()->json(['message' => 'Compra no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud'], 500);
        }
    }
}
