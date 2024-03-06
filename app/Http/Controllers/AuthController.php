<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;


class AuthController extends Controller
{
 

    /**
     * Registro de usuario
     */
    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'documento' => 'required',
            'establecimiento_id' => 'required|exists:establecimientos,id', 
        ]);
    
        $name = $request->input('name');
        $email = $request->input('email');
        $documento=$request->input('documento');
        $establecimientoId = $request->input('establecimiento_id');
        
        
        $user=User::create([
            'name' => $name,
            'email' => $email,
            'documento'=>$documento,
            'password' => Hash::make($documento),
            'estado'=>1,
            'establecimiento_id' => $establecimientoId,
            'rol_id'=>2
        ]);

        $user->sendEmailVerificationNotification();

       /* $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');   
            $token = $tokenResult->token;

            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
    
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
                'user'=>$user 
            ]);*/


        
    }


public function addUserMovil(Request $request)
{
    

    $existingUser = User::where('email', $request->email)->where('estado', 0)->first();

    if ($existingUser) {
        $existingUser->update([
            'name' => $request->name,
            'documento' => $request->documento,
            'password' => Hash::make($request->password),
            'estado' =>1
        ]);

        $existingUser->sendEmailVerificationNotification();

        return response()->json(['message' => 'Usuario actualizado y código de verificación reenviado con éxito. Por favor, revise la confirmación en su correo.'], 200);
    }

    $user = User::create([
        'name' => $request->name,
        'documento' => $request->documento,
        'email' => $request->email,
        'establecimiento_id' => null,
        'rol_id' => 4,
        'estado' => 1,
        'password' => Hash::make($request->password),
        'profile_image' => 0,
    ]);

    $user->sendEmailVerificationNotification();

    return response()->json(['message' => 'Usuario creado con éxito. Por favor, revise la confirmación en su correo.'], 201);
}


    

  
    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $this->middleware(['auth','verified']);
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);


            $user = $request->user();

            if ($user->email_verified_at!=NULL&&$user->estado==1) {
                $tokenResult = $user->createToken('Personal Access Token');   
                $token = $tokenResult->token;

                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();
                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
                    'user'=>$user
                ],200,[],JSON_NUMERIC_CHECK);
            }else{
                return response()->json([
                    'message' => 'Su cuenta esta sin verificar o inactiva. Verifique su correo o intentelo nuevamente.'
                ], 401);
            }

    }
  
    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

}
