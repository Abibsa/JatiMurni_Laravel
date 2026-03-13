<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Meubel Jati Murni</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #000 60%, #B22222 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        .login-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 500px;
            width: 100%;
        }
        .brand {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .brand-logo {
            background: #B22222;
            color: #fff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 0.5rem auto;
        }
        .brand-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #B22222;
            letter-spacing: 1px;
        }
        .form-label {
            color: #B22222;
            font-weight: 500;
        }
        .btn-login {
            background: #B22222;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background: #000;
            color: #fff;
        }
        .card-header {
            background: none;
            border-bottom: none;
            padding-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="brand-logo">
                <i class="fas fa-couch"></i>
            </div>
            <div class="brand-title">Meubel Jati Murni</div>
        </div>
        <div class="card-header text-center p-0 mb-3">
            <h5 class="mb-0" style="color:#B22222;">Buat Akun Baru</h5>
        </div>
        <div class="card-body p-0">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor WhatsApp</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                </div>
                <button type="submit" class="btn btn-login w-100 mb-3">Daftar Sekarang</button>
                <div class="text-center">
                    <span class="text-muted">Sudah punya akun? </span>
                    <a href="{{ route('login') }}" style="color: #B22222; text-decoration: none; font-weight: bold;">Login di sini</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
