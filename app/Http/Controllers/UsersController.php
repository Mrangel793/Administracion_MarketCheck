<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Establecimiento;
use App\Models\Rol;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;




class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    $usuario = Auth::user();
    $users = User::where('establecimiento_id', $usuario->establecimiento_id)->get();



    return view('users.index', compact('users'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $establecimientos = Establecimiento::all();
        $users = User::all();
        return view('users.create', compact('establecimientos', 'users'));
        $request->session()->flash('user_email', $email);
        $request->session()->flash('user_nit', $Nit);

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
        'establecimiento_id' => 'required|exists:establecimientos,id', 
    ]);

    $name = $request->input('name');
    $email = $request->input('email');
    $establecimientoId = $request->input('establecimiento_id');
    
    User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($name),
        'establecimiento_id' => $establecimientoId,
        'rol_id'=>2
    ]);

    return redirect()->route('user.index');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::find($id);
        $user->delete();
        return redirect()->route('user.index');
    }
}
