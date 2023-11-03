<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar ofertas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Editar Oferta</h2>
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
                <select class="form-select mt-3" name="categorias" >
                    @foreach($categorias as $categoria)
                    <option value="{{$categoria->id}}" @if($oferta->categoria_id == $categoria->id)selected @endif>{{$categoria->nombre}}</option>
                    @endforeach
                </select>
                <select class="form-select mt-3" name="estado" >
                    <option value="1" @if($oferta->estado == 1)selected @endif>Activo</option>
                    <option value="0" @if($oferta->estado == 0)selected @endif>Inactivo</option>
                </select>
                <button type="submit" class="btn btn-success mt-3">Guardar Oferta</button>
            
            </form>
        </div>
    </div>
</body>
</html>