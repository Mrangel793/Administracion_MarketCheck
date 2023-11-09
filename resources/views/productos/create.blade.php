@extends('layouts.app')

@section('content')
<body>
<div class="container mt-5">
<h1>Nuevo Producto</h1>
    <hr class="border color-background2 border-2 opacity-100">

            <form action="{{ route('producto.store') }}" method="POST">
                @csrf
                <div class="input-group-text">
                    <span class="input-group-text">Codigo</span>
                    <input type="text" class="form-control" name="codigoProducto">
                </div>
                <select class="form-select mt-3" name="estado" >
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Nombre</span>
                    <input type="text" class="form-control" name="nombreProducto">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Descripción</span>
                    <input type="text" class="form-control" name="descripcionProducto">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Precio</span>
                    <input type="number" class="form-control" name="precioProducto">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Stock</span>
                    <input type="number" class="form-control" name="numeroStock">

                </div> 
                <div class="mb-3">
                    <label for="id_categoria" class="form-label">Categoria:</label>
                    <select class="form-control m-2" name="id_categoria">
                        @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre}}</option>
                        @endforeach
                    </select>

                 </div> 
                 <div class="mb-3">
                    <label for="id_subcategoria" class="form-label">SubCategoria:</label>
                    <select class="form-control m-2" name="id_subcategoria">
                        @foreach ($subcategorias as $subcategoria)
                        <option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre}}</option>
                        @endforeach
                    </select>
                    
                 </div>
                 <div><a href="{{ route('producto.index') }}" class="btn btn-secondary mt-3">Volver</a>
                
                <button type="submit" class="btn btn-success mt-3">Guardar Producto</button>
                </div><br><br><br>
        </div>
   
@endsection