<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">    <title>Establecimientos</title>
</head>
<body>
<div class="container">
        <h1>Gestión de Establecimientos</h1>
        <hr class="border border-danger border-2 opacity-50">

        <a href="{{route('establecimiento.create')}}" class="btn btn-primary">Nuevo Establecimiento</a>
        
        <br>
       
        
        <br>
    
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nit</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Lema</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                    
                </tr>
            </thead>
        @if (count($establecimientos)>0)
            <tbody>
                @foreach ($establecimientos as $establecimiento)
                <tr>
                    <td>{{$establecimiento->id}}</td>
                    <td>{{$establecimiento->Nit}}</td>
                    <td>{{$establecimiento->NombreEstablecimiento}}</td>
                    <td>{{$establecimiento->DireccionEstablecimiento}}</td>
                    <td>{{$establecimiento->Lema}}</td>
                    <td>
                        @if ($establecimiento->Estado == 1)
                            Activo
                        @else
                            Inactivo
                        @endif
                    </td>
                    <td>
                    <a href="{{route('establecimiento.edit',['establecimiento'=> $establecimiento->id])}}" class="btn btn-warning ">Editar</a>
                    <form action="{{route('establecimiento.destroy', ['establecimiento'=>$establecimiento->id])}}" method="POST">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger" >Eliminar</button>
                    </form>
                    </td>
                     
                  
                </tr>
                @endforeach
            </tbody>
         </table>
        @endif
        
    </div>
</body>
</html>