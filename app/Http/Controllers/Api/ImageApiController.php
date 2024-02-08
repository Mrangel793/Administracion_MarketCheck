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
        //$storeId= null;
        //$offerId= null;
        switch ($option) {

            case 'storage':                
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
        
                    $path = $image->store('public/images');
                    $imagePath = basename($path);
                    return response()->json(['message' => 'Imagen almacenada con éxito', 'path' => $imagePath], 201);
                }
                return response()->json(['message' => 'No se proporcionó ningún archivo de imagen', 'path' => null]);

            case 'update':
                return $this->updateImage($request, $request->input('imagePath'));        

            default:
            return response()->json(['message' => 'No se proporcionó datos validos.'], 400); 
                break;
        }
            //ANTIGUO
           /*$newImage=Image::create([
                'imagePath' => $imagePath,
                'establecimiento_id' => $storeId,
                'oferta_id' => $offerId 
            ]);*/
    }

    private function updateImage(Request $request, $imagePath){
        try {     
            if ($request-> hasFile('image')) {
                if($imagePath){
                    Storage::delete("public/images/$imagePath");
                }else{
                    return response()->json(['message' => 'No se proporcionó el path de la imagen actual'], 400);
                }
    
                $image = $request->file('image');
    
                $path = $image->store('public/images');
                $newImagePath = basename($path);
            
                return response()->json(['message' => 'Imagen actualizada con éxito', "path"=> $newImagePath], 201);
            //ANTIGUO
           /* $imageInstance = Image::findOrFail($imageId);
    
            if ($request-> hasFile('image')) {
                $path= $imageInstance->imagePath;
                if($path) Storage::delete("public/images/$path");
    
                $image = $request->file('image');
    
                $path = $image->store('public/images');
                $imageName = basename($path);
    
                $imageInstance->update([
                    'imagePath' => $imageName, 
                ]);
            
                return response()->json(['message' => 'Imagen actualizada con éxito'], 201);*/
            }else{
                return response()->json(['message' => 'No se proporcionó ningún archivo de imagen']);
            }
    
            } catch (NotFound $e) {
                return response()->json(['message' => 'Imagen no encontrada.'], 404);
    
            } catch (\Exception $e) {
                return response()->json(['message'=>'Error al procesar la solicitud.', 'error'=> $e], 500);
            }
    }


    public function show($imagePath)
    {
        try {
            /*$image= Image::findOrFail($id);
            $path= $image->imagePath;*/
    
            $rutaArchivo = "public/images/$imagePath"; 
    
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

    public function destroy($imagePath)
    {   
        try {
            /*$image = Image::findOrFail($id); 
            $path= $image->imagePath;*/
            if($imagePath) Storage::delete("public/images/$imagePath");
            
           // $image->delete();
            return response()->json(['message' => 'Imagen eliminada con éxito'], 201      
        );

        } catch (NotFound $e) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud', 'error'=> $e], 500);
            
        }
    }
}
