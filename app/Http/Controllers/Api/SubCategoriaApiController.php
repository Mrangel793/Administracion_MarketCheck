<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;

use App\Models\SubCategoria;


//REVISADO
//TODO: PENDIENTE STORE, UPDATE & DESTROY
class SubCategoriaApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategorias = SubCategoria::all();
        return response()->json(['subcategories'=> $subcategorias], 200);
    } 

    public function indexporCategoria($categoria_id)
    {
        try {
            $subcategorias = SubCategoria::where('categoria_id', $categoria_id)->get();
            return response()->json(['sub_categories'=>$subcategorias], 200);

        } catch (NotFound $e) {
            return response()->json(['message'=> 'No encontraron resultados por esta Categoria'], 404);
            
        }catch(\Exception $e){
            return response()->json(['message'=> 'Error al procesar la solicitud', 'error'=> $e], 500);
            
        }
    }

    /**
     * Store a newly created resource in storage.
     *
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
            $subcategoria = SubCategoria::with('categoria')->findOrFail($id);
            return response()->json(['sub_categorie'=>$subcategoria], 200);

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
        //TODO: CREAR <-- SE EVALUA QUE NO SE PUEDA ELIMINAR SI TIENE PRODUCTOS RELACIONADAS
    }
}
