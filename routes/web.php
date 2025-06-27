<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\TransaksiController;

// Redirect root ke dashboard sesuai role
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($user->role === 'customer') {
            return redirect()->route('dashboard.user');
        }
    }
    return redirect()->route('login');
});

// =====================
// Dashboard Routes
// =====================
// Dashboard untuk admin
Route::get('/dashboard-admin', function () {
    return view('admin.dashboard.index');
})->name('dashboard.admin');

// Dashboard untuk pengguna
Route::get('/dashboard-user', function () {
    return view('pengguna.dashboard.index');
})->name('dashboard.user');

// =====================
// User (Pengguna) Routes
// =====================
Route::prefix('pengguna')->name('pengguna.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    // Fitur tambahan
    Route::post('/import', [UserController::class, 'import'])->name('import');
    Route::post('/export', [UserController::class, 'export'])->name('export');
    Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
    Route::post('/bulk-update', [UserController::class, 'bulkUpdate'])->name('bulk-update');
    Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
});

// =====================
// Payment Routes
// =====================
Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('index');
    Route::post('/', [PaymentController::class, 'store'])->name('store');
    Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
    Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
    Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
});

// =====================
// Product Routes (hanya untuk admin)
// =====================
Route::middleware(['auth'])->prefix('admin/produk')->name('produk.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
});

// Resource route untuk payments
Route::resource('pembayaran', PaymentController::class);

// =====================
// Login & Logout Routes
// =====================
Route::get('/login', function () {
    return view('login.index');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    $role = $request->role;

    $user = \App\Models\User::where('email', $credentials['email'])->first();
    if (!$user) {
        return back()->with(['error' => 'Email tidak ditemukan.', 'error_type' => 'email']);
    }
    if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
        return back()->with(['error' => 'Password salah.', 'error_type' => 'password']);
    }
    if (($user->role === 'admin' && $role === 'admin') || ($user->role === 'customer' && $role === 'pengguna')) {
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.admin');
            } else {
                return redirect()->route('dashboard.user');
            }
        }
    } else {
        return back()->with(['error' => 'Role yang dipilih tidak sesuai.', 'error_type' => 'role']);
    }
    return back()->with(['error' => 'Email atau password salah.', 'error_type' => 'password']);
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('/produk', [UserProductController::class, 'index'])->name('produk.user');

Route::get('/profil', [UserController::class, 'profil'])
    ->middleware('auth')
    ->name('profil.user');

Route::get('/logout', function () {
    return redirect('/'); // Atau redirect ke halaman login
});

Route::get('/produk/{id}', [UserProductController::class, 'show'])->name('produk.detail');



Route::post('/pesanan/tambah/{id}', [UserProductController::class, 'tambah'])->name('pesanan.tambah');

Route::get('/transaksi/{id}', [TransaksiController::class, 'detail'])->name('transaksi.detail');


