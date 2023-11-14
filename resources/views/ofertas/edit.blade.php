@extends('layouts.app')

@section('content')

<body>
<div class="container mt-5">
<h1>Editar Oferta</h1>
    <hr class="border color-background2 border-2 opacity-100">
        
            <form method="post" action="{{route('oferta.update',$oferta->id)}}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
                <div class="input-group-text">
                    <span class="input-group-text">Fecha de Inicio</span>
                    <input type="date" name="fecha_inicio" class="form-control" placeholder="Fecha de Inicio" value="{{$oferta->fecha_inicio}}">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Fecha de fin</span>
                    <input type="date" name="fecha_fin" class="form-control" placeholder="Fecha de Inicio" value="{{$oferta->fecha_fin}}">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Nombre Oferta</span>
                    <input class="form-control" type="text" name="nombre" value="{{$oferta->nombre}}" /><br>
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Descripción Oferta</span>
                    <input class="form-control" type="text" name="descripcion" value="{{$oferta->descripcion}}" /><br>
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Cantidad Stock</span>
                    <input class="form-control" type="number" name="numero_stock" value="{{$oferta->numero_stock}}"/><br>
                </div>
                <div class="input-group-text mt-3">
                <img src="{{ asset($oferta-> imagen) }}" alt="Imagen actual" width="150px">
                    <input class="form-control" type="file" name="file" accept="image/*" />
                    <br>
                    @error('file')
                    <small class="text-danger">*{{$message}}</small><br>
                    @enderror   
                </div>
                
                <select class="form-select mt-3" name="estado" >
                    <option value="1" @if($oferta->estado == 1)selected @endif>Activo</option>
                    <option value="0" @if($oferta->estado == 0)selected @endif>Inactivo</option>
                </select>
                <div>
                <button type="submit" class="btn btn-success mt-3">Guardar Oferta</button>
                <a href="{{ route('oferta.index') }}" class="btn btn-secondary mt-3">Volver</a>
                </div><br><br><br>
            
            </form>

        </div>
    </div>
@endsection