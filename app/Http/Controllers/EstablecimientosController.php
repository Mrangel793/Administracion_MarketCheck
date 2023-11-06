<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establecimiento;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Rol;



class EstablecimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $establecimientos=Establecimiento::all();
        return view('establecimientos.index',compact('establecimientos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rol=Rol::all();
        $establecimientos=Establecimiento::all();
        return view('establecimientos.create',compact('establecimientos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validar = $request->validate([
            'Nit' => 'required|unique:establecimientos',
            'NombreEstablecimiento' => 'required',
            'DireccionEstablecimiento' => 'required',
            'CorreoEstablecimiento' => 'required|email', // Asegúrate de validar que el correo sea válido
        ]);
        
    
        $establecimiento = Establecimiento::create($request->all());
        
        // Crear el usuario asociado al establecimiento
        $usuario = new User();
        $usuario->name = $request->input('NombreEstablecimiento');
        $usuario->email = $request->input('CorreoEstablecimiento');
        $usuario->password = Hash::make($request->input('Nit'));
        $usuario->rol_id = 2;

        $establecimiento->users()->save($usuario);
    
        return redirect()->route('establecimiento.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $establecimiento=Establecimiento::find($id);
        return view('establecimientos.edit',compact('establecimiento'));
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
    $validar = $request->validate([
        'Nit' => [
            'required',
            Rule::unique('establecimientos')->ignore($id, 'id'), 
        ],
        'NombreEstablecimiento' => 'required',
        'DireccionEstablecimiento' => 'required',
    ]);

    $establecimiento = Establecimiento::find($id);
    $establecimiento->update($request->all());

    return redirect()->route('establecimiento.index');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $establecimiento=Establecimiento::find($id);
        $establecimiento->delete();
        return redirect()->route('establecimiento.index');
    }

    
}
