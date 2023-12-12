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
        $compra = Compra::where('establecimiento_id', $usuario->establecimiento_id)->get();
        return response()->json(['compra' => $compra]);
    }

    public function productosCompra($compraid)
    {
        $compra = Compra::find($compraid);
        $compraProducto = ComprasProductos::where('compra_id', $compraid)->get();
        return response()->json(['compra' => $compraProducto]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuario = Auth::user();
        
        session()->forget('productos_acumulados');

        $compras = new Compra();
        $compras->hora = now()->format('H:i:s');;
        $compras->fecha = Carbon::now();
        $compras->total = 0;
        $compras->estado =0;
        $compras->establecimiento_id = $usuario->establecimiento_id;
        $compras->save();
        session(['compra_id' => $compras->id]);
        return response()->json(['message' => 'Compra creada con éxito']);

    }

    public function guardar(Request $request, $idCompra,$productoId){
        $compra = Compra::find($idCompra);
        $totalCompra = 0;
        $producto = Producto::find($productoId);

        if($compra->estado ==1){
            return response()->json(['message' => 'No se pueden agregar productos. Compra Finalizada']);

        }else{

            $compraProducto = new ComprasProductos();
            $compraProducto->producto_id = $producto->id;
            $compraProducto->compra_id = $compra->id;
            $compraProducto->nombre = $producto->nombreProducto;
            $compraProducto->cantidad = $request->cantidad;
            $compraProducto->precio = $producto->precioProducto;
            $compraProducto->total = $compraProducto->cantidad * $compraProducto->precio;
            $compraProducto->save();
            $totalCompra += $compraProducto->total;
            $compra->total= $totalCompra;
            $compra->save();            
        
            return response()->json(['message' => 'Productos agregados con éxito']);
        } 
    }

public function finalizarCompra($idCompra)
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
    public function show($id)
    {
        //
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
