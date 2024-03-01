<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;

use App\Models\User;
use App\Models\Image;
use App\Models\Oferta;
use App\Models\Compra;
use App\Models\Establecimiento;
use App\Models\Categoria;

//REVISADO <--- REMOVER EN PRODUCCION
class EstablecimientoApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $stores = Establecimiento::all();
        return response()->json(['stores'=> $stores], 200,[],JSON_NUMERIC_CHECK);
        
        return response()->json(['message' => 'No tienes permisos para ejecutar esta acción'], 403);

    }

    
    public function store(Request $request)
    {
        $request->validate([
            "Nit"=> 'required|numeric', 
            "Estado"=> 'required', 
            "NombreEstablecimiento"=> 'required', 
            "DireccionEstablecimiento"=> 'required',
            "CorreoEstablecimiento"=> 'required|email|unique:establecimientos', 
        ]);


        try {
            if (Auth::user()->rol_id == 1) {

            $store= Establecimiento::create([
                "Nit" => $request-> Nit, 
                "Estado" => $request-> Estado, 
                "NombreEstablecimiento" => $request-> NombreEstablecimiento, 
                "DireccionEstablecimiento" => $request-> DireccionEstablecimiento,
                "CorreoEstablecimiento" => $request-> CorreoEstablecimiento,
                "Lema" => $request-> Lema, 
                "ColorInterfaz" => $request-> ColorInterfaz, 
                //"Imagen" => $request-> Imagen, 
                //"Logo" => $request-> Logo
            ]);
            
            $user = User::create([
                'name' => $request-> NombreEstablecimiento,
                'email' => $request-> CorreoEstablecimiento,            
                'establecimiento_id' => $store-> id,
                'rol_id' => 2,
                'password' => Hash::make($request-> Nit)
            ]);
            event(new Registered($user));


            return response()->json(['message' => 'Establecimiento creado con éxito', 'id'=> $store-> id], 201,[],JSON_NUMERIC_CHECK);

            
        }else{return response()->json(['message' => 'No tienes permisos para ejecutar esta acción'], 403);}

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $store = Establecimiento::FindOrFail($id);
            return response()->json(['store'=> $store], 200,[],JSON_NUMERIC_CHECK);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }    
    }

    public function showCategoriesByStore($id){
        try {
            $categories = Categoria::select('categorias.*')
            ->join('productos', 'categorias.id', '=', 'productos.id_categoria')
            ->join('establecimientos', 'establecimientos.id', '=', 'productos.id_establecimiento')
            ->where('establecimientos.id', $id)
            ->distinct() 
            ->get();
        
            return response()->json(['categories' => $categories], 200,[],JSON_NUMERIC_CHECK);
        
            
        
        } catch (NotFound $e) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }    
    }         

    public function activateOrDestivateStore($id)
    {

        
        try {
            if (Auth::user()->rol_id == 1) {

            $store = Establecimiento::FindOrFail($id);
            $state= $store-> Estado;
            switch ($state) {
                case 0:
                    $store->update([
                        'Estado'=> 1    
                    ]);
                    return response()->json(['message' => 'Tienda activada con éxito'], 201,[],JSON_NUMERIC_CHECK);
                    
                case 1:
                    $store->update([
                        'Estado'=> 0    
                    ]);
                    return response()->json(['message' => 'Tienda desactivada con éxito'], 201,[],JSON_NUMERIC_CHECK);
                    break;
                
                default:
                    
                    break;
            }
            }else{return response()->json(['message'=>'No tienes permisos para realizar esta accion'],403);}

        } catch (NotFound $e) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }   
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

        $request->validate([
            "Nit"=> 'required|numeric', 
            "Estado"=> 'required', 
            "NombreEstablecimiento"=> 'required', 
            "DireccionEstablecimiento"=> 'required',
            "CorreoEstablecimiento"=> 'required',
        ]);

        try {
            if (Auth::user()->rol_id == 1) {

                $store = Establecimiento::FindOrFail($id);
                $store->update($request->all());

                return response()->json(['message' => 'Actualizacion éxitosa', 'store'=> $store], 201,[],JSON_NUMERIC_CHECK);
            }else{return response()->json(['message'=>'No tienes permisos para realizar esta accion'],403);}

        } catch (NotFound $e) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }

    public function updateImageField(Request $request, $id)
    {  
        try {
            $store = Establecimiento::FindOrFail($id);
            $logo= $request->input('Logo');
            $image= $request->input('Imagen');
            if($logo){
                $store->update([
                    'Logo' => $logo
                ]);
            }
            if($image){
                $store->update([
                    'Imagen' => $image
                ]);
            }
            return response()->json(['message' => 'Actualizacion éxitosa.', 'L'=>$logo , 'I'=>$image],201,[],JSON_NUMERIC_CHECK);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Tienda no encontrada.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud.', 'error'=> $e], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){      
        $user = Auth::user();

        try {

            if ($user->rol_id == 1) {

                    $store = Establecimiento::findOrFail($id);

                    $imagePath= $store-> Imagen;
                    $logoPath= $store-> Logo;

                if($imagePath) Storage::delete("public/images/$imagePath");
                if($logoPath) Storage::delete("public/images/$logoPath");
                
            

                    $offers = Oferta::where('establecimiento_id', $store-> id)->get();
                    if($offers->isNotEmpty()){
                        foreach ($offers as $offer) {             
                            $path= $offer->imagen;
                            if($path) Storage::delete("public/images/$path");    
                    
                            $offer->productos()->detach();
                            $offer->delete();
                        }
                    }

                    $purchases = Compra::where('establecimiento_id', $store-> id)->get();
                    if($purchases->isNotEmpty()){
                        foreach ($purchases as $purchase) {
                            $purchase->productos()->detach();
                            $purchase->delete();
                        }
                    }

                    $store->delete();
                    return response()->json(['message' => 'Establecimiento Eliminado!', 'store'=> $store], 201,[],JSON_NUMERIC_CHECK);
            
            }else{return response()->json(['message' => 'No tienes permisos para ejecutar esta acción'], 403);}

          
        } catch (NotFound $e) {
            return response()->json(['message' => 'Establecimiento no encontrado'], 404);

        } catch (\Exception $e) {
            
            return response()->json(['message' => 'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }

}
