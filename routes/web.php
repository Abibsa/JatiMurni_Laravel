<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ReviewController;

// Redirect root ke dashboard sesuai role jika login, atau ke katalog jika guest
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($user->role === 'customer') {
            return redirect()->route('dashboard.user');
        }
    }
    // Jika belum login, redirect ke halaman produk (katalog publik)
    return redirect()->route('produk.user');
});

// =====================
// Login & Logout Routes
// =====================
Route::get('/login', function () {
    return view('login.index');
})->name('login');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    $role = $request->role;

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        if (($user->role === 'admin' && $role === 'admin') || ($user->role === 'customer' && $role === 'pengguna')) {
            $request->session()->regenerate();
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.admin');
            } else {
                return redirect()->route('dashboard.user');
            }
        } else {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->with(['error' => 'Role yang dipilih tidak sesuai.', 'error_type' => 'role']);
        }
    }
    return back()->with(['error' => 'Email atau password salah.', 'error_type' => 'password']);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Redudant GET logout fallback
Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
});

// Public Product Routes
Route::get('/produk', [UserProductController::class, 'index'])->name('produk.user');
Route::get('/produk/{id}', [UserProductController::class, 'show'])->name('produk.detail');

// =====================
// PROTECTED ROUTES
// =====================
Route::middleware(['auth'])->group(function () {
    
    // Shared user profile route
    Route::get('/profil', [UserController::class, 'profil'])->name('profil.user');

    // =====================
    // Admin Routes
    // =====================
    Route::middleware(['admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard-admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');

        // CRUD Pesanan / Order Manager
        Route::prefix('admin/pesanan')->name('admin.orders.')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])->name('index');
            Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
            Route::put('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('update-status');
            Route::put('/{id}/payment-confirm', [AdminOrderController::class, 'confirmPayment'])->name('confirm-payment');
            Route::put('/{id}/payment-reject', [AdminOrderController::class, 'rejectPayment'])->name('reject-payment');
            Route::get('/{id}/invoice', [TransaksiController::class, 'invoice'])->name('invoice');
        });

        // CRUD Pengguna
        Route::prefix('pengguna')->name('pengguna.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/import', [UserController::class, 'import'])->name('import');
            Route::post('/export', [UserController::class, 'export'])->name('export');
            Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk-update', [UserController::class, 'bulkUpdate'])->name('bulk-update');
            Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        });

        // CRUD Pembayaran
        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('index');
            Route::post('/', [PaymentController::class, 'store'])->name('store');
            Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
            Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
            Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
        });

        // CRUD Produk
        Route::prefix('admin/produk')->name('produk.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });
    });

    // =====================
    // User Routes
    // =====================
    Route::middleware(['customer'])->group(function () {
        // Dashboard
        Route::get('/dashboard-user', function () {
            return view('pengguna.dashboard.index');
        })->name('dashboard.user');

        Route::post('/pesanan/tambah/{id}', [UserProductController::class, 'tambah'])->name('pesanan.tambah');
        Route::get('/transaksi/{id}', [TransaksiController::class, 'detail'])->name('transaksi.detail');
        Route::post('/transaksi/{id}/pembayaran', [TransaksiController::class, 'uploadPayment'])->name('transaksi.bayar');
        Route::get('/transaksi/{id}/invoice', [TransaksiController::class, 'invoice'])->name('transaksi.invoice');

        // Cart Routes
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

        // Review Routes
        Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
    });
});
