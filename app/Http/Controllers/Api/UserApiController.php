<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Establecimiento;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users,200);
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
            'rol_id' => 'required|numeric', // Validación del rol como un número.
        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $establecimientoId = $request->input('establecimiento_id');
        $rolId = $request->input('rol_id'); 

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($name);
        $user->establecimiento_id = $establecimientoId;
        $user->rol_id = $rolId; 

        $user->save();

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
        $users = User::find($id);
        return response()->json($users,200);
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
        'password' => 'required|string|min:8',
    ]);

    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    $user->password = Hash::make($request->input('password'));
    $user->save();

    return response()->json(['message' => 'Contraseña actualizada con éxito'], 200);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $users = User::find($id);
        if($users){
            $users->delete();
            return response()->json($users,200);
        }else{
            return response()->json(['Error'=>'Id no encontrado'],404);
        }
    }
}
