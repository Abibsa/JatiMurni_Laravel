@extends('pengguna.layouts.user')

@section('title', 'Pesanan')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Pesanan</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Status</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->product->name ?? '-' }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->payment->status ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 