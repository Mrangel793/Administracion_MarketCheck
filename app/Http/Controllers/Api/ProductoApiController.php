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
        return response()->json( $productos);
    }


    public function store(Request $request)
    {
        $usuario = Auth::user();

        if($usuario->establecimiento_id != null){
            $productos = new Producto();
            $productos->codigoProducto = $request->codigoProducto;
            $productos->nombreProducto = $request->nombreProducto;
            $productos->descripcionProducto = $request->descripcionProducto;
            $productos->precioProducto = $request->precioProducto;
            $productos->precioOriginal = $request->precioProducto;
            $productos->numeroStock = $request->numeroStock;
            $productos->estado = $request->estado;
            $productos->id_categoria = $request->id_categoria;
            $productos->id_subcategoria = $request->id_subcategoria;
            $productos->id_establecimiento = $usuario->establecimiento_id;
            $productos->save();

            return response()->json(['message' => 'Producto creado con éxito']);
        }else{
            return response()->json(['message' => 'El usuario no posee permisos para crear productos.']);
        }
    }

    public function show($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $data = [
            'id' => $producto->id,
            'codigoProducto' => $producto->codigoProducto,
            'nombreProducto' => $producto->nombreProducto,
            'descripcionProducto' => $producto->descripcionProducto,
            'precioProducto' => $producto->precioProducto,
            'numeroStock' => $producto->numeroStock,
            'estado' => $producto->estado,
            'id_categoria' => $producto->id_categoria,
            'id_subcategoria' => $producto->id_subcategoria,
        ];

        return response()->json($data);
    }

    public function activate($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $producto->estado = 1;
        $producto->update();

        return response()->json(['message' => 'Producto activado con éxito']);
    }

    public function deactivate($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $producto->estado = 0;
        $producto->update();

        return response()->json(['message' => 'Producto desactivado con éxito']);
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
        $producto->id_establecimiento = $usuario->establecimiento_id;
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