<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Pengguna')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background: #f8fafc; }
        .navbar-user { background: #18191a; }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; font-size: 1.5em; color: #fff !important; }
        .user-footer { background: #18191a; color: #fff; font-size: 0.95em; }
        .nav-link, .navbar-nav .nav-link.active { color: #fff !important; font-weight: 500; }
        .nav-link.active, .nav-link:focus, .nav-link:hover { color: #dc3545 !important; }
        .navbar-toggler { border: none; }
        .navbar-toggler:focus { box-shadow: none; }
        .user-avatar { background: #fff; color: #dc3545; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1em; }
        .btn-logout-user { background: #dc3545; color: #fff; border: none; font-weight: 500; }
        .btn-logout-user:hover, .btn-logout-user:focus { background: #b71c1c; color: #fff; }
        main .container, main .container-fluid { background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); padding: 2rem 1.5rem; margin-top: 2rem; }
        h1, h2, h3, h4, h5, h6 { color: #18191a; font-weight: bold; }
        .text-red { color: #dc3545 !important; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-user navbar-expand-lg navbar-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <i class="fas fa-couch text-red"></i> Meubel Jati Murni
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser" aria-controls="navbarUser" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarUser">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('dashboard-user') || request()->is('/')) active @endif" href="{{ Auth::check() ? route('dashboard.user') : url('/') }}"><i class="fas fa-home me-1"></i>Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('produk*')) active @endif" href="{{ route('produk.user') }}"><i class="fas fa-box-open me-1"></i>Produk</a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('profil*')) active @endif" href="{{ route('profil.user') }}"><i class="fas fa-user me-1"></i>Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('cart*')) active @endif" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i>Keranjang
                            @if(\App\Models\Cart::where('user_id', Auth::id())->count() > 0)
                                <span class="badge bg-danger rounded-pill">{{ \App\Models\Cart::where('user_id', Auth::id())->count() }}</span>
                            @endif
                        </a>
                    </li>
                    @endauth
                </ul>
                <div class="d-flex align-items-center gap-2 ms-lg-3 mt-3 mt-lg-0">
                    @auth
                        <span class="user-avatar"><i class="fas fa-user"></i></span>
                        <span class="text-white small d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-logout-user btn-sm ms-2">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3 fw-bold">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-danger btn-sm px-3 fw-bold">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    <footer class="user-footer text-center py-3 mt-5">
        &copy; {{ date('Y') }} Meubel Jati Murni. All rights reserved.
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html> 