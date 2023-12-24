<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;

use App\Models\User;
use App\Models\Oferta;
use App\Models\Establecimiento;

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
        return response()->json(['stores'=> $stores], 200);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "Nit"=> 'required|numeric', 
            "Estado"=> 'required', 
            "NombreEstablecimiento"=> 'required', 
            "DireccionEstablecimiento"=> 'required',
            "CorreoEstablecimiento"=> 'required|email|unique:establecimientos',
            "Imagen"=> 'required', 
            "Logo"=> 'required' 
        ]);

        try {
            $store= Establecimiento::create([
                "Nit" => $request-> Nit, 
                "Estado" => $request-> Estado, 
                "NombreEstablecimiento" => $request-> NombreEstablecimiento, 
                "DireccionEstablecimiento" => $request-> DireccionEstablecimiento,
                "CorreoEstablecimiento" => $request-> CorreoEstablecimiento,
                "Lema" => $request-> Lema, 
                "ColorInterfaz" => $request-> ColorInterfaz, 
                "Imagen" => $request-> Imagen, 
                "Logo" => $request-> Logo
            ]);
            
            $user = User::create([
                'name' => $request-> NombreEstablecimiento,
                'email' => $request-> CorreoEstablecimiento,            
                'establecimiento_id' => $store-> id,
                'rol_id' => 2,
                'password' => Hash::make($request-> Nit)
            ]);

            return response()->json(['message' => 'Establecimiento creado con éxito'], 201);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
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
            return response()->json(['store'=> $store], 200);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        }    
    }

    public function activate($id)
    {
        try {
            $store = Establecimiento::FindOrFail($id);

            $store->update([
                'estado'=> 1    
            ]);
            return response()->json(['message' => 'Tienda activada con éxito'], 201);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        }   
    }

    public function deactivate($id)
    {
        try {
            $store = Establecimiento::FindOrFail($id);
            
            $store->update([
                'estado'=> 0    
            ]);
            return response()->json(['message' => 'Tienda desactivada con éxito'], 201);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
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
            "CorreoEstablecimiento"=> 'required|email|unique:establecimientos',
            "Imagen"=> 'required' 
        ]);

        try {
            $store = Establecimiento::FindOrFail($id);
            $store->update($request->all());

            return response()->json(['message' => 'Producto activado con éxito', 'store'=> $store],201);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        try {
            $store = Establecimiento::findOrFail($id);
            $image= $store->Imagen;
            if($image) Storage::delete("public/images/$image");
            $logo= $store->Logo;
            if($logo) Storage::delete("public/images/$logo");


            /*$users= User::where('establecimiento_id', $store->id)->get();

            if($users){
                foreach($users as $user){
                    $user->delete();
                }
            }*/

            $store->delete();
    
            return response()->json(['message' => 'Establecimiento Eliminado!', 'store'=> $store], 200);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Establecimiento no encontrado'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud'], 500);
        }
    }

    //TODO: Revisar Utilidad??
    public function showOffer($establecimiento_id, $oferta_id)
    {
        $oferta = Oferta::where('establecimiento_id', $establecimiento_id)
                    ->where('id', $oferta_id)
                    ->first();

        if ($oferta) {
            return response()->json($oferta, 200);
        } else {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }
    }

}
