@extends('layouts.app')

@section('content')

<body>
<div class="container mt-5">
<h1>Agregar Productos a la Oferta</h1>
    <hr class="border color-background2 border-2 opacity-100">
       
            
            <p class="negrita">Detalles de la Oferta:</p>
      
            <p><strong>Nombre de la Oferta:</strong> {{ $oferta->nombre }}</p>
            <p><strong>Descripción de la Oferta:</strong> {{ $oferta->descripcion }}</p>
            <p><strong>Fecha de Inicio:</strong> {{ $oferta->fecha_inicio }}</p>
            <p><strong>Fecha de Fin:</strong> {{ $oferta->fecha_fin }}</p>
         



            <form method="post" action="{{ route('oferta.guardar_productos', ['ofertaId' => $oferta->id]) }}">
                @csrf
                <input type="hidden" name="id_oferta" value="{{ $oferta->id }}" />
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Seleccionar Producto</span>
                    <select class="form-select" name="producto_id">
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombreProducto ."—". $producto->precioOriginal }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Porcentaje de Descuento</span>
                    <input class="form-control" type="number" name="porcentaje" />
                </div>

                

                <button type="submit" class="btn btn-success mt-3">Agregar Producto</button>
            </form>
           <form method="post" action="{{ route('oferta.finalizar_agregar_productos', ['ofertaId' => $oferta->id]) }}">
                @csrf
                <button type="submit" class="btn btn-primary mt-3">Finalizar</button>
            </form>

            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
@endsection