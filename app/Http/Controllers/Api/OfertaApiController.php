<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

use App\Models\Oferta;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Establecimiento;
use App\Models\Oferta_Producto;

//TODO: EN REVISION, FALTA INDEX, SHOW PARA APP
class OfertaApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if($user && isset($user-> establecimiento_id)){
            $offers = Oferta::where('establecimiento_id', $user-> establecimiento_id)->get();
            return response()->json(['offers'=> $offers]);
        }
        return response()->json(['message'=> 'El usuario no tiene permisos para visualizar este Contenido'], 403);
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        if($user && isset($user-> establecimiento_id)){
            
            $offer = Oferta::create([
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'nombre' => $request-> nombre,
                'descripcion' => $request-> descripcion,
                'numero_stock' => $request-> numero_stock,
                'estado' => $request-> estado,
                'establecimiento_id' => $user-> establecimiento_id,
                'imagen' => $request-> imagen
            ]);
            return response()->json(['message' => 'Oferta creada con éxito', 'offer' => $offer], 201);
        }

        return response()->json(['message' => 'El usuario no tiene permisos para visualizar este Contenido'], 403);
    }


    public function show($id){   
        try {    
            $user = Auth::user();
            $offert = Oferta::findOrFail($id);
        
            if ($offert-> establecimiento_id !== $user-> establecimiento_id) {
                return response()->json(['message' => 'No tienes permiso para ver esta oferta.'], 403);
            }
            return response()->json(['offer' => $offert], 200);

        } catch (NotFound $e) {
            return response()->json(['message' => 'La oferta no existe.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        } 
    }


    public function activarOferta($ofertaId){
        try {    
            $offer = Oferta::findOrFail($ofertaId);

            $offerItems = $offer-> productos; 
        
                foreach ($offerItems as $item) {
                    $offerPrice = $item->pivot->precio_oferta; 
        
                    if ($item->precioProducto !== null) {
                        $item->update([
                            'precioProducto' => $offerPrice
                        ]);
                    }
                }

            $offer->update([
                'estado' => 1
            ]);
            return response()->json(['message' => 'Oferta activada con éxito', 'offer' => $offer]);    

        } catch (NotFound $e) {
            return response()->json(['message' => 'La oferta no existe.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        } 
    }


    public function desactivarOferta($ofertaId)
    {
        try {    
            $offer = Oferta::findOrFail($ofertaId);

            $offerItems = $offer-> productos; 
        
                foreach ($offerItems as $item) {
                    $item->update([
                        'precioProducto' => $item-> precioOriginal
                    ]);      
                }

            $offer->update([
                'estado' => 0
        ]);
        return response()->json(['message' => 'Oferta desactivada.', 'offer' => $offer]);    

        } catch (NotFound $e) {
            return response()->json(['message' => 'La oferta no existe.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=> 'Error al procesar la solicitud'], 500);
        } 
    }
   

    public function guardarProductos(Request $request, $ofertaId)
    {
        try {    
            $offer = Oferta::findOrFail($ofertaId);
            $productId = $request-> producto_id;
            $percent = $request-> porcentaje;

            $product = Producto::find($productId);  
            if(!$product){
                return response()->json(['message' => 'No se encontró el Producto.'], 404);
            } 

            $discountPrice = $product-> precioProducto - ($product-> precioProducto * $percent / 100);

            $offerItem = Oferta_Producto::create([
                'id_producto' => $productId,
                'id_oferta' => $offer-> id,
                'porcentaje' => $percent,
                'precio_oferta' => $discountPrice
            ]);
    
            if ($offer-> estado === 1) {
                $product->update([
                    'precioProducto' => $discountPrice
                ]);
            }
    
            return response()->json(['message' => "$product-> nombreProducto agregado con éxito a la oferta", 'oferta_producto' => $offerItem], 200);

        } catch (NotFound $e) {
            return response()->json(['message' => 'No se encontró la oferta.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=> 'Error al procesar la solicitud'], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {    
            $offer = Oferta::findOrFail($id);
    
            $offer->update([
                'fecha_inicio' => $request-> fecha_inicio,
                'fecha_fin' => $request-> fecha_fin,
                'nombre' => $request-> nombre,
                'descripcion' => $request-> descripcion,
                'numero_stock' => $request-> numero_stock,
                'estado' => $request-> estado,
                'imagen' => $request-> imagen
            ]);
    
            return response()->json(['message' => 'Oferta actualizada con éxito.'], 200); 

        } catch (NotFound $e) {
            return response()->json(['message' => 'No se encontró la oferta.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=> 'Error al procesar la solicitud'], 500);
        }
    }

    //TODO: SE PUEDE MEJORAR HACIENDO MODULAR LA LOGICA
    public function editarPorcentaje(Request $request, $ofertaId, $productoId)
    {
        try {    
            $offer = Oferta::findOrFail($ofertaId);
    
            $product = Producto::find($productoId);
            if (!$product) {
                return response()->json(['message' => 'No se encontró el producto.'], 404);
            }
    
            $user = Auth::user();
            if ($offer-> establecimiento_id !== $user-> establecimiento_id || $product-> id_establecimiento !== $user-> establecimiento_id) {
                return response()->json(['message' => 'No tienes permiso para editar el porcentaje de este producto en la oferta.'], 403);
            }
    
            $request->validate([
                'porcentaje' => 'required|numeric|min:0|max:100',
            ]);
            $percent = $request-> porcentaje;
    
            $precioDescuento = $product-> precioOriginal - ($product-> precioOriginal * $percent / 100);
    
            $offerItem = Oferta_Producto::where('id_oferta', $offer-> id)
            ->where('id_producto', $product-> id)
            ->first();
    
            if (!$offerItem) {
                return response()->json(['message' => 'El producto no está asociado a la oferta.'], 400);
            }

            $offerItem->update([
                'porcentaje' => $percent,
                'precio_oferta' => $precioDescuento
            ]);

    
            if ($offer-> estado === 1) {
                $product->update([
                    'precioProducto' => $precioDescuento
                ]);
            }
    
            return response()->json(['message' => 'Porcentaje del producto actualizado con éxito.', 'oferta_producto' => $ofertaProducto]);
            
        } catch (NotFound $e) {
            return response()->json(['message' => 'La oferta no existe.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        }
    }


    public function productosOferta($offerId)
    {
        try {    
            $offer = Oferta::findOrFail($offerId);
            $offerItems = $offer-> productos;
    
            return response()->json(['offerItems' => $offerItems], 200);  

        } catch (NotFound $e) {
            return response()->json(['message' => 'No se encontró la oferta.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        }
    }   


    public function eliminarProducto($offerId, $productId)
    {
        try {    
            $offer = Oferta::findOrFail($offerId);
    
            $product = Producto::find($productId);
            if (!$product) {
                return response()->json(['message' => 'No se encontró el producto.'], 404);
            }
    
            $user = Auth::user();
            if ($offer-> establecimiento_id !== $user-> establecimiento_id || $product-> id_establecimiento !== $user-> establecimiento_id) {
                return response()->json(['message' => 'No tienes permiso para eliminar este producto de la oferta.'], 403);
            }
    
            $offer->productos()->detach($product-> id);
            $product->update([
                'precioProducto' => $product-> precioOriginal
            ]);
    
            return response()->json(['message' => 'Producto eliminado de la oferta con éxito']);

        } catch (NotFound $e) {
            return response()->json(['message' => 'No se encontró la oferta.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        }
    }

    public function destroy($id)
    {
        try {    
            $offer = Oferta::findOrFail($id);
    
            $user = Auth::user();
            if ($offer-> establecimiento_id !== $user-> establecimiento_id) {
                return response()->json(['message' => 'No tienes permiso para eliminar esta oferta.'], 403);
            }
    
            $offerItems = $offer-> productos;
            foreach ($offerItems as $item) {
                $item->update([
                    'precioProducto' => $item-> precioOriginal
                ]);
            }
            $offer->productos()->detach();
            $offer->delete();
    
            return response()->json(['message' => 'Oferta eliminada con éxito.']);
            
        } catch (NotFound $e) {
            return response()->json(['message' => 'No se encontró la oferta.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        }
    }

}