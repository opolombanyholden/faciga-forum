<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FACIGA 2025 - Forum d\'Affaires Côte d\'Ivoire Gabon')</title>
    <link rel="icon" href="{{ asset('images/icon-anpi.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --orange-ci: #FF8C00;      /* Orange Côte d'Ivoire */
            --blue-gabon: #3A75C4;     /* Bleu Gabon */
            --green-gabon: #009E49;    /* Vert Gabon */
            --white: #FFFFFF;
        }
        
        .navbar-brand img {
            height: 50px;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--orange-ci), var(--blue-gabon), var(--green-gabon));
            color: white;
            padding: 100px 0;
        }
        
        .btn-primary {
            background-color: var(--orange-ci);
            border-color: var(--orange-ci);
        }
        
        .btn-primary:hover {
            background-color: #e67e00;
            border-color: #e67e00;
        }
        
        .btn-success {
            background-color: var(--green-gabon);
            border-color: var(--green-gabon);
        }
        
        .btn-success:hover {
            background-color: #008a3f;
            border-color: #008a3f;
        }
        
        .text-primary {
            color: var(--blue-gabon) !important;
        }
        
        .bg-primary {
            background-color: var(--blue-gabon) !important;
        }
        
        .card-faciga {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            border-top: 3px solid var(--orange-ci);
        }
        
        .card-faciga:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
        }
        
        footer {
            background: linear-gradient(90deg, var(--blue-gabon), var(--green-gabon));
            color: white;
        }
        
        .navbar {
            border-bottom: 3px solid;
            border-image: linear-gradient(90deg, var(--orange-ci), var(--green-gabon), var(--blue-gabon)) 1;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="/images/faciga-logo.png" alt="FACIGA" class="me-2">
                <img src="/images/anpi-logo.png" alt="ANPI" class="ms-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inscription') }}">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                    @auth('company')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth('company')->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('company.dashboard') }}">Tableau de bord</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3" href="{{ route('login') }}">Connexion</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @yield('content')

    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>FACIGA 2025</h5>
                    <p>Forum d'Affaires Côte d'Ivoire - Gabon<br>
                    09-10 Octobre 2025<br>
                    Radisson Blu, Libreville</p>
                </div>
                <div class="col-md-4">
                    <h5>Organisateurs</h5>
                    <p>CEPICI - Côte d'Ivoire<br>
                    ANPI-Gabon<br>
                    Ambassade de Côte d'Ivoire au Gabon</p>
                </div>
                <div class="col-md-4">
                    <h5>Contact ANPI-Gabon</h5>
                    <p>
                        <i class="bi bi-geo-alt"></i> 104 Rue Gustave ANGUILE, Serena Mall<br>
                        <i class="bi bi-telephone"></i> 011 76 48 48<br>
                        <i class="bi bi-envelope"></i> contact@investingabon.ga
                    </p>
                </div>
            </div>
            <hr class="mt-4 mb-3">
            <p class="text-center mb-0">&copy; 2025 FACIGA. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>