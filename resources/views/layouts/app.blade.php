<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Licoreras - Sistema de Inventario')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--light-color);
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            transition: all 0.3s;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            z-index: 1000;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar .logo {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .logo h4 {
            color: white;
            margin: 0;
            font-weight: 700;
        }
        
        .sidebar .logo i {
            font-size: 2rem;
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem 1.5rem;
            border-radius: 0;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .sidebar.collapsed + .main-content {
            margin-left: 70px;
        }
        
        /* Navbar */
        .top-navbar {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .toggle-sidebar {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-left: 4px solid;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.primary { border-left-color: var(--primary-color); }
        .stat-card.success { border-left-color: var(--success-color); }
        .stat-card.info { border-left-color: var(--info-color); }
        .stat-card.warning { border-left-color: var(--warning-color); }
        .stat-card.danger { border-left-color: var(--danger-color); }
        
        .stat-card .icon {
            font-size: 2rem;
            opacity: 0.3;
        }
        
        .stat-card.primary .icon { color: var(--primary-color); }
        .stat-card.success .icon { color: var(--success-color); }
        .stat-card.info .icon { color: var(--info-color); }
        .stat-card.warning .icon { color: var(--warning-color); }
        .stat-card.danger .icon { color: var(--danger-color); }
        
        /* Tables */
        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            overflow: hidden;
        }
        
        .table-card .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        
        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
        }
        
        .btn-rounded {
            border-radius: 50px;
        }
        
        /* Forms */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 0.6rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        /* Badge */
        .badge {
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 500;
        }
        
        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 1.5rem;
        }
        
        .page-link {
            border-radius: 8px;
            margin: 0 3px;
            color: var(--primary-color);
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Dropdown */
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
        }
        
        /* User dropdown */
        .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--dark-color);
        }
        
        .user-dropdown .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar .nav-link span {
                display: none;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }
        
        /* Scanner animation */
        .scanner-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: var(--success-color);
            animation: scan 2s linear infinite;
            box-shadow: 0 0 10px var(--success-color);
        }
        
        @keyframes scan {
            0% { top: 0; }
            50% { top: 100%; }
            100% { top: 0; }
        }
        
        /* Print styles */
        @media print {
            .sidebar, .top-navbar, .no-print {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="logo">
            <i class="fas fa-wine-bottle"></i>
            <h4 class="mt-2">LICORERAS</h4>
        </div>
        
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                    <i class="fas fa-box"></i>
                    <span>Productos</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" href="{{ route('categorias.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Categorías</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}" href="{{ route('proveedores.index') }}">
                    <i class="fas fa-truck"></i>
                    <span>Proveedores</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Clientes</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="{{ route('ventas.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Ventas</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}" href="{{ route('inventario.entradas') }}">
                    <i class="fas fa-warehouse"></i>
                    <span>Inventario</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('gastos.*') ? 'active' : '' }}" href="{{ route('gastos.index') }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Gastos</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
            
            @if(auth()->user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                    <i class="fas fa-user-cog"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('configuracion.*') ? 'active' : '' }}" href="{{ route('configuracion.index') }}">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="user-dropdown dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="avatar">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                        <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('perfil') }}">
                            <i class="fas fa-user me-2"></i> Mi Perfil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('password.change') }}">
                            <i class="fas fa-key me-2"></i> Cambiar Contraseña
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Alerts -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        
        <!-- Page Content -->
        @yield('content')
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script>
        // Toggle sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        // Format currency
        function formatCurrency(amount) {
            return '$ ' + parseFloat(amount).toLocaleString('es-CO', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }
        
        // Format number
        function formatNumber(number) {
            return parseInt(number).toLocaleString('es-CO');
        }
    </script>
    
    @stack('scripts')
</body>
</html>
