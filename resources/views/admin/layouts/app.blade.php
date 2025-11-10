<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') - FACIGA 2025</title>
    <link rel="icon" href="{{ asset('images/icon-anpi.png') }}" type="image/x-icon">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --admin-primary: #0d6efd;
            --admin-sidebar-bg: #212529;
            --admin-sidebar-width: 260px;
        }

        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--admin-sidebar-width);
            background: var(--admin-sidebar-bg);
            padding: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .admin-sidebar .sidebar-brand {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar .sidebar-brand img {
            max-height: 40px;
        }

        .admin-sidebar .nav {
            padding: 1rem 0;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.75rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }

        .admin-sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
            border-left-color: var(--admin-primary);
        }

        .admin-sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: var(--admin-primary);
        }

        .admin-sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
        }

        /* Topbar */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: var(--admin-sidebar-width);
            right: 0;
            height: 70px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }

        /* Content */
        .admin-content {
            margin-left: var(--admin-sidebar-width);
            margin-top: 70px;
            min-height: calc(100vh - 70px);
        }

        /* Cards */
        .stats-card {
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
        }

        /* Badges */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        /* Mobile */
        @media (max-width: 992px) {
            :root {
                --admin-sidebar-width: 0;
            }

            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-topbar {
                left: 0;
            }

            .admin-content {
                margin-left: 0;
            }
        }

        /* Utilities */
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand text-center">
            <img src="{{ asset('images/faciga-logo.png') }}" alt="FACIGA" class="mb-2">
            <div class="text-white small">Administration</div>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Tableau de bord
                </a>
            </li>
            
            @if(Auth::guard('admin')->user()->canViewData())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.companies*') ? 'active' : '' }}" 
                   href="{{ route('admin.companies') }}">
                    <i class="bi bi-building"></i>
                    Entreprises
                    @php
                        $pendingCount = \App\Models\Company::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                    <span class="badge bg-warning text-dark ms-2">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.participants') ? 'active' : '' }}" 
                   href="{{ route('admin.participants') }}">
                    <i class="bi bi-people"></i>
                    Participants
                </a>
            </li>
            @endif

            @if(Auth::guard('admin')->user()->isSuperAdmin() || Auth::guard('admin')->user()->isModerator())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}" 
                   href="{{ route('admin.activity-logs') }}">
                    <i class="bi bi-list-check"></i>
                    Logs d'activité
                </a>
            </li>
            @endif

            <li class="nav-item mt-4">
                <hr class="text-white-50 mx-3">
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i>
                    Voir le site public
                </a>
            </li>

            <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        Déconnexion
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Topbar -->
    <div class="admin-topbar">
        <div class="d-flex align-items-center">
            <button class="btn btn-link d-lg-none text-dark" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h5 class="mb-0 ms-3">@yield('title', 'Administration')</h5>
        </div>

        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button class="btn btn-link text-dark text-decoration-none dropdown-toggle" 
                        type="button" 
                        data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <span class="d-none d-md-inline">{{ Auth::guard('admin')->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">
                            {{ Auth::guard('admin')->user()->email }}
                        </h6>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <span class="dropdown-item-text">
                            <i class="bi bi-shield-check text-primary me-2"></i>
                            Rôle: <strong>{{ ucfirst(Auth::guard('admin')->user()->role) }}</strong>
                        </span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Content -->
    <main class="admin-content">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="container-fluid pt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container-fluid pt-4">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('info'))
        <div class="container-fluid pt-4">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('show');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>