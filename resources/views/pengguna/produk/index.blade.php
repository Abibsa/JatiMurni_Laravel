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
    <h2 class="mb-4 fw-bold text-dark">Daftar Produk</h2>
    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card card-product h-100 shadow-sm border-0">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}" class="card-img-top" alt="{{ $product->name }}" style="object-fit:cover; height:180px;">
                <div class="card-body d-flex flex-column">
                    <span class="badge-category mb-2">{{ $product->category->name ?? 'Tanpa Kategori' }}</span>
                    <h5 class="card-title mb-1 fw-bold text-dark">{{ $product->name }}</h5>
                    <div class="mb-2">
                        <span class="badge bg-danger fs-6">Rp {{ number_format($product->price,0,',','.') }}</span>
                    </div>
                    <p class="card-text flex-grow-1 text-secondary">{{ Str::limit($product->description, 60) }}</p>
                    <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }} mt-2 mb-2">{{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Habis' }}</span>
                    <a href="#" 
                       class="btn btn-detail btn-sm mt-auto"
                       data-bs-toggle="modal"
                       data-bs-target="#detailModal"
                       data-nama="{{ $product->name }}"
                       data-gambar="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                       data-kategori="{{ $product->category->name ?? 'Tanpa Kategori' }}"
                       data-harga="Rp {{ number_format($product->price,0,',','.') }}"
                       data-deskripsi="{{ $product->description }}"
                       data-stok="{{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Habis' }}"
                    >
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
        var nama = button.getAttribute('data-nama');
        var gambar = button.getAttribute('data-gambar');
        var kategori = button.getAttribute('data-kategori');
        var harga = button.getAttribute('data-harga');
        var deskripsi = button.getAttribute('data-deskripsi');
        var stok = button.getAttribute('data-stok');

        var modalContent = `
            <div class="row">
                <div class="col-md-5 mb-3 mb-md-0">
                    <img src="${gambar}" alt="${nama}" class="img-fluid rounded shadow-sm w-100">
                </div>
                <div class="col-md-7">
                    <h4 class="fw-bold mb-2">${nama}</h4>
                    <span class="badge-category mb-2">${kategori}</span>
                    <div class="mb-2 fw-bold text-danger fs-5">${harga}</div>
                    <div class="mb-3 text-secondary">${deskripsi}</div>
                    <span class="badge bg-${stok.includes('Stok') ? 'success' : 'danger'}">${stok}</span>
                </div>
            </div>
        `;
        document.getElementById('modalContent').innerHTML = modalContent;
    });
});
</script>
@endpush 