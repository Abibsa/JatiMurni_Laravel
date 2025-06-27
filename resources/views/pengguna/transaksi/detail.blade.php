@extends('pengguna.layouts.user')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detail Transaksi</h2>
    <div class="card">
        <div class="card-body">
            <h5>Produk: {{ $order->product->name }}</h5>
            <p>Harga: Rp {{ number_format($order->product->price,0,',','.') }}</p>
            <p>Jumlah: {{ $order->qty }}</p>
            <p>Status: {{ ucfirst($order->status) }}</p>
            <p>Tanggal Pesan: {{ $order->created_at->format('d-m-Y H:i') }}</p>
        </div>
    </div>
    <a href="{{ route('produk.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Produk</a>
</div>
@endsection
