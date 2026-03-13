@extends('admin.layouts.dashboard')

@section('title', 'Detail Pesanan #' . $order->id . ' - Meubel Jati Murni')

@section('page-title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="row">
    <!-- Informasi Utama Pesanan -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Rincian Produk</h5>
                <span class="badge bg-{{ ['pending' => 'warning', 'processing' => 'info', 'shipped' => 'primary', 'completed' => 'success', 'cancelled' => 'danger'][$order->status] ?? 'secondary' }} fs-6 text-uppercase">
                    {{ $order->status }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Harga Satuan</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <span class="fw-bold">{{ $item->product->name ?? 'Produk Terhapus' }}</span>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">x{{ $item->quantity }}</td>
                                    <td class="text-end fw-semibold pe-4">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end fs-5">Total Dibayar:</td>
                                <td class="text-end fs-5 text-danger pe-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Status Management Action -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-truck me-2 text-primary"></i>Ubah Status Pesanan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-select flex-grow-1">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu Bayar)</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing (Sedang Diproses)</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped (Dalam Pengiriman)</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                    </select>
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Simpan Status</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Informasi Pelanggan & Pembayaran -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-danger"></i>Info Pelanggan</h6>
            </div>
            <div class="card-body pt-0 text-muted">
                <p class="mb-1 fw-bold text-dark">{{ $order->user->name ?? 'Guest/Terhapus' }}</p>
                <p class="mb-1"><i class="fas fa-envelope fa-fw"></i> {{ $order->user->email ?? '-' }}</p>
                <p class="mb-1"><i class="fas fa-phone fa-fw"></i> {{ $order->user->phone ?? '-' }}</p>
                <hr>
                <h6 class="text-dark fw-bold mb-2">Alamat Pengiriman:</h6>
                <p class="mb-0" style="white-space: pre-wrap;">{{ $order->shipping_address ?? 'Tidak ada alamat' }}</p>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-wallet me-2 text-success"></i>Bukti Pembayaran</h6>
                @if($order->payment)
                    <span class="badge bg-{{ ['pending' => 'warning', 'confirmed' => 'success', 'rejected' => 'danger'][$order->payment->status] ?? 'secondary' }} text-uppercase">
                        {{ $order->payment->status }}
                    </span>
                @endif
            </div>
            <div class="card-body pt-0">
                @if($order->payment)
                    <div class="mb-3">
                        <span class="d-block text-muted small">Metode Bank:</span>
                        <span class="fw-bold">{{ mb_strtoupper($order->payment->payment_method) }}</span>
                    </div>
                    
                    <a href="{{ asset('storage/' . $order->payment->proof_image) }}" target="_blank" class="d-block mb-3 border rounded overflow-hidden">
                        <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Transfer" class="img-fluid w-100" style="max-height: 200px; object-fit: cover;">
                        <div class="bg-light text-center py-1 small text-muted">Klik untuk perbesar</div>
                    </a>

                    @if($order->payment->status == 'pending')
                    <div class="d-flex gap-2">
                         <form action="{{ route('admin.orders.confirm-payment', $order->payment->id) }}" method="POST" class="flex-fill">
                             @csrf @method('PUT')
                             <button type="submit" class="btn btn-success w-100 btn-sm"><i class="fas fa-check"></i> Konfirmasi</button>
                         </form>
                         <form action="{{ route('admin.orders.reject-payment', $order->payment->id) }}" method="POST" class="flex-fill">
                             @csrf @method('PUT')
                             <button type="submit" class="btn btn-danger w-100 btn-sm"><i class="fas fa-times"></i> Tolak</button>
                         </form>
                    </div>
                    @endif
                @else
                    <div class="alert alert-secondary text-center mb-0">
                        <i class="fas fa-box-open d-block fs-3 mb-2 text-muted"></i>
                        Pelanggan belum mengunggah bukti pembayaran via portal user.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
