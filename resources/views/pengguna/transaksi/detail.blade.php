@extends('pengguna.layouts.user')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="mb-0 fw-bold"><i class="fas fa-receipt text-danger me-2"></i>Detail Transaksi</h2>
        <a href="{{ route('transaksi.invoice', $order->id) }}" target="_blank" class="btn btn-danger shadow-sm"><i class="fas fa-print me-1"></i> Cetak Invoice</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h6 class="fw-bold fs-5">Nomor Pesanan: #{{ $order->id }}</h6>
            <p class="mb-1 text-muted">Status: <span class="badge bg-{{ ['pending' => 'warning', 'processing' => 'info', 'shipped' => 'primary', 'completed' => 'success', 'cancelled' => 'danger'][$order->status] ?? 'secondary' }} text-uppercase">{{ $order->status }}</span></p>
            <p class="mb-0 text-muted">Tanggal Pesan: {{ $order->created_at->format('d-m-Y H:i') }}</p>
            <hr>
            <h5 class="fw-bold mb-3">Daftar Produk</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Produk</th>
                            <th>Harga Satuan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end pe-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="ps-3"><span class="fw-semibold">{{ $item->product->name ?? 'Produk Dihapus' }}</span></td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-center">x{{ $item->quantity }}</td>
                            <td class="text-end pe-3 fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top-2 border-danger">
                        <tr>
                            <th colspan="3" class="text-end fs-5">Total Pembayaran</th>
                            <th class="text-end pe-3 fs-5 text-danger">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if(session('success'))
                <div class="alert alert-success m-3"><i class="fas fa-check-circle me-1"></i> {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger m-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <hr>
            
            @if($order->payment)
                <h5 class="mt-4 fw-bold">Status Bukti: <span class="badge bg-{{ $order->payment->status == 'confirmed' ? 'success' : ($order->payment->status == 'rejected' ? 'danger' : 'warning') }} text-uppercase ms-2">{{ $order->payment->status }}</span></h5>
                <div class="mt-3 bg-light p-4 rounded-3 border-start border-4 border-danger">
                    <p class="mb-1"><i class="fas fa-credit-card me-2 text-muted"></i><strong>Metode Pembayaran:</strong> {{ mb_strtoupper($order->payment->payment_method) }}</p>
                    <p class="mb-2"><i class="fas fa-image me-2 text-muted"></i><strong>Bukti Transfer Terkirim:</strong></p>
                    <a href="{{ asset('storage/' . $order->payment->proof_image) }}" target="_blank">
                        <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm border" style="max-height: 250px;">
                    </a>
                </div>
            @elseif($order->status == 'pending')
                <div class="bg-danger bg-opacity-10 p-4 rounded-4 mt-4 border border-danger border-opacity-25">
                    <h5 class="text-danger fw-bold mb-3"><i class="fas fa-wallet me-2"></i>Konfirmasi Pembayaran Anda</h5>
                    <div class="alert bg-white border-0 shadow-sm rounded-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-danger fs-3 me-3"></i> 
                            <div>
                                <span class="d-block mb-1">Silakan transfer senilai <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> ke rekening:</span>
                                <span class="fs-5 fw-bold font-monospace bg-light px-2 py-1 rounded border">BCA 1234567890 a/n Jati Murni</span>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('transaksi.bayar', $order->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-3 rounded-3 shadow-sm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label fw-bold">Asal Bank / E-Wallet Anda</label>
                                <input type="text" class="form-control" id="payment_method" name="payment_method" placeholder="Contoh: BCA Achmad, Mandiri Budi" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="proof_image" class="form-label fw-bold">Unggah Bukti Transaksi</label>
                                <input type="file" class="form-control" id="proof_image" name="proof_image" accept="image/jpeg,image/png,image/jpg" required>
                                <small class="text-muted"><i class="fas fa-file-image me-1"></i>Maksimal 2MB (Hanya JPG/PNG)</small>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger fw-bold w-100 py-2 mt-2">
                            <i class="fas fa-upload me-2"></i> Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>
            @endif

            {{-- ========== REVIEW & RATING SECTION ========== --}}
            @if($order->status == 'completed')
            <hr class="mt-4">
            <h5 class="fw-bold mt-4 mb-3"><i class="fas fa-star text-warning me-2"></i>Beri Ulasan Produk</h5>
            
            @foreach($order->items as $item)
                @php
                    $existingReview = \App\Models\Review::where('user_id', auth()->id())
                        ->where('product_id', $item->product_id)
                        ->where('order_id', $order->id)
                        ->first();
                @endphp
                <div class="bg-light p-3 rounded-3 mb-3 border">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded" style="width:50px; height:50px; object-fit:cover;">
                        @endif
                        <div>
                            <span class="fw-bold">{{ $item->product->name ?? 'Produk Dihapus' }}</span>
                            <span class="text-muted d-block small">x{{ $item->quantity }} — Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($existingReview)
                        {{-- Tampilkan review yang sudah ada --}}
                        <div class="bg-white p-3 rounded-3 border-start border-3 border-warning">
                            <div class="mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $existingReview->rating ? '' : '-o' }} text-warning"></i>
                                @endfor
                                <span class="ms-2 text-muted small">({{ $existingReview->rating }}/5)</span>
                            </div>
                            @if($existingReview->comment)
                                <p class="mb-0 mt-1 text-secondary"><em>"{{ $existingReview->comment }}"</em></p>
                            @endif
                            <small class="text-muted d-block mt-1">Direview pada {{ $existingReview->created_at->format('d M Y, H:i') }}</small>
                        </div>
                    @else
                        {{-- Form review --}}
                        <form action="{{ route('review.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            
                            <div class="mb-2">
                                <label class="form-label fw-semibold small mb-1">Rating Bintang:</label>
                                <div class="star-rating-input" data-product="{{ $item->product_id }}">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label style="cursor:pointer; font-size: 1.5rem;">
                                            <input type="radio" name="rating" value="{{ $i }}" class="d-none" required>
                                            <i class="far fa-star text-warning star-icon" data-value="{{ $i }}"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-2">
                                <textarea name="comment" class="form-control form-control-sm" rows="2" placeholder="Tulis kesan & pengalaman Anda... (opsional)" maxlength="1000"></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning btn-sm fw-bold"><i class="fas fa-paper-plane me-1"></i> Kirim Ulasan</button>
                        </form>
                    @endif
                </div>
            @endforeach
            @endif

        </div>
    </div>
    <div class="mt-4 mb-5 pb-5">
        <a href="{{ route('produk.user') }}" class="btn btn-outline-dark px-4"><i class="fas fa-arrow-left me-1"></i>Kembali Belanja</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.star-rating-input').forEach(function(container) {
        const stars = container.querySelectorAll('.star-icon');
        stars.forEach(function(star) {
            star.addEventListener('click', function() {
                const val = parseInt(this.getAttribute('data-value'));
                stars.forEach(function(s, idx) {
                    if (idx < val) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });
            star.addEventListener('mouseover', function() {
                const val = parseInt(this.getAttribute('data-value'));
                stars.forEach(function(s, idx) {
                    if (idx < val) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });
        });
        container.addEventListener('mouseleave', function() {
            const checked = container.querySelector('input[name="rating"]:checked');
            const checkedVal = checked ? parseInt(checked.value) : 0;
            stars.forEach(function(s, idx) {
                if (idx < checkedVal) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                }
            });
        });
    });
});
</script>
@endpush
