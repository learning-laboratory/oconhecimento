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
    <nav class="navbar navbar-expand-md navbar-light fixed-top navbar-gray shadow-sm ">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">O Conhecimento</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarCollapse">
                <ul class="navbar-nav  ms-auto mb-2 mb-md-0 me-5">
                    <li class="nav-item text-uppercase">
                        <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Inicio</a>
                    </li>
                    <li class="nav-item text-uppercase">
                        <a class="nav-link" aria-current="page" href="#">Sobre</a>
                    </li>
                    <li class="nav-item text-uppercase">
                        <a class="nav-link" aria-current="page" href="{{ route('blog.contact') }}">Contacto</a>
                    </li>
                </ul>
                <div>
                    <a  href="{{ route('login') }}" class="btn btn-outline-primary ">
                        <i class="fas fa-user-alt"></i>
                        Entrar
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-white">
                        Registar-se
                    </a>
                </div>
            </div>
        </div>
    </nav>


    <main class="py-4">
        @yield('content')
    </main>

    <footer class="footer shadow-sw">
        <div class="container pt-5 pb-3">
            <div class="row">
                <div class="col-md-5">
                    <h2 class="footer-title py-1">O Conhecimento</h2>
                    <p class="footer-description">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Rem quidem
                        quo harum inventore, error assumenda.</p>
                    <div class="mt-md-4">
                        <h2 class="footer-title py-1">Siga-nos</h2>
                        <ul class="footer-social list-unstyled d-flex">
                            <li>
                                <a href="https://www.facebook.com/o-conhecimento-1234" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/o_conhecimento" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/o_conhecimento" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/channel/UC-lHJZR3Y5-_xZ5i7mX5bQ" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-3">
                            <h2 class="footer-title py-1">Disciplinas</h2>
                            <ul class="list-unstyled footer-description">
                                @forelse ($categories as $category)
                                    <li>
                                        <a href="{{ $category->getLink() }}"
                                            class="text-dark text-decoration-none">{{ $category->name }}</a>
                                    </li>
                                @empty

                                @endforelse
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <h2 class="footer-title">Paginas</h2>
                            <ul class="list-unstyled footer-description">
                                <li><a class="text-dark text-decoration-none" href="{{ route('home') }}">Inicio</a>
                                </li>
                                <li><a class="text-dark text-decoration-none"
                                        href="{{ route('blog.articles') }}">Artigos</a></li>
                                <li><a class="text-dark text-decoration-none"
                                        href="{{ route('blog.contact') }}">Contacto</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <h2 class="footer-title py-1">Lorem Ipsum</h2>
                                <p class="footer-description">Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                                    Inventore, odit!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <hr>
                <div class=" d-flex justify-content-center">
                    <p class="text-muted">
                        <strong><a href="{{ route('home') }}" class="text-muted text-decoration-none">O
                                Conhecimento</a></strong> &copy; {{ date('Y') }} - Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    @yield('js')
</body>

</html>
