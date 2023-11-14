@extends('layouts.app')

@section('content')
<body>

    

    <div class="container mt-5">
    <h1>Gestión de Productos</h1>
    <hr class="border color-background2 border-2 opacity-100">
    <div><a href="{{ route('home') }}" class="btn btn-secondary ms-2 mb-3">Volver</a>
    <a href="{{route('producto.create')}}" class="btn btn-primary mb-3">Nuevo Producto</a>
    </div>
        <div class="card">
            <h1>Productos</h1>
        


            <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Codigo</th>
                <th>Estado</th>
                <th>Precio</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($productos as $producto)
                <td>{{$producto->id}}</td>
                <td>{{$producto->codigoProducto}}</td>
                <td>{{$producto->estado}}</td>
                <td> {{$producto->precioProducto}}</td>
                <td>{{$producto->nombreProducto}}</td>
                <td>{{$producto->descripcionProducto}}</td>
                <td>{{$producto->numeroStock}}</td>
                <th> 
                    <div class="d-flex">
                        <a href="{{ route('producto.edit', $producto->id) }}" class="btn btn-warning">Editar</a>
                         <form method="post" action="{{ route('producto.destroy', $producto->id) }}">
                            @method('delete')
                             @csrf
                             <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </th>
            </tr>
        </tbody>
        @endforeach
        </div>
    </div>
@endsection