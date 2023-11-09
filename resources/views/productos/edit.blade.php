@extends('layouts.app')

@section('content')
<body>
<div class="container mt-5">
    <h1>Editar Producto</h1>
    <hr class="border color-background2 border-2 opacity-100">
        
            <form action="{{route('producto.update',$producto->id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="input-group-text">
                    <span class="input-group-text">Codigo</span>

                    <input type="text" class="form-control" name="codigoProducto" value="{{$producto->codigoProducto}}">

                </div>
                <select class="form-select mt-3" name="estado" >
                    <option value="1" >Activo</option>
                    <option value="0" >Inactivo</option>
                </select>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Nombre</span>

                    <input type="text" class="form-control" name="nombreProducto" value="{{$producto->nombreProducto}}">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Descripción</span>
                    <input type="text" class="form-control" name="descripcionProducto" value="{{$producto->descripcionProducto}}">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Precio</span>
                    <input type="number" class="form-control" name="precioProducto" value="{{$producto->precioOriginal}}">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Stock</span>
                    <input type="number" class="form-control" name="numeroStock" value="{{$producto->numeroStock}}">
                </div>
                <div class="input-group-text mt-3">
    <span class="input-group-text">Categoria</span>
    <select class="form-control m-2" name="id_categoria">
        @foreach ($categorias as $categoria)
        <option value="{{ $categoria->id }}" @if($producto->id_categoria == $categoria->id) selected @endif>{{ $categoria->nombre }}</option>
        @endforeach
    </select>
</div>

<input type="hidden" name="subcategoria_id" value="{{ $producto->id_subcategoria }}">


<div class="mb-3">
    <label for="id_subcategoria" class="form-label">SubCategoria:</label>
    <select class="form-control m-2" name="id_subcategoria">
        @foreach ($subcategorias as $subcategoria)
            <option value="{{ $subcategoria->id }}" @if ($subcategoria->id == $producto->id_subcategoria) selected @endif>{{ $subcategoria->nombre }}</option>
        @endforeach
    </select>
</div>

                <div>
                <button type="submit" class="btn btn-success mt-3">Editar Producto</button>
                <a href="{{ route('producto.index') }}" class="btn btn-secondary mt-3">Volver</a>

                </div>
                <br><br>
        </div>
    
@endsection