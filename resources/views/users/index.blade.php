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
        <h1>Gestión de Usuarios</h1>
        <hr class="border border-danger border-2 opacity-50">

        <a href="{{route('user.create')}}" class="btn btn-primary">Nuevo Usuario</a>
        
        <br>
        
        
        <br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Acciones</th>                    
                </tr>
            </thead>
        @if (count($users)>0)
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>
                <form action="{{route('user.destroy', ['user'=>$user->id])}}" method="POST">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger" >Eliminar</button>
                </form>
                </td>
            </tr>
            @endforeach
            </tbody>
            @endif
            
        </table>
        
    </div>

</body>
</html>