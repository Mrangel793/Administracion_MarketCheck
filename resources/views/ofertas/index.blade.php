@extends('layouts.app')

@section('content')

<body>
<div class=" tamaño mt-5">
    <h1>Gestión de Ofertas</h1>
    <hr class="border color-background2 border-2 opacity-100">
    <div><a href="{{ route('home') }}" class="btn btn-secondary ms-2 mb-3">Volver</a>
<a href="{{route('oferta.create')}}" class="btn btn-primary ms-2 mb-3">Nueva Oferta</a>
    </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Estado</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Número de Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($ofertas as $oferta)
                        <tr>
                            <td>{{ $oferta->id }}</td>
                            <td>@if ($oferta->estado == 1)
                                    Activo
                                @else
                                    Inactivo
                                @endif</td>
                            <td>{{ $oferta->fecha_inicio }}</td>
                            <td>{{ $oferta->fecha_fin }}</td>
                            <td>{{ $oferta->nombre }}</td>
                            <td>{{ $oferta->descripcion }}</td>
                            <td><img src="{{ asset($oferta->imagen) }}" alt="Imagen actual" width="150px"></td>
                            <td>{{ $oferta->numero_stock }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('oferta.edit', $oferta->id) }}" class="btn btn-warning">Editar</a>
                                    <form method="post" action="{{ route('oferta.destroy', $oferta->id) }}">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                    
                                    @if ($oferta->estado == 1)
                                        {{-- Si la oferta está activada, muestra el botón para desactivar --}}
                                            <form action="{{ route('oferta.desactivar', ['ofertaId' => $oferta->id]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger">Desactivar Oferta</button>
                                            </form>
                                    @else
                                        {{-- Si la oferta está desactivada, muestra el botón para activar --}}
                                            <form action="{{ route('oferta.activar', ['ofertaId' => $oferta->id]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-primary">Activar Oferta</button>
                                    </form>
                                    @endif
</form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                
            </tbody>
        </table>
    </div>
@endsection