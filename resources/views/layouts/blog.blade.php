<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'O Conhecimento') }}</title>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    @yield('css')

</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-white shadow-sm ">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">O Conhecimento</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blog.articles') }}">Artigos</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Disciplinas
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                            <li><a class="dropdown-item" href="#">Matematica</a></li>
                            <li><a class="dropdown-item" href="#">Fisica</a></li>
                            <li><a class="dropdown-item" href="#">Quimica</a></li>
                            <li><a class="dropdown-item" href="#">Portugues</a></li>
                            <li><a class="dropdown-item" href="#">Historia</a></li>
                            <li><a class="dropdown-item" href="#">Geografia</a></li>
                            <li><a class="dropdown-item" href="#">Biologia</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('blog.contact') }}" class="nav-link">Contacto</a>
                    </li>
                </ul>
                <div>
                    <button class="btn mx-1">
                         <i class="fas fa-user-alt"></i>
                         Entrar
                    </button>
                    <button class="btn btn-dark mx-1">
                        Registar-se
                    </button>
                </div>
            </div>
        </div>
    </nav>



    <main class="py-4">
        @yield('content')
    </main>

    <footer class="footer shadow-sw bg-white">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-5">
                    <h2 class="footer-title">O Conhecimento</h2>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Rem quidem quo harum inventore, error assumenda.</p>
                </div>
                <div class="col-md-7">
                    <div class="row row-cols-3">
                        <div>
                            <h2 class="footer-title">Paginas</h2>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('home') }}">Inicio</a></li>
                                <li><a href="{{ route('blog.articles') }}">Artigos</a></li>
                                <li><a href="{{ route('blog.contact') }}">Contacto</a></li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="footer-title">Disciplinas</h2>
                            <ul class="list-unstyled">
                                <li><a href="#">Matematica</a></li>
                                <li><a href="#">Fisica</a></li>
                                <li><a href="#">Portugues</a></li>
                                <li><a href="#">Quimica</a></li>
                                <li><a href="#">Geografia</a></li>
                                <li><a href="#">Biologia</a></li>
                                <li><a href="#">Historia</a></li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="footer-title">Lorem Ipsum</h2>
                            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Inventore, odit!</p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni, officiis!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @yield('js')


</body>

</html>
