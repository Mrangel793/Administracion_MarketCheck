<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Establecimiento;
use App\Models\Rol;
use App\Models\User;
use App\Models\Oferta;

class EstablecimientoApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $establecimientos = Establecimiento::all();
        return response()->json($establecimientos,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
 
        $establecimiento = Establecimiento::create($request->all());

        $usuario = new User();
        $usuario->name = $request->NombreEstablecimiento;
        $usuario->email = $request->CorreoEstablecimiento;
        $usuario->password = Hash::make($request->Nit);
        $usuario->rol_id = 2;
    
        $establecimiento->users()->save($usuario);
    
        return response()->json(['message' => 'Establecimiento creado con éxito'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $establecimiento = Establecimiento::find($id);
        return response()->json($establecimiento,200);
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
        $establecimiento = Establecimiento::find($id);
        if($establecimiento){
            $establecimiento->update($request->all());
            return response()->json($establecimiento,200);
        }else{
            return response()->json(['Error'=>'Id no encontrado'],404);
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
        $establecimiento = Establecimiento::find($id);
        if($establecimiento){
            $establecimiento->delete();
            return response()->json($establecimiento,200);
        }else{
            return response()->json(['Error'=>'Id no encontrado'],404);        }
    }

    public function showOffer($establecimiento_id, $oferta_id)
{
    // Buscar una oferta específica ($oferta_id) en el contexto de un establecimiento específico ($establecimiento_id).
    // Realiza la lógica necesaria para buscar y devolver la oferta.

    // Ejemplo:
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
