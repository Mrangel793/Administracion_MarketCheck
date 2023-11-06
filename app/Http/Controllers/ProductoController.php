<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\SubCategoria;

use Illuminate\Support\Facades\Auth;


class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = Auth::user();
        $productos = Producto::where('id_establecimiento', $usuario->establecimiento_id)->get();
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();
        $subcategorias = SubCategoria::all();


        return view('productos.create',compact('categorias','subcategorias'));
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

        $categorias = Categoria::all();
        $subcategorias = SubCategoria::all();

        $productos = new Producto();
        $productos ->codigoProducto = $request->codigoProducto;
        $productos ->nombreProducto = $request->nombreProducto;
        $productos ->descripcionProducto = $request->descripcionProducto;
        $productos ->precioProducto = $request->precioProducto;
        $productos ->numeroStock = $request->numeroStock;
        $productos ->estado = $request->estado;
        $productos->id_categoria = $request->id_categoria;
        $productos->id_subcategoria = $request->id_subcategoria;
        
        $productos ->id_establecimiento = $usuario->establecimiento_id;

        
        $productos->save();


        return redirect()->route('producto.index');
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
        $usuario = Auth::user();
        $categorias = Categoria::all();
        $subcategorias = SubCategoria::all();
        $producto = Producto::find($id);
        return view('productos.edit', compact('producto', 'subcategorias', 'categorias'));
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
        $usuario = Auth::user();

        $categorias = Categoria::all();
        $subcategorias = SubCategoria::all();

        $producto = Producto::find($id);
        $producto->codigoProducto = $request->codigoProducto;
        $producto->nombreProducto = $request->nombreProducto;
        $producto->descripcionProducto = $request->descripcionProducto;
        $producto->precioProducto = $request->precioProducto;
        $producto->numeroStock = $request->numeroStock;
        $producto->estado = $request->estado;
        $producto->id_categoria = $request->id_categoria;
        $producto->id_subcategoria = $request->id_subcategoria;
        
        // No necesitas actualizar el id_establecimiento si no ha cambiado
    
        $producto->update();

        return redirect()->route('producto.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productos = Producto::find($id);
        $productos->delete();
        return redirect()->route('producto.index');
    }
}
