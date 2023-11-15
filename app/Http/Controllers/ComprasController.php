<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Compra;
use App\Models\ComprasProductos;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class ComprasController extends Controller
{
    protected $productosAcumulados;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compraProducto = ComprasProductos::all();
        return view('compras.index', compact('compraProducto'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('compras.create');
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$totalCompra = session('total_compra');
        //session()->forget('total_compra');
        //dd($totalCompra);
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

        return redirect()->route('compras.consultar');

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
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

    public function consultar(Request $request){
        $nombre = $request->nombre;
        $productos = [];

        if (!empty($nombre)) {
            $productos = Producto::where('nombreProducto', 'like', '%' . $nombre . '%')->get();
        }
        $productosAcumulados = session()->get('productos_acumulados', collect());
        if (!empty($productos)) {
             $productosAcumulados = $productosAcumulados->merge($productos);
        }
        session(['productos_acumulados' => $productosAcumulados]);

         return view('compras.consultar', ['productos' => $productosAcumulados]);
}

public function guardar(Request $request)
{
    $idCompra = session('compra_id');
    $compra = Compra::find($idCompra);
    $totalCompra = 0;
   
    foreach ($request->input('cantidadProducto') as $productoId => $cantidad) {
        
        $producto = Producto::find($productoId);

        if ($producto && is_numeric($cantidad) && $cantidad > 0) {
            $compraProducto = new ComprasProductos();
            $compraProducto->producto_id = $producto->id;
            $compraProducto->compra_id = $compra->id;
            $compraProducto->nombre = $producto->nombreProducto;
            $compraProducto->cantidad = $cantidad;
            $compraProducto->precio = $producto->precioProducto;
            $compraProducto->total = $compraProducto->cantidad * $compraProducto->precio;
            $compraProducto->save();
            $totalCompra += $compraProducto->total;
            $compra->total= $totalCompra;
            $compra->save();
            
        }
    }
    return redirect()->route('compras.index')->with('success', 'Compra guardada exitosamente.');
}



}