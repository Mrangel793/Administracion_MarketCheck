<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>Document</title>
</head>
<body>
<div class="container">
        <h1>Editar Establecimiento</h1>
        <hr class="border border-danger border-2 opacity-50">
        
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
             @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('establecimiento.update',['establecimiento'=>$establecimiento->id]) }}">
    @csrf
    @method('PUT')

    <input type="hidden" name="id" value="{{ $establecimiento->id }}">

    <div class="mb-3">
        <label for="nit" class="form-label">Nit:</label>
        <input class="form-control" list="datalistOptions" type="number" name="Nit" placeholder="Nit del Establecimiento" value="{{ $establecimiento->Nit }}"/>
    </div>
    <div class="mb-3">
     <label for="estado" class="form-label">Estado:</label>
     <select name="Estado" id="estado" class="form-select">
          <option value="1" @if ($establecimiento->Estado == 1) selected @endif>Activo</option>
            <option value="0" @if ($establecimiento->Estado == 0) selected @endif>Inactivo</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input class="form-control" list="datalistOptions" type="text" name="NombreEstablecimiento" placeholder="Nombre del Establecimiento" value="{{ $establecimiento->NombreEstablecimiento }}"/>
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección:</label>
        <input class="form-control" list="datalistOptions" type="text" name="DireccionEstablecimiento" placeholder="Direccion del Establecimiento" value="{{ $establecimiento->DireccionEstablecimiento }}"/>
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label">Correo del Establecimiento:</label>
        <input class="form-control" list="datalistOptions" type="text" name="Correo del Establecimiento" placeholder="Direccion del Establecimiento" value="{{ $establecimiento->CorreoEstablecimiento }}"/>
    </div>
    <div class="mb-3">
        <label for="Lema" class="form-label">Lema:</label>
        <input class="form-control" list="datalistOptions" type="text" name="Lema" placeholder="Lema del Establecimiento" value="{{ $establecimiento->Lema }}"/>
    </div>
    <div class="mb-3">
        <label for="Imagen" class="form-label">URL de Logo:</label>
        <input class="form-control" list="datalistOptions" type="text" name="Logo" placeholder="URL Logo Establecimiento" value="{{ $establecimiento->Logo }}"/>
    </div>
    <div class="mb-3">
        <label for="Imagen" class="form-label">URL de Imagen:</label>
        <input class="form-control" list="datalistOptions" type="text" name="Imagen" placeholder="URL Logo Establecimiento" value="{{ $establecimiento->Imagen }}"/>
    </div>
    <div class="mb-3">
        <label for="Color Establecimiento" class="form-label">Color de Interfaz</label>
        <input type="color" class="form-control form-control-color" id="colorPicker" name="ColorInterfaz" value="{{ $establecimiento->ColorInterfaz }}" title="Escoge un Color"/>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('establecimiento.index') }}" class="btn btn-secondary ms-2">Volver</a>
    </div>
</form>
    </div>
</body>
</html>