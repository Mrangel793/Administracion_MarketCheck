<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;

use App\Models\Producto;
use App\Models\Categoria;


//REVISADO <--- REMOVER EN PRODUCCION
class ProductoApiController extends Controller
{   
    public function productsByStoreMobileApp($id){
        $products = Producto::where('id_establecimiento', $id)->where('estado', 1)->get();
        return response()->json( ['products'=> $products,'message'=>'Ok'], 200,[],JSON_NUMERIC_CHECK);            
    }

    public function productByStoreAndScanner(Request $request){
        $request->validate([
            'store_id' => 'required', 
            'product_code' => 'required',
        ]);
        $storeId= $request-> store_id;
        $productCode= $request-> product_code;

        $product = Producto::where('id_establecimiento', $storeId)->where('codigoProducto', $productCode)->where('estado', 1)->first();
        return response()->json( ['product'=> $product], 200,[],JSON_NUMERIC_CHECK);
    }

    public function index()
    {
        $user = Auth::user();

        if($user && isset($user-> establecimiento_id)&&($user->rol_id==2||$user->rol_id==3)){
            $products = Producto::where('id_establecimiento', $user-> establecimiento_id)->get();
            return response()->json( ['products'=> $products], 200,[],JSON_NUMERIC_CHECK);           
        }

        return response()->json( ['message'=> 'El usuario actual no puede obtener esta informacion'], 403);
    }

    
    public function getProductsfilter($searchTerm) {
        $user = Auth::user();
    
            $products = Producto::where('id_establecimiento', $user->establecimiento_id)
                               ->where(function ($query) use ($searchTerm) {
                                   $query->where('nombreProducto',$searchTerm )
                                         ->orWhere('codigoProducto', $searchTerm );
                               })
                               ->get();
    
            if ($products->isEmpty()) {
                return response()->json(['message' => 'No se encontraron productos con el término proporcionado.'], 404);
            }
    
            return response()->json(['products' => $products], 200,[],JSON_NUMERIC_CHECK);
        
    
        return response()->json(['message' => 'El usuario no tiene permisos para visualizar este contenido.'], 403);
    }
    

    
    public function store(Request $request)
{   

    $user = Auth::user();


    $request->validate([ 
        'codigoProducto' => 'required|numeric',
        'nombreProducto' => 'required',
        'descripcionProducto' => 'required',
        'precioProducto' => 'required|numeric|min:0',
        'numeroStock' => 'required|numeric|min:0',
        'estado' => 'required',
        'id_categoria' => 'required|numeric',
    ]);
    if (Auth::user()->rol_id == 2 || Auth::user()->rol_id ==3) {



        $existingProduct = Producto::where('codigoProducto', $request->codigoProducto)
                                    ->where('id_establecimiento', $user->establecimiento_id)
                                    ->first();

        if($existingProduct){
            return response()->json(['error' => 'Ya existe un producto con el mismo código de barras en este establecimiento.'], 422);
        }

        $product = Producto::create([
            'codigoProducto' => $request->codigoProducto,
            'nombreProducto' => $request->nombreProducto,
            'descripcionProducto' => $request->descripcionProducto,
            'precioProducto' => $request->precioProducto,
            'precioOriginal' => $request->precioProducto,
            'numeroStock' => $request->numeroStock,
            'estado' => $request->estado,
            'id_categoria' => $request->id_categoria,
            'id_establecimiento' => $user->establecimiento_id
        ]);

        return response()->json(['message' => 'Producto creado con éxito', 'product' => $product], 201,[],JSON_NUMERIC_CHECK);
    
    }else{return response()->json(['message'=>'No tienes permisos para realizar esta accion'],403);}

    

}

