<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;

use App\Models\User;
use App\Models\Establecimiento;

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

            return response()->json(['users'=> $users], 200);
        }
        elseif ($user){
            $users = User::where('rol_id', '!=', 1)->get();
            return response()->json(['users'=> $users], 200);
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
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'documento' => $request->documento,
            'establecimiento_id' => $request->establecimiento_id,
            'rol_id' => $request->rol_id,
            'password' => Hash::make($request->name),
            'email_verified_at' => now(), // Marcar como verificado inmediatamente
        ]);
    
        // Asegurémonos de que el usuario tenga un establecimiento antes de enviar la notificación
        if ($user->establecimiento_id) {
            $user->sendEmailVerificationNotification();
        }
    
        return response()->json(['message' => 'Usuario creado con éxito. Recuerde que la contraseña es el mismo nombre'], 201);
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
            return response()->json(['user'=> $user], 200);

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
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'rol_id' => 'required|numeric',
        ]);
    
        try {
            $user = User::findOrFail($id);
    
            $user->update([
                'name' => $request-> name,
                'email' => $request-> email,
                'documento' => $request-> documento,
                'establecimiento_id' => $request-> establecimiento_id,
                'rol_id' => $request-> rol_id,
            ]);
    
            return response()->json(['message' => 'Datos actualizados con éxito'], 201);

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
            $user->update([
                'password' => Hash::make($request-> password)
            ]);
    
            return response()->json(['message' => 'Contraseña actualizada con éxito'], 200);

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
            $user->delete();
    
            return response()->json(['message' => 'Usuario Eliminado!', 'user'=> $user], 200);

        } catch (NotFound $e) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud', 'error'=> $e], 500);
        }
    }
}
