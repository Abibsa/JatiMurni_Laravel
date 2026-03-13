<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Meubel Jati Murni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #B22222;
            --secondary-color: #000000;
            --accent-color: #FFFFFF;
            --sidebar-width: 280px;
            --navbar-height: 70px;
        }
        
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        .sidebar {
            background-color: var(--secondary-color);
            min-height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: var(--accent-color);
            margin: 5px 0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link:hover {
            background-color: var(--primary-color);
            color: var(--accent-color);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            font-weight: 600;
        }
        
        .sidebar .nav-link i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .navbar {
            background-color: var(--secondary-color);
            height: var(--navbar-height);
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .navbar-brand {
            color: var(--accent-color);
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .navbar .nav-link {
            color: var(--accent-color);
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .navbar .nav-link:hover {
            background-color: var(--primary-color);
        }
        
        .navbar .dropdown-menu {
            background-color: var(--secondary-color);
            border: none;
            border-radius: 8px;
            padding: 10px;
        }
        
        .navbar .dropdown-item {
            color: var(--accent-color);
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .navbar .dropdown-item:hover {
            background-color: var(--primary-color);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-menu .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-color);
            font-weight: 600;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--primary-color);
            color: var(--accent-color);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: var(--accent-color);
            border-radius: 10px 10px 0 0 !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #8B0000;
            border-color: #8B0000;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="text-center mb-4">
                <h3 class="text-white">Meubel Jati Murni</h3>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard-admin') ? 'active' : '' }}" href="{{ route('dashboard.admin') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/produk*') ? 'active' : '' }}" href="{{ route('produk.index') }}">
                        <i class="fas fa-box"></i> Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/pesanan*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i> Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('pengguna') ? 'active' : '' }}" href="{{ route('pengguna.index') }}">
                        <i class="fas fa-users"></i> Pengguna
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('pembayaran') ? 'active' : '' }}" href="{{ route('pembayaran.index') }}">
                        <i class="fas fa-money-bill-wave"></i> Pembayaran
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100" style="color: var(--accent-color);">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <button class="navbar-toggler text-white" type="button" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-bell"></i>
                                    <span class="notification-badge">3</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-shopping-cart me-2"></i>Pesanan baru #123
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-money-bill-wave me-2"></i>Pembayaran diterima
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-box me-2"></i>Stok produk menipis
                                    </a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">
                                    <div class="user-menu">
                                        <div class="avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="d-none d-md-block">
                                            <div class="text-white">Admin</div>
                                            <div class="text-white-50 small">Administrator</div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-cog me-2"></i>Pengaturan
                                    </a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }
    </script>
    @yield('scripts')
</body>
</html> 