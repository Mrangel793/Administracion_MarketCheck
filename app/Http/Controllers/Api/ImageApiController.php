<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;

use App\Models\Image;

class ImageApiController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Funcionando'], 200);
    }

    
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $path = $image->store('public/images');
            $imageName = basename($path);
            $newImage=Image::create([
                'imagePath' => $path,
                'establecimiento_id' => $request->input('store_id'),
            ]);
        
            return response()->json(['message' => 'Imagen almacenada con éxito', 'path' => $path], 201);
        }

        return response()->json(['message' => 'No se proporcionó ningún archivo de imagen'], 400);
    }

    

    public function show($path)
    {
        $rutaArchivo = "public/images/$path"; 

        if (Storage::exists($rutaArchivo)) {

            return response()->download(public_path(Storage::url('public/images/'.$path)), $path);
        }

        return response()->json(['mensaje' => 'Archivo no encontrado'], 404);
    }

    public function update(Request $request, $id)
    {
        $image = Image::find($id);
        if ($image) {
            // Actualizar la imagen o sus propiedades según sea necesario
            $image->image = $request->file('image')->get();
            $image->save();

            return response()->json(['message' => 'Imagen actualizada con éxito'], 200);
        } else {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }
    }

    public function destroy($id)
    {
        $image = Image::find($id);
        if ($image) {
            $image->delete();
            return response()->json(['message' => 'Imagen eliminada con éxito'], 200);
        } else {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }
    }
}
