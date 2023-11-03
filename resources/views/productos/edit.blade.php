<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Editar Producto</h1>
            <form action="{{route('producto.update',$producto->id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="input-group-text">
                    <span class="input-group-text">Codigo</span>
                    <input type="text" class="form-control" name="codigoProducto" value="{{$productos->codigoProducto}}">
                </div>
                <select class="form-select mt-3" name="estado" >
                    <option value="1" >Activo</option>
                    <option value="0" >Inactivo</option>
                </select>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Nombre</span>
                    <input type="text" class="form-control" name="nombreProducto" value="{{$productos->nombreProducto}}">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Descripción</span>
                    <input type="text" class="form-control" name="descripcionProducto" value="{{$productos->descripcionProducto}}">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Precio</span>
                    <input type="number" class="form-control" name="precioProducto" value="{{$productos->precioProducto}}">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Stock</span>
                    <input type="number" class="form-control" name="numeroStock" value="{{$productos->numeroStock}}">
                </div>
                <button type="submit" class="btn btn-success mt-3">Editar Producto</button>
        </div>
    </div>
</body>
</html>