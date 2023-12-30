<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;

use App\Models\Categoria;


//REVISADO
//TODO: PENDIENTE STORE, UPDATE & DESTROY
class CategoriaApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Categoria::all();
        return response()->json(['categories'=> $categories], 200);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO: EL SUPER - ADMIN DEBE PODER CREAR
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
            $categorie = Categoria::findOrFail($id);
            return response()->json(['categorie'=>$categorie], 200);

        } catch (NotFound $e) {
            return response()->json(['message'=> 'No encontraron resultados'], 404);
            
        }catch(\Exception $e){
            return response()->json(['message'=> 'Error al procesar la solicitud', 'error'=> $e], 500);
            
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
        //TODO: EL SUPER - ADMIN DEBE PODER ACTUALIZAR SIEMPRE Y CUANDO NO TENGA REGISTROS ASOCIADOS
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //TODO: CREAR <-- SE EVALUA QUE NO SE PUEDA ELIMINAR SI TIENE SUB-CATEGORIAS RELACIONADAS
    }
}
