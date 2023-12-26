@extends('layouts.app')

@section('content')

<body>
<div class="container mt-5">
    <h1>Creación de Ofertas</h1>
    <hr class="border color-background2 border-2 opacity-100">
        
            <form method="post" action="{{route('oferta.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="input-group-text">
                    <span class="input-group-text">Fecha de Inicio</span>
                    <input type="date" name="fecha_inicio" class="form-control" placeholder="Fecha de Inicio">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Fecha de fin</span>
                    <input type="date" name="fecha_fin" class="form-control" placeholder="Fecha de Inicio">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Nombre Oferta</span>
                    <input class="form-control" type="text" name="nombre" /><br>
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Descripción Oferta</span>
                    <input class="form-control" type="text" name="descripcion" /><br>
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Cantidad Stock</span>
                    <input class="form-control" type="number" name="numero_stock" /><br>
                </div>
                <div class="input-group-text mt-3">
                    <input class="form-control" type="file" name="file" accept="image/*" />
                    <br>
                    @error('file')
                    <small class="text-danger">*{{$message}}</small><br>
                    @enderror
                </div>
                <select class="form-select mt-3" name="categorias" >
                    @foreach($categorias as $categoria)
                    <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                    @endforeach
                </select>
                
                <select class="form-select mt-3" name="estado" >
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                <form method="post" action="{{ route('oferta.store') }}" enctype="multipart/form-data">
    @csrf
    <!-- ... Campos de creación de la oferta ... -->
    <button type="submit" class="btn btn-success mt-3">Guardar Oferta</button><br><br>
</form>

@if (isset($nuevaOferta))
    <a href="{{ route('oferta.agregar_productos', ['ofertaId' => $nuevaOferta->id]) }}" class="btn btn-primary mt-1">Agregar Productos</a>
@endif
            </form>
        </div>
    </div>
@endsection