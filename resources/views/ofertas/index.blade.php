<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
<div class="container">
<a href="{{route('oferta.create')}}" class="btn btn-primary">Nuevo Producto</a>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Estado</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Número de Stock</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($ofertas as $oferta)
                        <tr>
                            <td>{{ $oferta->id }}</td>
                            <td>{{ $oferta->estado }}</td>
                            <td>{{ $oferta->fecha_inicio }}</td>
                            <td>{{ $oferta->fecha_fin }}</td>
                            <td>{{ $oferta->nombre }}</td>
                            <td>{{ $oferta->descripcion }}</td>
                            <td><img src="{{ asset($oferta->imagen) }}" alt="Imagen actual" width="150px"></td>
                            <td>{{ $oferta->numero_stock }}</td>
                            <td>{{ $oferta->categoria->nombre }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('oferta.edit', $oferta->id) }}" class="btn btn-warning">Editar</a>
                                    <form method="post" action="{{ route('oferta.destroy', $oferta->id) }}">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                
            </tbody>
        </table>
    </div>
</body>
</html>