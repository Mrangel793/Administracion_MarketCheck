@extends('layouts.app')

@section('content')

<body>
<div class="container mt-5">
    <h1>Importe de Inventario</h1>
    <hr class="border color-background2 border-2 opacity-100">

        
            <form method="post" action="{{url('importe/importar')}}" enctype="multipart/form-data">
                @csrf
            <div class="input-group-text mt-3">
                    <input class="form-control" type="file" name="documento" />
            </div>
            <div>
            <button type="submit" class="btn btn-success mt-3">Subir Archivo</button>
            <a href="{{ route('home') }}" class="btn btn-secondary mt-3">Volver</a>
            </div>
            </form>
        
    </div>
@endsection