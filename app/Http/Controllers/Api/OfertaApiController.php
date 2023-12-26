<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Establecimiento;
use App\Models\Producto;
use App\Models\Oferta_Producto;

class OfertaApiController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $ofertas = Oferta::where('establecimiento_id', $usuario->establecimiento_id)->get();
        //$ofertas = Oferta::all();
        //return response()->json(['ofertas' => $ofertas]);
        return response()->json($ofertas, 200);
    }

    public function create()
    {
        $categorias = Categoria::all();
        return response()->json(['categorias' => $categorias]);
    }


    public function store(Request $request)
    {
        $usuario = Auth::user();
        $oferta = new Oferta();
        $oferta->fecha_inicio = $request->fecha_inicio;
        $oferta->fecha_fin = $request->fecha_fin;
        $oferta->nombre = $request->nombre;
        $oferta->descripcion = $request->descripcion;
        $oferta->numero_stock = $request->numero_stock;
        $oferta->estado = $request->estado;
        $oferta->establecimiento_id = $usuario->establecimiento_id;
        $oferta->establecimiento_id = $request->establecimiento_id;    
        if($request->hasFile('imagen')){
            $imagenes = $request->file('imagen')->store('public/imagenes');
            $url =Storage::url($imagenes);
            $oferta-> imagen = $url;
            }else{  
                echo "No se cargo imagen";
                }
        $oferta->save();
    
        //return response()->json(['message' => 'Oferta creada con éxito', 'oferta' => $oferta]);
        return response()->json($oferta);

    }

        public function show($id)
    {
        //$usuario = Auth::user();
        //$oferta = Oferta::find($id);
        $oferta = Oferta::where('id', $id)->get();

        if (!$oferta) {
            return response()->json(['error' => 'La oferta no existe.'], 404);
        }  
        /*if ($oferta->establecimiento_id !== $usuario->establecimiento_id) {
            return response()->json(['error' => 'No tienes permiso para ver esta oferta.'], 403);
        }*/
        return response()->json($oferta, 200);
    }


        public function activarOferta($ofertaId)
    {
        $oferta = Oferta::find($ofertaId);
        if (!$oferta) {
            return response()->json(['error' => 'La oferta no existe.'], 404);
        }
        $productosOferta = $oferta->productos; 
            foreach ($productosOferta as $producto) {
                $precioOferta = $producto->pivot->precio_oferta; 
      
                if ($producto->precioProducto !== null) {
                    $producto->precioProducto = $precioOferta;
                    $producto->update();
                }
            }
        $oferta->estado = 1; 
        $oferta->save();

        return response()->json(['message' => 'Oferta activada con éxito', 'oferta' => $oferta]);
    }

    public function desactivarOferta($ofertaId)
    {
        $oferta = Oferta::find($ofertaId);
        $productosOferta = $oferta->productos;
       foreach ($productosOferta as $producto) {
           $producto->precioProducto = $producto->precioOriginal;
           $producto->save();
       }
        if (!$oferta) {
            return response()->json(['error' => 'La oferta no existe.'], 404);
        }
        $oferta->estado = 0; // Desactiva la oferta
        $oferta->save();
      return response()->json(['message' => 'Oferta desactivada con éxito', 'oferta' => $oferta]);
    }

   

    public function guardarProductos(Request $request, $ofertaId)
    {
        $oferta = Oferta::find($ofertaId);
        if (!$oferta) {
            return response()->json(['error' => 'La oferta no existe.'], 404);

        }
        $porcentaje = $request->input('porcentaje');
        $productoId = $request->input('producto_id');
        $producto = Producto::find($productoId);
        if (!$producto) {
            return response()->json(['error' => 'El producto no existe.'], 404);
        }
        $precioDescuento = $producto->precioProducto - ($producto->precioProducto * $porcentaje / 100);
        $ofertaProducto = new Oferta_Producto();
        $ofertaProducto->id_producto = $productoId;
        $ofertaProducto->id_oferta = $oferta->id;
        $ofertaProducto->porcentaje = $porcentaje;
        $ofertaProducto->precio_oferta = $precioDescuento;
        $ofertaProducto->save();
        if ($oferta->estado === 1) {
            $producto->precioProducto = $precioDescuento;
            $producto->save();
        }

        return response()->json(['message' => "$producto->nombreProducto agregado con éxito a la oferta", 'oferta_producto' => $ofertaProducto]);
    }



    public function edit($id)
    {
        $categorias = Categoria::all();
        $oferta = Oferta::find($id);
        return response()->json(['oferta' => $oferta, 'categorias' => $categorias]);
    }

    public function update(Request $request, $id)
    {
        $oferta = Oferta::find($id);

        if (!$oferta) {
            return response()->json(['error' => 'La oferta no existe.'], 404);
        }

        $oferta->fecha_inicio = $request->fecha_inicio;
        $oferta->fecha_fin = $request->fecha_fin;
        $oferta->nombre = $request->nombre;
        $oferta->descripcion = $request->descripcion;
        $oferta->numero_stock = $request->numero_stock;
        $oferta->estado = $request->estado;
        $oferta->imagen = $request->imagen;

        $oferta->save();

        //return response()->json(['message' => 'Oferta actualizada con éxito.']);
        return response()->json($oferta);

    }


    public function editarPorcentaje(Request $request, $ofertaId, $productoId)
    {
        $oferta = Oferta::find($ofertaId);

        if (!$oferta) {
            return response()->json(['error' => 'La oferta no existe.'], 404);
        }

        $producto = Producto::find($productoId);

        if (!$producto) {
            return response()->json(['error' => 'El producto no existe.'], 404);
        }

        $usuario = Auth::user();
        if ($oferta->establecimiento_id !== $usuario->establecimiento_id || $producto->id_establecimiento !== $usuario->establecimiento_id) {
            return response()->json(['error' => 'No tienes permiso para editar el porcentaje de este producto en la oferta.'], 403);
        }

        $request->validate([
            'porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        $porcentaje = $request->input('porcentaje');

        $precioDescuento = $producto->precioOriginal - ($producto->precioOriginal * $porcentaje / 100);

        $ofertaProducto = Oferta_Producto::where('id_oferta', $oferta->id)
    ->where('id_producto', $producto->id)
    ->first();

        if (!$ofertaProducto) {
            return response()->json(['error' => 'El producto no está asociado a la oferta.'], 400);
        }


        $ofertaProducto->porcentaje = $porcentaje;
        $ofertaProducto->precio_oferta = $precioDescuento;
        $ofertaProducto->save();

        if ($oferta->estado === 1) {
            $producto->precioProducto = $precioDescuento;
            $producto->save();
        }

        return response()->json(['message' => 'Porcentaje del producto actualizado con éxito.', 'oferta_producto' => $ofertaProducto]);
    }

    public function productosOferta($ofertaId)
    {
        $oferta = Oferta::find($ofertaId);
        if (!$oferta) {
            return response()->json(['error' => 'La oferta no existe.'], 404);
        }
        $productosOferta = $oferta->productos;
        return response()->json(['productos_oferta' => $productosOferta]);
    }   

    public function eliminarProducto($ofertaId, $productoId)
    {
        $oferta = Oferta::find($ofertaId);
        if (!$oferta) {
            return response()->json(['error' => 'La oferta no existe.'], 404);
        }
        $producto = Producto::find($productoId);
        if (!$producto) {
            return response()->json(['error' => 'El producto no existe.'], 404);
        }
        $usuario = Auth::user();
        if ($oferta->establecimiento_id !== $usuario->establecimiento_id || $producto->id_establecimiento !== $usuario->establecimiento_id) {
            return response()->json(['error' => 'No tienes permiso para eliminar este producto de la oferta.'], 403);
        }
        $oferta->productos()->detach($producto->id);
        $producto->precioProducto = $producto->precioOriginal;
        $producto->save();
        return response()->json(['message' => 'Producto eliminado de la oferta con éxito']);
    }

    public function destroy($id)
    {
        $oferta = Oferta::find($id);
        if (!$oferta) {
            return response()->json(['error' => 'La oferta no existe.'], 404);
        }
        /*$usuario = Auth::user();
        if ($oferta->establecimiento_id !== $usuario->establecimiento_id) {
            return response()->json(['error' => 'No tienes permiso para eliminar esta oferta.'], 403);
        }*/
        $productosOferta = $oferta->productos;
        foreach ($productosOferta as $producto) {
            $producto->precioProducto = $producto->precioOriginal;
            $producto->save();
        }
        $oferta->productos()->detach();
        $oferta->delete();
        return response()->json(['message' => 'Oferta eliminada con éxito.']);
    }

}