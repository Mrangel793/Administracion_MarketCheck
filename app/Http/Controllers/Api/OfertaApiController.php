<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

use App\Models\Image;
use App\Models\Oferta;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Establecimiento;
use App\Models\Oferta_Producto;

//TODO: REVISADO
class OfertaApiController extends Controller
{   
    public function offersMobileApp(){
        $offers = Oferta::where('estado', 1)->get();
        return response()->json(['offers'=> $offers]);
    }

    public function showOfferMobileApp($id){   
        try {    
            $user = Auth::user();
            $offer = Oferta::findOrFail($id);
            if($offer-> estado !== 1){
                return response()->json(['message' => 'La oferta no se encuentra disponible'], 404);
            }
            return response()->json(['offer' => $offer], 200);

        } catch (NotFound $e) {
            return response()->json(['message' => 'La oferta no existe.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        } 
    }


    public function index(){
        $user = Auth::user();
        if($user && isset($user-> establecimiento_id)){
            $offers = Oferta::where('establecimiento_id', $user-> establecimiento_id)->get();
            return response()->json(['offers'=> $offers]);
        }
        return response()->json(['message'=> 'El usuario no tiene permisos para visualizar este Contenido'], 403);
    }


    public function store(Request $request){   
        $request->validate([
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            'nombre' => 'required',
            'descripcion' => 'required',
            'estado' => 'required',
            'establecimiento_id' => 'required'
        ]);

        $user = Auth::user();
        if($user && isset($user-> establecimiento_id)){
            $offer = Oferta::create([
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'nombre' => $request-> nombre,
                'descripcion' => $request-> descripcion,
                'estado' => $request-> estado,
                'establecimiento_id' => $user-> establecimiento_id,
                'image' => null,
            ]);
            return response()->json(['message' => 'Oferta creada con éxito', 'offer' => $offer], 201);
        }

        return response()->json(['message' => 'El usuario no tiene permisos para visualizar este Contenido'], 403);
    }


    public function show($id){   
        try {    
            $user = Auth::user();
            $offer = Oferta::findOrFail($id);
        
            if ($offer-> establecimiento_id !== $user-> establecimiento_id) {
                return response()->json(['message' => 'No tienes permiso para ver esta oferta.'], 403);
            }
            return response()->json(['offer' => $offer], 200);

        } catch (NotFound $e) {
            return response()->json(['message' => 'La oferta no existe.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        } 
    }


    public function activateOrDesactivateOffer($ofertaId){
        try {    
            $offer = Oferta::findOrFail($ofertaId);
            $offerItems = $offer-> productos;
            $option= $offer-> estado;

            switch ($option) {
                case 0:
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
                    return response()->json(['message' => 'Oferta activada.', 'offer' => $offer]);

                case 1:
                    foreach ($offerItems as $item) {
                        $item->update([
                            'precioProducto' => $item-> precioOriginal
                        ]);      
                    }

                    $offer->update([
                        'estado' => 0
                    ]);
                    return response()->json(['message' => 'Oferta desactivada.', 'offer' => $offer]);    
                
                default:
                    return response()->json(['message' => 'Estado de oferta incorrecto.'], 400);
                    break;
            }
                
        } catch (NotFound $e) {
            return response()->json(['message' => 'La oferta no existe.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        } 
    }


    public function guardarProductos(Request $request, $offerId){
        $user = Auth::user();
        $request->validate([
            'percent' => 'required|numeric|min:1|max:99',
        ]);
        try {    
            $offer = Oferta::findOrFail($offerId);
            $productId = $request-> product;
            $percent = $request-> percent;

            $product = Producto::find($productId);  
            if(!$product){
                return response()->json(['message' => 'No se encontró el Producto.'], 404);
            }

            if ($offer-> establecimiento_id !== $user-> establecimiento_id || $product-> id_establecimiento !== $user-> establecimiento_id) {
                return response()->json(['message' => 'No tienes permiso para crear o editar ofertas.'], 403);
            }
            $discountPrice = $product-> precioOriginal - ($product-> precioOriginal * $percent / 100);

            $offerItem = Oferta_Producto::where('id_oferta', $offer-> id)
            ->where('id_producto', $product-> id)
            ->first();
    
            if ($offerItem) {
                $offerItem->update([
                    'porcentaje' => $percent,
                    'precio_oferta' => $discountPrice
                ]);
                $result= $this-> editProductPrice($offer, $product, $discountPrice);
                return response()->json(['message' => 'Oferta actualizado con éxito.', 'oferta_producto' => $offerItem, 'result' => $result]);
            }

            $offerItem = Oferta_Producto::create([
                'id_producto' => $productId,
                'id_oferta' => $offer-> id,
                'porcentaje' => $percent,
                'precio_oferta' => $discountPrice
            ]);
    
            $result= $this-> editProductPrice($offer, $product, $discountPrice);

            return response()->json(['message' => "Producto agregado con éxito a la oferta", 'oferta_producto' => $offerItem, 'result' => $result], 200);

        } catch (NotFound $e) {
            return response()->json(['message' => 'No se encontró la oferta.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=> 'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }

    private function editProductPrice(Oferta $offer, Producto $product, $discountPrice){                    
        if ($offer-> estado === 1) {
            $product->update([
                'precioProducto' => $discountPrice
            ]);
            return true;
        }
        return false;
    }

    public function update(Request $request, $id){
        try {    
            $offer = Oferta::findOrFail($id);
    
            $offer->update([
                'fecha_inicio' => $request-> fecha_inicio,
                'fecha_fin' => $request-> fecha_fin,
                'nombre' => $request-> nombre,
                'descripcion' => $request-> descripcion,
                'estado' => $request-> estado,
            ]);
    
            return response()->json(['message' => 'Oferta actualizada con éxito.', 'offer'=> $offer], 201); 

        } catch (NotFound $e) {
            return response()->json(['message' => 'No se encontró la oferta.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=> 'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }

    public function updateImageField(Request $request, $id){  
        try {
            $offer = Oferta::FindOrFail($id);
            $image= $request->input('imagen');

            if($image){
                $offer->update([
                    'imagen' => $image
                ]);
            }
            return response()->json(['message' => 'Actualizacion éxitosa.', 'I'=>$image],201);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Tienda no encontrada.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud.', 'error'=> $e], 500);
        }
    }

    public function productosOferta($offerId){
        try {    
            $offer = Oferta::findOrFail($offerId);
            $offerItems = $offer-> productos;
    
            return response()->json(['offerItems' => $offerItems], 200);  

        } catch (NotFound $e) {
            return response()->json(['message' => 'No se encontró la oferta.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
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
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
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

            $imageId= $offer-> imagen;
            $image= Image::find($imageId);
            if($image){
                $path= $image->imagePath;
                if($path) Storage::delete("public/images/$path");
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
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }

}