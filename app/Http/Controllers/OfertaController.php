<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Oferta;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Establecimiento;
use App\Models\Producto;
use App\Models\Oferta_Producto;



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
        
        if($request->File('file')){
            $imagenes = $request->file('file')->store('public/imagenes');
            $url =Storage::url($imagenes);
            $ofertas-> imagen = $url;
            }else{  
                echo "No se cargo imagen";
                }
        $ofertas->save();
        $nuevaOferta = $ofertas; // Asigna la oferta recién creada a $nuevaOferta
        return view('ofertas.create', ['nuevaOferta' => $nuevaOferta]);
        
       
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

    public function activarOferta($ofertaId)
    {
        $oferta = Oferta::find($ofertaId);
    
        if (!$oferta) {
            return redirect()->route('oferta.index')->with('error', 'La oferta no existe.');
        }
    
        $productosOferta = $oferta->productos; 
    
        foreach ($productosOferta as $producto) {
            $precioOferta = $producto->pivot->precio_oferta; 
      
            if ($producto->precioProducto !== null) {
                $producto->precioProducto = $precioOferta;
                $producto->update();
            }
        }
    
        $oferta->estado = 1; // Activa la oferta
        $oferta->save();
    
        return redirect()->route('oferta.index')->with('success', 'Oferta activada con éxito.');
    }


    public function desactivarOferta($ofertaId)
{
    $oferta = Oferta::find($ofertaId);

   
       $productosOferta = $oferta->productos;

       foreach ($productosOferta as $producto) {
           $producto->precioProducto = $producto->precioOriginal;
           $producto->save();
       }
   
      
       $oferta->estado = 0;
       $oferta->save();
   
       return redirect()->route('oferta.index')->with('success', 'Oferta desactivada con éxito.');
   
}


    public function agregarProductos($ofertaId)
    {
        $oferta = Oferta::find($ofertaId);
        $productos = Producto::all(); 
    
        return view('ofertas.agregar_productos_oferta', [
            'oferta' => $oferta,
            'productos' => $productos,
        ]);
    }
    
public function guardarProductos(Request $request, $ofertaId)
{
    $oferta = Oferta::find($ofertaId);
    
    $porcentaje = $request->input('porcentaje');
    $producto_id = $request->input('producto_id');
    $producto = Producto::find($producto_id);
    
    // Calcula el precio de oferta
    $precioDescuento = $producto->precioOriginal - ($producto->precioOriginal * $porcentaje / 100);
    
    // Guarda los datos en la tabla oferta_productos
    $ofertaProducto = new Oferta_Producto();
    $ofertaProducto->id_producto = $producto_id;
    $ofertaProducto->id_oferta = $oferta->id;
    $ofertaProducto->porcentaje = $porcentaje;
    $ofertaProducto->precio_oferta = $precioDescuento;
    $ofertaProducto->save();
    
    // Actualiza el precio del producto en la tabla productos
    $producto->precioProducto = $precioDescuento;
    $producto->save();
    
    return redirect()->route('oferta.agregar_productos', ['ofertaId' => $oferta->id])
        ->with('success', " $producto->nombreProducto agregado con éxito a la oferta");
}

public function finalizarAgregarProductos($ofertaId)
{
    
    
    return redirect()->route('oferta.index');
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

   
       $productosOferta = $oferta->productos;

       foreach ($productosOferta as $producto) {
           $producto->precioProducto = $producto->precioOriginal;
           $producto->save();
       }

    if (!$oferta) {
        return redirect()->route('oferta.index')->with('error', 'La oferta no existe.');
    }

    $oferta->productos()->detach();
    $oferta->delete();

    return redirect()->route('oferta.index')->with('success', 'Oferta eliminada con éxito.');
}
}
