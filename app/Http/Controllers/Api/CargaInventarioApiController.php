<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth; 

use App\Imports\ProductsImport;

class CargaInventarioApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(['message' => 'API Index Endpoint']);
    }

    /**
     * Import data from Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importar(Request $request)
    {
        $user = Auth::user();
        if($user->rol_id== 2){
         if ($request->hasFile('documento')) {
             $path = $request->file('documento')->getRealPath();
             
             try {
                Excel::import(new ProductsImport, $path, 'xlsx');
                return response()->json(['message' => 'Import successful'], 200,[],JSON_NUMERIC_CHECK);
             } catch (\Exception $e) {
                 return response()->json(['error' => 'Import failed', 'message' => $e->getMessage()], 500);
             }
         }

         return response()->json(['error' => 'No file provided'], 400);
        }
        return response()->json(['error'=>'El usuario no tiene permisos para ejecutar esta accion'],403);
    }
}