    public function show($id)
    {   
        $user = Auth::user();
        try {
            $product= Producto::FindOrFail($id);

            if($user && isset($user-> rol_id) && $user-> rol_id === 4){
                if($product-> estado === 0){
                    return response()->json(['message'=> 'El producto no se encuentra disponible.'], 404);
                } 
            }    
            return response()->json(['product'=> $product], 200,[],JSON_NUMERIC_CHECK);

        } catch (NotFound $e) {
            return response()->json(['message' => 'No se encontraron resultados'], 404);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }

    public function activate($id)
     {
        $user = Auth::user();
         
         try {

             $product= Producto::FindOrFail($id);
             if ((Auth::user()->rol_id == 2 || Auth::user()->rol_id ==3)&& $product->id_establecimiento == $user->establecimiento_id) {

             $product->update([
                 'estado'=> 1    
             ]);
             return response()->json(['message' => 'Producto activado con éxito'], 201);
            }else{return response()->json(['message'=>'No tienes permisos para realizar esta accion'],403);}

         } catch (NotFound $e) {
             return response()->json(['message' => 'Producto no encontrado'], 404);
 
         } catch (\Exception $e) {
             return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
         }  
    
        
        return response()->json(['message' => 'El usuario no tiene permisos para visualizar este contenido.'], 403);
 }

    public function deactivate($id)
    {
        $user = Auth::user();
         try {
             $product= Producto::FindOrFail($id);
             if ((Auth::user()->rol_id == 2 || Auth::user()->rol_id ==3)&& $product->id_establecimiento == $user->establecimiento_id) {

             $product->update([
                 'estado'=> 0    
             ]);
             return response()->json(['message' => 'Producto desactivado con éxito'], 201);
            }else{return response()->json(['message'=>'No tienes permisos para realizar esta accion'],403);}

         } catch (NotFound $e) {
             return response()->json(['message' => 'Producto no encontrado'], 404);

         } catch (\Exception $e) {
              return response()->json(['message'=>'Error al procesar la solicitud', 'error'=> $e], 500);
         } 
        
        return response()->json(['message' => 'El usuario no tiene permisos para visualizar este contenido.'], 403);
    }

    

    public function update(Request $request, $id)
{   
    $request->validate([ 
        'codigoProducto' => 'required|numeric',
        'nombreProducto' => 'required',
        'descripcionProducto' => 'required',
        'precioProducto' => 'required|numeric|min:0',
        'numeroStock' => 'required|numeric|min:0',
        'estado' => 'required',
        'id_categoria' => 'required|numeric',
    ]);

    $user = Auth::user();

    if ($user && isset($user->establecimiento_id)) {
        try {
            $product = Producto::findOrFail($id);

            $existingProduct = Producto::where('codigoProducto', $request->codigoProducto)
                                        ->where('id_establecimiento', $user->establecimiento_id)
                                        ->where('id', '!=', $id)
                                        ->first();

            if($existingProduct){
                return response()->json(['error' => 'Ya existe un producto con el mismo código de barras en este establecimiento.'], 422);
            }

            // Si no existe, proceder con la actualización del producto
            $product->update([
                'codigoProducto' => $request->codigoProducto,
                'nombreProducto' => $request->nombreProducto,
                'descripcionProducto' => $request->descripcionProducto,
                'precioProducto' => $request->precioProducto,
                'numeroStock' => $request->numeroStock,
                'estado' => $request->estado,
                'id_categoria' => $request->id_categoria,
                'id_establecimiento' => $user->establecimiento_id
            ]);

            return response()->json(['message' => 'Producto actualizado con éxito'], 201);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Producto no encontrado'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud', 'error' => $e], 500);
        }
    }

    return response()->json(['message' => 'El usuario no posee permisos para actualizar productos.'], 403);
}


    public function assignCategory(Request $request)
    {
        $categoria_id = $request->input('categoria_id');
        $productos_ids = $request->input('productos_ids');
        $user = auth()->user(); 

        try {
                if (!$user || !$user->establecimiento_id) {
                return response()->json(['error' => 'Usuario no tiene un establecimiento asignado'], 403);
            }

         $categoria = Categoria::find($categoria_id);

            if (!$categoria) {
            return response()->json(['error' => 'Categoría no encontrada'], 404);
            }

            Producto::whereIn('id', $productos_ids)
                ->where('id_establecimiento', $user->establecimiento_id)
                ->update(['id_categoria' => $categoria_id]);

            return response()->json(['message' => 'Categoría asignada correctamente a los productos'],201);
            } catch (\Exception $e) {
            return response()->json(['error' => 'Error al asignar la categoría a los productos', 'details' => $e->getMessage()], 500);
        }
    }

    public function productosSinCategoria()
    {
    try {
        $user = Auth::user();

        if (!$user || !$user->establecimiento_id) {
            return response()->json(['error' => 'Usuario no tiene un establecimiento asignado'], 403);
        }

        $productosSinCategoria = Producto::whereNull('id_categoria')
            ->where('id_establecimiento', $user->establecimiento_id)
            ->get();

        return response()->json(['productos' => $productosSinCategoria],200,[],JSON_NUMERIC_CHECK);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al obtener productos sin categoría', 'details' => $e->getMessage()], 500);
    }
    }

    public function productosConCategoria($id_establecimiento,$id_categoria)
    {
    try {

        $productosConCategoria = Producto::where('id_categoria',$id_categoria)
            ->where('id_establecimiento', $id_establecimiento)
            ->get();

        return response()->json(['productos' => $productosConCategoria],200,[],JSON_NUMERIC_CHECK);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al obtener productos sin categoría', 'details' => $e->getMessage()], 500);
    }
    }

    public function destroy($id)
    {
        $user = Auth::user();


        try {

            $product = Producto::findOrFail($id);
            
            
            if ($product->id_establecimiento != $user->establecimiento_id) {
                return response()->json(['error' => 'No tienes permisos para eliminar este producto.'], 403);
            }
            
            $product->delete();
    
            return response()->json(['message' => 'Producto Eliminado!', 'product' => $product], 201,[],JSON_NUMERIC_CHECK);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Producto no encontrado'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud', 'error'=> $e], 500);
        }
  
    }
}