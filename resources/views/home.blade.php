@extends('layouts.app')

@section('content')
    <div class="container">

    @if (Auth::check())
    <p class="welcome-message mt-4">Hola {{ Auth::user()->name }}, Bienvenido</p>
    @endif

    <p>TU CORREO SE HA VERIFICADO CORRECTAMENTE. YA PUEDES USAR MARKETCHECK</p>


@endsection
