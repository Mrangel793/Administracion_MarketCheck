<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    </table>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Crear Productos</h1>
            <form action="{{ route('producto.store') }}" method="POST">
                @csrf
                <div class="input-group-text">
                    <span class="input-group-text">Codigo</span>
                    <input type="text" class="form-control" name="codigoProducto">
                </div>
                <select class="form-select mt-3" name="estado" >
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Nombre</span>
                    <input type="text" class="form-control" name="nombreProducto">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Descripción</span>
                    <input type="text" class="form-control" name="descripcionProducto">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Precio</span>
                    <input type="number" class="form-control" name="precioProducto">
                </div>
                <div class="input-group-text mt-3">
                    <span class="input-group-text">Stock</span>
                    <input type="number" class="form-control" name="numeroStock">
                </div> 
                <div class="mb-3">
                    <label for="id_categoria" class="form-label">Categoria:</label>
                    <select class="form-control m-2" name="id_categoria">
                        @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre}}</option>
                        @endforeach
                    </select>

                 </div> 
                 <div class="mb-3">
                    <label for="id_subcategoria" class="form-label">SubCategoria:</label>
                    <select class="form-control m-2" name="id_subcategoria">
                        @foreach ($subcategorias as $subcategoria)
                        <option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre}}</option>
                        @endforeach
                    </select>
                    
                 </div>
                <button type="submit" class="btn btn-success mt-3">Guardar Producto</button>
        </div>
    </div>
</body>
</html>