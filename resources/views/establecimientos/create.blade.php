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
        <h1>Nuevo Establecimiento</h1>
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

    <form method="post" action="{{ route('establecimiento.store') }}">
    @csrf
    <div class="mb-3">
        <label for="nit" class="form-label">Nit:</label>
        <input class="form-control" list="datalistOptions" type="number" name="Nit" placeholder="Nit del Establecimiento" value="{{ old('Nit') }}"/>
    </div>
    <div class="mb-3">
        <label for="estado" class="form-label">Estado:</label>
        <select name="Estado" id="estado" class="form-select">
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input class="form-control" list="datalistOptions" type="text" name="NombreEstablecimiento" placeholder="Nombre del Establecimiento" value="{{ old('NombreEstablecimiento') }}"/>
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección:</label>
        <input class="form-control" list="datalistOptions" type="text" name="DireccionEstablecimiento" placeholder="Direccion del Establecimiento" value="{{ old('DireccionEstablecimiento') }}"/>
    </div>
    <div class="mb-3">
        <label for="correo" class="form-label">Correo del Establecimiento:</label>
        <input class="form-control" list="datalistOptions" type="text" name="CorreoEstablecimiento" placeholder="Correo del Establecimiento" value="{{ old('CorreoEstablecimiento') }}"/>
    </div>
    <div class="mb-3">
        <label for="Lema" class="form-label">Lema:</label>
        <input class="form-control" list="datalistOptions" type="text" name="Lema" placeholder="Lema del Establecimiento" value="{{ old('Lema') }}"/>
    </div>
    
    <div class="mb-3">
        <label for="Imagen" class="form-label">URL de Logo:</label>
        <input class="form-control" list="datalistOptions" type="text" name="Logo" placeholder="URL Logo Establecimiento" value="{{ old('Logo') }}"/>
    </div>
    <div class="mb-3">
        <label for="Imagen" class="form-label">URL de Imagen:</label>
        <input class="form-control" list="datalistOptions" type="text" name="Imagen" placeholder="URL Logo Establecimiento" value="{{ old('Imagen') }}"/>
    </div>
    <div class="mb-3">
        <label for="Color Establecimiento" class="form-label">Color de Interfaz</label>
        <input type="color" class="form-control form-control-color" id="colorPicker" name="ColorInterfaz" value="#563d7c" title="Escoge un Color" value="{{ old('ColorInterfaz') }}">
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('establecimiento.index') }}" class="btn btn-secondary ms-2">Volver</a>
    </div>
    </form>
    </div>
</body>
</html>