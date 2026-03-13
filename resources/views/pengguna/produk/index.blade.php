@extends('pengguna.layouts.user')

@section('title', 'Produk')

@push('styles')
<style>
    .card-product {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 18px;
        overflow: hidden;
    }
    .card-product:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 8px 32px rgba(44,62,80,0.15);
        border: 1.5px solid #dc3545;
    }
    .card-img-top {
        border-radius: 18px 18px 0 0;
        background: #f1f1f1;
        transition: filter 0.3s;
        box-shadow: 0 2px 8px rgba(44,62,80,0.08);
    }
    .card-product:hover .card-img-top {
        filter: brightness(0.92) saturate(1.2);
    }
    .badge-category {
        background: linear-gradient(90deg, #dc3545 60%, #ff7675 100%);
        color: #fff;
        font-size: 0.85em;
        border-radius: 8px;
        padding: 0.35em 0.8em;
        margin-bottom: 0.5em;
        display: inline-block;
        box-shadow: 0 2px 6px rgba(220,53,69,0.08);
    }
    .btn-detail {
        background: #18191a;
        color: #fff;
        border-radius: 8px;
        font-weight: 500;
        transition: background 0.2s;
        letter-spacing: 0.5px;
    }
    .btn-detail:hover {
        background: #dc3545;
        color: #fff;
    }
    @media (max-width: 575.98px) {
        .card-body {
            padding: 1rem 0.7rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="fw-bold text-dark mb-0">Daftar Produk</h2>
        
        <form action="{{ route('produk.user') }}" method="GET" class="d-flex gap-2">
            <select name="category" class="form-select form-select-sm border-secondary shadow-sm" style="max-width: 150px;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <div class="input-group input-group-sm shadow-sm">
                <input type="text" name="search" class="form-control border-secondary" placeholder="Cari produk..." value="{{ request('search') }}">
                <button class="btn btn-dark" type="submit"><i class="fas fa-search"></i></button>
            </div>
            @if(request('search') || request('category'))
                <a href="{{ route('produk.user') }}" class="btn btn-sm btn-outline-danger shadow-sm"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>
    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card card-product h-100 shadow-sm border-0">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}" class="card-img-top" alt="{{ $product->name }}" style="object-fit:cover; height:180px;">
                <div class="card-body d-flex flex-column">
                    <span class="badge-category mb-2">{{ $product->category->name ?? 'Tanpa Kategori' }}</span>
                    <h5 class="card-title mb-1 fw-bold text-dark">{{ $product->name }}</h5>
                    <div class="mb-1">
                        @php $avg = round($product->average_rating, 1); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa{{ $i <= round($avg) ? 's' : 'r' }} fa-star text-warning" style="font-size:0.85rem;"></i>
                        @endfor
                        <span class="text-muted small ms-1">({{ $product->review_count }})</span>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-danger fs-6">Rp {{ number_format($product->price,0,',','.') }}</span>
                    </div>
                    <p class="card-text flex-grow-1 text-secondary">{{ Str::limit($product->description, 60) }}</p>
                    <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }} mt-2 mb-2">{{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Habis' }}</span>
                    <a href="#" 
                       class="btn btn-detail btn-sm mt-auto"
                       data-bs-toggle="modal"
                       data-bs-target="#detailModal"
                       data-id="{{ $product->id }}"
                       data-nama="{{ $product->name }}"
                       data-gambar="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                       data-kategori="{{ $product->category->name ?? 'Tanpa Kategori' }}"
                       data-harga="Rp {{ number_format($product->price,0,',','.') }}"
                       data-deskripsi="{{ $product->description }}"
                       data-stok="{{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Habis' }}"
                       data-stock-value="{{ $product->stock }}"
                       data-gallery='@json($product->images->pluck("image_path"))'
                       data-avg-rating="{{ $avg }}"
                       data-review-count="{{ $product->review_count }}"
                       data-reviews='@json($product->reviews_data)'>
                       Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center shadow-sm">Belum ada produk.</div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Detail Produk -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Detail Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div id="modalContent">
          <!-- Konten detail produk akan diisi via JS -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var detailModal = document.getElementById('detailModal');
    detailModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-nama');
        var gambar = button.getAttribute('data-gambar');
        var kategori = button.getAttribute('data-kategori');
        var harga = button.getAttribute('data-harga');
        var deskripsi = button.getAttribute('data-deskripsi');
        var stokLabel = button.getAttribute('data-stok');
        var stockValue = parseInt(button.getAttribute('data-stock-value'));
        var avgRating = parseFloat(button.getAttribute('data-avg-rating')) || 0;
        var reviewCount = parseInt(button.getAttribute('data-review-count')) || 0;
        
        var gallery = [];
        try { gallery = JSON.parse(button.getAttribute('data-gallery') || '[]'); } catch(e) {}
        var reviews = [];
        try { reviews = JSON.parse(button.getAttribute('data-reviews') || '[]'); } catch(e) {}

        // Gallery thumbnails
        var galleryHtml = '';
        if (gallery.length > 0) {
            galleryHtml = '<div class="d-flex flex-wrap gap-2 mt-2">';
            gallery.forEach(function(path) {
                var url = '{{ asset("storage") }}/' + path;
                galleryHtml += '<img src="' + url + '" class="rounded border shadow-sm" style="width:55px;height:55px;object-fit:cover;cursor:pointer;" onclick="document.getElementById(\'modalMainImg\').src=this.src">';
            });
            galleryHtml += '</div>';
        }

        // Rating stars
        var starsHtml = '';
        for (var i = 1; i <= 5; i++) {
            starsHtml += '<i class="fa' + (i <= Math.round(avgRating) ? 's' : 'r') + ' fa-star text-warning"></i>';
        }
        starsHtml += ' <span class="text-muted small">(' + avgRating.toFixed(1) + '/5 dari ' + reviewCount + ' ulasan)</span>';

        // Reviews list
        var reviewsHtml = '';
        if (reviews.length > 0) {
            reviewsHtml = '<hr><h6 class="fw-bold mb-2"><i class="fas fa-comments text-warning me-1"></i>Ulasan Pelanggan</h6>';
            reviews.forEach(function(r) {
                var rs = '';
                for (var j = 1; j <= 5; j++) {
                    rs += '<i class="fa' + (j <= r.rating ? 's' : 'r') + ' fa-star text-warning" style="font-size:0.8rem;"></i>';
                }
                reviewsHtml += '<div class="bg-light p-2 rounded mb-2 border-start border-3 border-warning">' +
                    '<div class="d-flex justify-content-between"><strong class="small">' + r.user + '</strong><span class="text-muted" style="font-size:0.75rem;">' + r.date + '</span></div>' +
                    '<div>' + rs + '</div>' +
                    (r.comment ? '<p class="mb-0 small text-secondary mt-1"><em>"' + r.comment + '"</em></p>' : '') +
                    '</div>';
            });
        }

        var cartForm = '';
        if (stockValue > 0) {
            @auth
                cartForm = `
                    <form action="{{ route('cart.store') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="product_id" value="${id}">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <label for="quantity" class="col-form-label">Jumlah:</label>
                            </div>
                            <div class="col-auto">
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="${stockValue}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang</button>
                            </div>
                        </div>
                    </form>
                `;
            @else
                cartForm = `
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary"><i class="fas fa-sign-in-alt me-1"></i> Login untuk Membeli</a>
                    </div>
                `;
            @endauth
        }

        var modalContent = `
            <div class="row">
                <div class="col-md-5 mb-3 mb-md-0">
                    <img id="modalMainImg" src="${gambar}" alt="${nama}" class="img-fluid rounded shadow-sm w-100">
                    ${galleryHtml}
                </div>
                <div class="col-md-7">
                    <h4 class="fw-bold mb-2">${nama}</h4>
                    <span class="badge-category mb-2">${kategori}</span>
                    <div class="mb-1">${starsHtml}</div>
                    <div class="mb-2 fw-bold text-danger fs-5">${harga}</div>
                    <div class="mb-3 text-secondary">${deskripsi}</div>
                    <span class="badge bg-${stokLabel.includes('Stok') ? 'success' : 'danger'}">${stokLabel}</span>
                    
                    ${cartForm}
                    ${reviewsHtml}
                </div>
            </div>
        `;
        document.getElementById('modalContent').innerHTML = modalContent;
    });
});
</script>
@endpush 