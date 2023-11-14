@extends('layouts.app')

@section('content')
    <div class="container">

    @if (Auth::check())
    <p class="welcome-message mt-4">Hola {{ Auth::user()->name }}, Bienvenido</p>
    @endif

    <div class="row justify-content-center mt-5">
    <div class="col-md-5">
    <div class="card">
        <div class="card-header bg-primary text-white negrita text-center ">{{ __('Gestión de Productos') }}</div>
        <div class="card-body">
            <p class="card-text">Añade, edita, y elimina de entre toda la variedad de productos que tiene tu establecimiento</p>
            <a href="{{ route('producto.index') }}" class="btn btn-primary">Productos</a>
        </div>
    </div>
</div>
    
        <div class="col-md-5">
    <div class="card">
        <div class="card-header bg-primary text-white negrita text-center ">{{ __('Gestión de Ofertas') }}</div>
        <div class="card-body">
            <p class="card-text">Crea, activa, desactiva y elimina ofertas de distintos productos de tu establecimiento.</p>
            <a href="{{ route('oferta.index') }}" class="btn btn-primary">Ofertas</a>
        </div>
    </div>
</div>
    </div>

    <div class="row justify-content-center mt-5">
    <div class="col-md-5">
    <div class="card">
        <div class="card-header bg-primary text-white negrita text-center ">{{ __('Importe de Inventario') }}</div>
        <div class="card-body">
            <p class="card-text">¿Ya tienes un inventario? No pierdas el tiempo, importalo y comienza a manejar todo con Market Check</p>
            <a href="{{ route('importe') }}" class="btn btn-primary">Inventario</a>
        </div>
    </div>
</div>

    
<div class="col-md-5">
    <div class="card">
        <div class="card-header bg-primary text-white negrita text-center ">{{ __('Gestión de Usuarios') }}</div>
        <div class="card-body">
            <p class="card-text">Creale un usuario a tus trabajadores, para que ellos tambien puedan usar esta herramienta</p>
            <a href="{{ route('user.index') }}" class="btn btn-primary"">Usuarios</a>

        </div>
    </div>
</div>
    </div>


@endsection
