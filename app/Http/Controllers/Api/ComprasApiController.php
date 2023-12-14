<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\ComprasProductos;
use Carbon\Carbon;



use Illuminate\Support\Facades\Auth;


class ComprasApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = Auth::user();
        if($usuario && isset($usuario->establecimiento_id)){
            $compras = Compra::where('establecimiento_id', $usuario->establecimiento_id)->get();
            return response()->json(['purchases'=> $compras, 200]);
        }else{
            return response()->json(['message' => 'El Usuario no tiene permisos de visualizar Compras']);
        }
    }

    public function productosCompra($compraid)
    {
        $compra = Compra::find($compraid);
        $compraProducto = ComprasProductos::where('compra_id', $compraid)->get();
        return response()->json(['items'=>$compraProducto, 200]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        session()->forget('productos_acumulados');
        $usuario = Auth::user();

        if($usuario && isset($usuario->establecimiento_id)){
            $compra = new Compra();
            $compra->hora = now()->format('H:i:s');;
            $compra->fecha = Carbon::now();
            $compra->total = 0;
            $compra->estado = 0;
            $compra->establecimiento_id = $usuario->establecimiento_id;
            $compra->save();
            session(['compra_id' => $compra->id]);
            return response()->json(['message' => 'Compra creada con éxito', 'id'=>$compra->id]);
        }else{
            return response()->json(['message' => 'El Usuario no tiene permisos de crear Compras']);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request, $idCompra, $productoId){
        
        $usuario = Auth::user();
        $compra = Compra::find($idCompra);

        if($usuario && isset($usuario->rol_id) && $usuario->rol_id!= 1 && $compra->estado != 1){

            $totalCompra = 0;
            $producto = Producto::find($productoId);
            $compraProducto = new ComprasProductos();

            $compraProducto->producto_id = $producto->id;
            $compraProducto->compra_id = $compra->id;
            $compraProducto->nombre = $producto->nombreProducto;
            $compraProducto->cantidad = $request->itemsCount;
            $compraProducto->precio = $producto->precioProducto;
            $compraProducto->total = $compraProducto->cantidad * $compraProducto->precio;
            $compraProducto->save();
            $totalCompra += $compraProducto->total;
            $compra->total= $totalCompra;
            $compra->save();            
        
            return response()->json(['message' => 'Productos agregados con éxito']);

        }else if($compra->estado == 1){
            return response()->json(['message' => 'No se pueden agregar productos. Compra Finalizada']);

        }else{
            throw error;
        } 
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function finalizarCompra(Request $request, $idCompra)
    {
        $usuario = Auth::user();

        $compra = Compra::find($idCompra);

        if ($compra) {
            $compra->estado = 1;
            $compra->save();

            $productosComprados = $compra->productos;

            foreach ($productosComprados as $productoComprado) {
                $productoId = $productoComprado->id;

                // Utiliza first() para obtener el primer resultado de la consulta
                $producto = ComprasProductos::where('producto_id', $productoId)->where('compra_id', $idCompra)->first();

                if ($producto) {
                    $cantidadComprada = $producto->cantidad;

                    $productoActual = Producto::find($productoId);

                    if ($productoActual) {
                        $nuevaCantidad = $productoActual->numeroStock - $cantidadComprada;

                        // Asegúrate de que la cantidad no sea negativa
                        $productoActual->numeroStock = max($nuevaCantidad, 0);
                        $productoActual->save();
                    }
                }
            }
            return response()->json(['message' => 'Compra Finalizada. Productos descontados del Stock']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCompra($id){

        $compra = Compra::find($id);
        return response()->json(['purchase' => $compra, 200]);
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
        //
    }
}
