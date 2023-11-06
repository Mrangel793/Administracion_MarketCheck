<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\SubCategoria;
use Illuminate\Support\Facades\Auth;

class ProductoApiController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $productos = Producto::where('id_establecimiento', $usuario->establecimiento_id)->get();
        return response()->json(['productos' => $productos]);
    }


    public function store(Request $request)
    {
        $usuario = Auth::user();

        $productos = new Producto();
        $productos->codigoProducto = $request->codigoProducto;
        $productos->nombreProducto = $request->nombreProducto;
        $productos->descripcionProducto = $request->descripcionProducto;
        $productos->precioProducto = $request->precioProducto;
        $productos->numeroStock = $request->numeroStock;
        $productos->estado = $request->estado;
        $productos->id_categoria = $request->id_categoria;
        $productos->id_subcategoria = $request->id_subcategoria;
        $productos->id_establecimiento = $usuario->establecimiento_id;
        $productos->save();

        return response()->json(['message' => 'Producto creado con éxito']);
    }

    

    public function update(Request $request, $id)
    {
        $usuario = Auth::user();

        $producto = Producto::find($id);
        $producto->codigoProducto = $request->codigoProducto;
        $producto->nombreProducto = $request->nombreProducto;
        $producto->descripcionProducto = $request->descripcionProducto;
        $producto->precioProducto = $request->precioProducto;
        $producto->numeroStock = $request->numeroStock;
        $producto->estado = $request->estado;
        $producto->id_categoria = $request->id_categoria;
        $producto->id_subcategoria = $request->id_subcategoria;
        $producto->update();

        return response()->json(['message' => 'Producto actualizado con éxito']);
    }

    public function destroy($id)
    {
        $producto = Producto::find($id);
        $producto->delete();
        return response()->json(['message' => 'Producto eliminado con éxito']);
    }
}