<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Portal de Clientes - Rocket Lubricentro' }}</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- AdminLTE / Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f4f6f9;
        }
        .nav-pills-custom .nav-link {
            color: #495057;
            background-color: #e9ecef;
            border-radius: 0.5rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }
        .nav-pills-custom .nav-link.active {
            color: #fff;
            background-color: #007bff;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.25);
        }
        @media (max-width: 575.98px) {
            .nav-pills-custom .nav-link {
                width: 100%;
                margin-right: 0;
                text-align: center;
            }
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
        }
    </style>
    @livewireStyles
</head>
<body class="bg-light">
    <!-- NAVBAR DEL PORTAL DE CLIENTES -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-warning d-flex align-items-center" href="{{ route('portal.cliente') }}">
                <i class="fas fa-oil-can mr-2 fa-lg"></i>
                <span>ROCKET <small class="text-white-50 font-weight-normal d-none d-sm-inline">LUBRICENTRO</small></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarPortal" aria-controls="navbarPortal" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-2 mt-md-0" id="navbarPortal">
                <ul class="navbar-nav ml-auto align-items-md-center">
                    <li class="nav-item mb-2 mb-md-0 mr-md-3 text-white-50">
                        <i class="fas fa-user-circle mr-1 text-warning"></i> {{ Auth::user()->name }}
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm btn-block text-left text-md-center">
                                <i class="fas fa-sign-out-alt mr-1"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="py-3 py-md-4">
        <div class="container">
            {{ $slot }}
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="text-center py-3 text-muted border-top mt-4 bg-white">
        <div class="container">
            <small>&copy; {{ date('Y') }} Rocket Lubricentro. Todos los derechos reservados.</small>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    @livewireScripts
</body>
</html>
