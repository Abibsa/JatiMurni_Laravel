@extends('admin.layouts.dashboard')

@section('title', 'Manajemen Pesanan - Meubel Jati Murni')

@section('page-title', 'Manajemen Pesanan')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-white border-bottom-0 py-3">
        <h5 class="mb-0 fw-bold">Daftar Semua Pesanan</h5>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No. Order</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Pesan</th>
                        <th>Total Nilai</th>
                        <th>Status Order</th>
                        <th>Status Pembayaran</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? 'User Terhapus' }}</td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ][$order->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }} text-uppercase">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                @if($order->payment)
                                    @php
                                        $payClass = [
                                            'pending' => 'warning',
                                            'confirmed' => 'success',
                                            'rejected' => 'danger'
                                        ][$order->payment->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $payClass }} text-uppercase">
                                        {{ $order->payment->status }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Belum Upload Bukti</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-dark">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data pesanan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
