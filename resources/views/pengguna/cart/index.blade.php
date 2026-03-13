@extends('pengguna.layouts.user')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Keranjang Belanja</h2>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    @if($carts->isEmpty())
        <div class="alert alert-info mt-3">Keranjang belanja Anda masih kosong. <a href="{{ route('produk.user') }}">Mulai Belanja</a></div>
    @else
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Harga</th>
                                <th width="150">Jumlah</th>
                                <th>Subtotal</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($carts as $cart)
                                @php 
                                    $subtotal = $cart->product->price * $cart->quantity;
                                    $total += $subtotal;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if($cart->product->image)
                                                <img src="{{ Storage::url($cart->product->image) }}" alt="{{ $cart->product->name }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $cart->product->name }}</h6>
                                                <small class="text-muted">Stok: {{ $cart->product->stock }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($cart->product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" class="form-control form-control-sm text-center w-50" value="{{ $cart->quantity }}" min="1" max="{{ $cart->product->stock }}" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus produk ini dari keranjang?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end fs-5">Total Pembayaran:</td>
                                <td colspan="2" class="fs-5 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('produk.user') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Lanjut Belanja
                </a>
                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" onclick="return confirm('Apakah Anda yakin ingin melakukan checkout sekarang?')">
                        Checkout Sekarang <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
