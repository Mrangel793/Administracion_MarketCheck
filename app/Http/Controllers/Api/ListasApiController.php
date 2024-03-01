<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lista;
use App\Models\User;



class ListasApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $user = Auth::User();

        if ( $user->rol_id == 4) {
            $listas = Lista::where('user_id', $user->id)->get(); 
            return response()->json(['Listas del usuario' => $listas], 200, [], JSON_NUMERIC_CHECK);
        } else {
            return response()->json(['message' => 'No tiene permisos para hacer esto'], 503);
        }

        return response()->json(['message' => 'Error al procesar la solicitud'], 500);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->rol_id == 4) {
            $request->validate([
                'listName' => 'required',
                'productos' => 'required|json',
            ]);

            $nuevaLista = Lista::create([
                'listName' => $request->input('listName'),
                'user_id' => Auth::user()->id,
                'productos' => $request->input('productos'),
            ]);

            return response()->json(['message' => 'Lista creada correctamente', 'lista' => $nuevaLista], 201,[], JSON_NUMERIC_CHECK);
        } else {
            return response()->json(['message' => 'No tiene permisos para hacer esto'], 403);
        }
        return response()->json(['message' => 'Error al procesar la solicitud'], 500);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();    
        $lista = Lista::findOrFail($id);

        if($user->id == $lista->user_id){

            return response()->json(['lista' => $lista], 200,[], JSON_NUMERIC_CHECK);
        }else{
            return response()->json(['message' => 'No tiene permisos para hacer esto'], 403);
    }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $listaId)
    {
        $user = Auth::user();

        if ($user->rol_id == 4) {
            $request->validate([
                'listName' => 'required|string',
                'productos' => 'required|json',
            ]);

    
        $lista = Lista::findOrFail($listaId);

        if($user->id == $lista->user_id){

            $lista->update([
                'listName' => $request->input('listName'),
                'productos' => $request->input('productos'),
            ]);

            return response()->json(['message' => 'Lista actualizada correctamente', 'lista' => $lista], 200,[], JSON_NUMERIC_CHECK);
        }else{
            return response()->json(['message' => 'No tiene permisos para hacer esto'], 403);

        }
        } else {
        // Manejar el caso donde el usuario no está autenticado o no tiene los permisos adecuados
        return response()->json(['message' => 'No tiene permisos para hacer esto'], 403);
    }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($listaId)
    {
        $user = Auth::user();

        $lista = Lista::findOrFail($listaId);

        if($user->id == $lista->user_id){

            $lista->delete();

            return response()->json(['message' => 'Lista eliminada correctamente'], 201);
        }else{
            return response()->json(['message' => 'No tiene permisos para hacer esto'], 403);
    }

    }
}
