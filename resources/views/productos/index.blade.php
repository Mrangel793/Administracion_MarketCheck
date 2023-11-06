<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>Productos</title>

</head>
<body>

    <div class="container">
    <a href="{{route('producto.create')}}" class="btn btn-primary">Nuevo Producto</a>
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
                <td>{{$producto->precioProducto}}</td>
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
</body>
</html>