<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">   <meta charset="utf-8">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


</head>
<body>
    
        
    @guest

                            
    @else
    
<nav class="navbar navbar-expand-md  shadow-sm barra">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav margen negrita">
                <li class="nav-item ms-3">
                    <a class="nav-link" href="{{ route('producto.index') }}">Productos</a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link" href="{{ route('oferta.index') }}">Ofertas</a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link" href="{{ route('importe') }}">Inventario</a>
                </li>
                <li class="nav-item ms-3">
                <a class="nav-link" href="{{ route('user.index') }}">Usuarios</a>
                </li>
            </ul>
                
            <ul class="navbar-nav ms-auto">
                @auth
                <li class="nav-item negrita">
                <a class="dropdown-item ms-2 negrita" href="{{ route('logout') }}"
            onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
            </form>
                </li>
                @endauth
            </ul>
            
        </div>
    </div>
</nav>
@endif

        
            @yield('content')
        </main>
    
</body>
</html>
