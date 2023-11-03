<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Oferta;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Establecimiento;


class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function index()
     {
         $usuario = Auth::user();
         $ofertas = Oferta::where('establecimiento_id', $usuario->establecimiento_id)->get();
     
         return view('ofertas.index', compact('ofertas'));
     }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('ofertas.create', compact('categorias'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    
    {
        $usuario = Auth::user();
        $request->validate([
            'file'=> 'required|image|max:2048'
        ]);
        //dd($request->all());
        $categorias = Categoria::all();
        $ofertas = new Oferta();
        $ofertas ->fecha_inicio = $request->fecha_inicio;
        $ofertas ->fecha_fin = $request->fecha_fin;
        $ofertas ->nombre = $request->nombre;
        $ofertas ->descripcion = $request->descripcion;
        $ofertas ->numero_stock = $request->numero_stock;
        $ofertas ->estado = $request->estado;
        $ofertas ->establecimiento_id = $usuario->establecimiento_id;
        

        $ofertas->id_categoria = $request->categorias;
        if($request->File('file')){
            $imagenes = $request->file('file')->store('public/imagenes');
            $url =Storage::url($imagenes);
            $ofertas-> imagen = $url;
            }else{  
                echo "No se cargo imagen";
                }
        $ofertas->save();
        
        //$imagenes = $request->file('file')->store('public/imagenes');
        return redirect()->route('oferta.index');
        //return $ofertas-> imagen;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categorias = Categoria::all();
        $oferta = Oferta::find($id);
        return view('ofertas.edit',compact('oferta','categorias'));
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
        $oferta= Oferta::find($id);
        $oferta ->fecha_inicio =$request->fecha_inicio;
        $oferta ->fecha_fin = $request->fecha_fin;
        $oferta ->nombre = $request->nombre;
        $oferta ->descripcion = $request->descripcion;
        $oferta ->numero_stock =$request->numero_stock;
        $oferta ->estado =$request->estado;
        $oferta->id_categoria = $request->categorias;
        if ($request->File('file')) {
            $imagenes = $request->file('file')->store('public/imagenes');
            $url =Storage::url($imagenes);
            $oferta-> imagen = $url;
            Storage::delete($url);
            $url=$request->file('file')->store('public/imagenes');
            $array=explode('/',$url);
            }
        $oferta->update();
        return redirect()->route('oferta.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oferta = Oferta::find($id);
        $oferta->delete();
        return redirect()->route('oferta.index');
    }
}
