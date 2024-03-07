<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;

use App\Models\User;
use App\Models\Establecimiento;
use App\Models\ComprasProductos;


//REVISADO <--- REMOVER EN PRODUCCION
class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {    
        $user= Auth::user();

        if($user && isset($user-> establecimiento_id)){
            $users = User::where('establecimiento_id', $user-> establecimiento_id)
            ->whereNotIn('id', [$user-> id])
            ->get();

            return response()->json(['users'=> $users], 200,[],JSON_NUMERIC_CHECK);
        }
        elseif ($user){
            $users = User::where('rol_id', '!=', 1)
            ->where('estado',1)
            ->get();
            return response()->json(['users'=> $users], 200,[],JSON_NUMERIC_CHECK);
        }

        return response()->json(['message'=> 'Por favor inicie sesion'], 401);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'rol_id' => 'required|numeric',
            'documento'=>'required|numeric',
            'establecimiento_id'=>'required|numeric'
        ]);
        if ((Auth::user()->rol_id == 1 || Auth::user()->rol_id ==2)) {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'documento' => $request->documento,
                'establecimiento_id' => $request->establecimiento_id,
                'estado'=>1,
                'rol_id' => $request->rol_id,
                'password' => Hash::make($request->documento),
            ]);
            $user->sendEmailVerificationNotification();    
            return response()->json(['message' => 'Usuario creado con éxito. Recuerde que la contraseña es el numero de documento'], 201);
        }else{return response()->json(['message'=>'No tienes permisos para realizar esta accion'],403);}

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
            $user= User::FindOrFail($id);
            return response()->json(['user'=> $user], 200,[],JSON_NUMERIC_CHECK);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);

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

     public function UserStores($id)
    {
        $user=Auth::user();

        if($user->id == $id && $user->rol_id==4) {
            $establecimientos = Establecimiento::join('compras', 'establecimientos.id', '=', 'compras.establecimiento_id')
                ->where('compras.user_id', $id)
                ->distinct()
                ->select('establecimientos.*') 
                ->get();

                return response()->json(['establecimientos' => $establecimientos],200,[],JSON_NUMERIC_CHECK);
        }else{
            return response()->json(['message'=>'No tienes permisos para realizar esta accion'],403);

        }
        

    }

    public function UserMostPurchasedProducts($id)
    {
        $user=Auth::user();

        if($user->id == $id && $user->rol_id==4) {

            $productosMasComprados = DB::table('compras_productos')
            ->join('compras', 'compras_productos.compra_id', '=', 'compras.id')
            ->join('productos', 'compras_productos.producto_id', '=', 'productos.id')
            ->join('establecimientos', 'productos.id_establecimiento', '=', 'establecimientos.id')
            ->where('compras.user_id', $id)
            ->select(
                'productos.id as producto_id',
                'productos.nombreProducto',
                'productos.id_establecimiento',
                DB::raw('SUM(compras_productos.cantidad) as total_comprado'),
                'establecimientos.NombreEstablecimiento as nombre_establecimiento'
            )
            ->groupBy(
                'productos.id',
                'productos.nombreProducto',
                'productos.id_establecimiento',
                'establecimientos.NombreEstablecimiento'
            )
            ->orderByDesc('total_comprado')
            ->limit(10)
            ->get();

            return response()->json(['productos_mas_comprados' => $productosMasComprados], 200,[],JSON_NUMERIC_CHECK);

        }else{
            return response()->json(['message'=>'No tienes permisos para realizar esta accion'],403);

        }
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'rol_id' => 'required|numeric',
        ]);
    
        try {
            $user = User::findOrFail($id);
            
            if (Auth::user()->rol_id != 3) {

                $user->update([
                    'name' => $request-> name,
                    'email' => $request-> email,
                    'documento' => $request-> documento,
                    'establecimiento_id' => $request-> establecimiento_id,
                    'rol_id' => $request-> rol_id,
                    'profile_image' => $request->profile_image
                ]);
    
                return response()->json(['message' => 'Datos actualizados con éxito', 'user'=>$user, 'image'=>$request->profile_image], 201,[],JSON_NUMERIC_CHECK);

            }else{
                return response()->json(['message'=>'No tienes permisos para realizar esta accion'],403);
            }

        } catch (NotFound $e) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar los datos', 'error'=> $e], 500);
        } 
    }

    public function changePassword(Request $request, $id)
    {

        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = User::findOrFail($id);
            if (Auth::user()->id == $user->id) {

                $user->update([
                    'password' => Hash::make($request-> password)
                ]);
    
                return response()->json(['message' => 'Contraseña actualizada con éxito'], 201);
            }else{return response()->json(['message'=>'No tienes permisos para realizar esta accion'],403);}

        } catch (NotFound $e) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud', 'error'=> $e], 500);
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

            $user = User::findOrFail($id);
            if ((($user->establecimiento_id==Auth::user()->establecimiento_id)&&Auth::user()->rol_id==2)||(Auth::user()->rol_id == 1)||(Auth::user()->id==$id && Auth::user()->rol_id==4)){
                if($user->rol_id==4){
                    
                    $user->update([
                        'estado' => 0,
                        'email_verified_at' => null,
                    ]);
                    
                }

                if($user->rol_id==2||$user->rol_id==3){
                    $user->delete();
                }
            
            }else{
                return response()->json(['message'=>'No tiene autorización para eliminar este usuario'],403);
            }
    
            return response()->json(['message' => 'Usuario Eliminado!', 'user'=> $user], 201,[],JSON_NUMERIC_CHECK);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }
}
