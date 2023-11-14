<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Compras</title>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Compras</h1>
                <form method="post" action="{{route('compras.store')}}">
                @csrf
                <button type="submit" class="btn btn-primary">Crear Compra</button>
                </form>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col"></th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Cantidad</th>
                                </tr>
                            </thead>

                            </table>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>

</body>
</html>