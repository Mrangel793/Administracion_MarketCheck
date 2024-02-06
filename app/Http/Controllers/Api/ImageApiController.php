<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;

use App\Models\Image;
use App\Models\Establecimiento;

class ImageApiController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Funcionando'], 200);
    }

    
    public function store(Request $request)
    {   
        $option= $request->input('option');
        $storeId= null;
        $offerId= null;

        switch ($option) {
            case 'store':
                $storeId= $request->input('id');
                break;

            case 'offer':
                $offerId= $request->input('id');
                break;

            case 'update':
                return $this->updateImage($request, $request->input('id'));        

            default:
            return response()->json(['message' => 'No se proporcionó datos validos.'], 400); 
                break;
        }
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $path = $image->store('public/images');
            $imageName = basename($path);

            $newImage=Image::create([
                'imagePath' => $imageName,
                'establecimiento_id' => $storeId,
                'oferta_id' => $offerId 
            ]);
        
            return response()->json(['message' => 'Imagen almacenada con éxito', 'path' => $newImage->id], 201);
        }

        return response()->json(['message' => 'No se proporcionó ningún archivo de imagen', 'path' => null]);
    }

    private function updateImage(Request $request, $imageId){
        try {    
            $imageInstance = Image::findOrFail($imageId);
    
            if ($request-> hasFile('image')) {
                $path= $imageInstance->imagePath;
                if($path) Storage::delete("public/images/$path");
    
                $image = $request->file('image');
    
                $path = $image->store('public/images');
                $imageName = basename($path);
    
                $imageInstance->update([
                    'imagePath' => $imageName, 
                ]);
            
                return response()->json(['message' => 'Imagen actualizada con éxito'], 201);
            }else{
                return response()->json(['message' => 'No se proporcionó ningún archivo de imagen']);
            }
    
            } catch (NotFound $e) {
                return response()->json(['message' => 'Imagen no encontrada.'], 404);
    
            } catch (\Exception $e) {
                return response()->json(['message'=>'Error al procesar la solicitud.', 'error'=> $e], 500);
            }
    }


    public function show($id)
    {
        try {
            $image= Image::findOrFail($id);
            $path= $image->imagePath;
    
            $rutaArchivo = "public/images/$path"; 
    
            if (Storage::exists($rutaArchivo)) {
                $imageUrl=Storage::url("public/images/$path");
                return response()->json(['image_url'=>$imageUrl,200]);
            }
    
            return response()->json(['mensaje' => 'Archivo no encontrado'], 404);
            
        } catch (NotFound $e) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }

    public function update(Request $request, $id)
    {
    
    }

    public function destroy($id)
    {   
        try {
            $image = Image::findOrFail($id); 
            $path= $image->imagePath;
            if($path) Storage::delete("public/images/$path");
            
            $image->delete();
            return response()->json(['message' => 'Imagen eliminada con éxito'], 200);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud', 'error'=> $e], 500);
            
        }
    }
}
