<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">    <title>Establecimientos</title>

    <title>Document</title>
</head>
<body>
<div class="container">
    
        <h1>Nuevo Usuario</h1>
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
        <form method="post" action="{{ route('user.store') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input class="form-control" list="datalistOptions" type="text" name="name" placeholder="Nombre del Usuario" value="{{ old('name') }}"/>
            </div>

            <p class="text-muted">
                 La contraseña será configurada automáticamente con el mismo nombre de usuario.
            </p>

            <div class="mb-3">
                <label for="email" class="form-label">Correo:</label>
                <input class="form-control" list="datalistOptions" type="text" name="email" placeholder="Correo del Usuario" value="{{ old('email') }}"/>
            </div>

            <div class="mb-3">
                <label for="establecimiento_id" class="form-label">Establecimiento:</label>
                <select class="form-control m-2" name="establecimiento_id">
                    @foreach ($establecimientos as $establecimiento)
                      <option value="{{ $establecimiento->id }}">{{ $establecimiento->NombreEstablecimiento." — ". $establecimiento->Nit }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="{{ route('user.index') }}" class="btn btn-secondary ms-2">Volver</a>
            </div>

        </form>
</div>

</body>
</html>