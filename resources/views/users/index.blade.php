@extends('layouts.app')

@section('content')
<body>
<div class="container mt-5">
    <h1>Gestion de Usuarios</h1>
    <hr class="border color-background2 border-2 opacity-100">
    <div>
        <a href="{{route('user.create')}}" class="btn btn-primary">Nuevo Usuario</a>
        <a href="{{route('home')}}" class="btn btn-secondary">Volver</a>
    </div>
        
        <br>
        
        
        <br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Acciones</th>                    
                </tr>
            </thead>
        @if (count($users)>0)
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>
                <form action="{{route('user.destroy', ['user'=>$user->id])}}" method="POST">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger" >Eliminar</button>
                </form>
                </td>
            </tr>
            @endforeach
            </tbody>
            @endif
            
        </table>
        
    </div>

@endsection