<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Crear Compras</title>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Carga de productos</h1>
                <form method="post" action="{{route('compras.consultar')}}">
                    @csrf
                <input type="text" name="nombre" placeholder="Buscar Producto">
                <button type="submit" class="btn btn-success">Buscar</button>
                </form>
        </div>
        <div class="card-body">
        <form method="post" action="{{route('compras.guardar')}}">
                @csrf
            <table class="table table-striped">
                <thead>
                    <tr >
                        <th >Id</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr class="table-info">
                        <td class="table-info" name="id">{{ $producto['id'] }}</td>
                        <td class="table-info">{{ $producto['codigoProducto'] }}</td>
                        <td class="table-info">{{ $producto['nombreProducto']  }}</td>
                        <td class="table-info">{{ $producto['precioProducto']  }}</td>
                        <td><input type="number" name="cantidadProducto[{{ $producto->id }}]" class="form-control" value="{{ old('cantidadProducto.' . $producto->id) }}"></td>
                        <td class="table-info" name="total">{{$producto->total}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
                <button type="submit" class="btn btn-success mt-3">Guardar Compra</button>
            </form>
                <a href="{{route('compras.index')}}"><button type="submit" class="btn btn-danger mt-3">Volver a Inicio</button>
            </div>
        </div>
        </div>     
    </div>
</body>
</html>