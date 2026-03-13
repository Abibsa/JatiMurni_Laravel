@extends('admin.layouts.dashboard')

@section('title', 'Produk')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-12">
            <div class="bg-dark p-4 rounded-4 d-flex align-items-center justify-content-between shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-couch fa-2x text-white text-shadow"></i>
                    <div>
                        <h2 class="mb-0 text-white fw-bold display-6 text-shadow">Manajemen Produk</h2>
                        <p class="text-white mb-0 text-shadow">Kelola produk Meubel Jati Murni dengan mudah dan cepat</p>
                    </div>
                </div>
                <button class="btn btn-danger btn-lg shadow" data-bs-toggle="modal" data-bs-target="#tambahProdukModal">
                    <i class="fas fa-plus me-2"></i>Tambah Produk
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-lg border-0 product-card-modern position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 m-2">
                    <span class="badge bg-danger bg-opacity-75 px-3 py-2 rounded-pill shadow-sm">{{ $product->category->name ?? '-' }}</span>
                </div>
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }} px-3 py-2 rounded-pill shadow-sm">{{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Habis' }}</span>
                </div>
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('public/images/default-product.jpg') }}" class="card-img-top product-img-modern" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-1 fw-bold text-dark">{{ $product->name }}</h5>
                    <div class="mb-2 text-muted small">Material: <span class="fw-semibold">{{ $product->material }}</span></div>
                    <div class="mb-2 text-muted small">Dimensi: <span class="fw-semibold">{{ $product->dimensions }}</span></div>
                    <div class="mb-2 fw-bold text-danger fs-5">Rp {{ number_format($product->price,0,',','.') }}</div>
                    <p class="card-text flex-grow-1 text-secondary">{{ Str::limit($product->description, 60) }}</p>
                    <div class="d-flex gap-2 mt-2">
                        <button class="btn btn-outline-danger btn-sm flex-fill shadow" data-bs-toggle="modal" data-bs-target="#editProdukModal{{ $product->id }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form action="{{ route('produk.destroy', $product->id) }}" method="POST" class="d-inline flex-fill">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm w-100 shadow"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                    <button class="btn btn-detail btn-sm mt-auto" data-bs-toggle="modal" data-bs-target="#detailProdukModal{{ $product->id }}">Lihat Detail</button>
                </div>
            </div>
        </div>

        <!-- Modal Edit Produk -->
        <div class="modal fade custom-modal-blur" id="editProdukModal{{ $product->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-md animate-modal">
                <div class="modal-content">
                    <form action="{{ route('produk.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-danger text-white align-items-center flex-column text-center border-0 rounded-top-4">
                            <span class="mb-2"><i class="fas fa-edit fa-2x"></i></span>
                            <h4 class="modal-title fw-bold w-100">Edit Produk</h4>
                            <small class="text-white-50">Perbarui data produk di bawah ini</small>
                            <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-tag"></i></span>
                                <input type="text" name="name" class="form-control form-control-modern border-start-0" value="{{ $product->name }}" placeholder="Nama Produk" required>
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-list"></i></span>
                                <select name="category_id" class="form-select form-control-modern border-start-0" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-money-bill-wave"></i></span>
                                <input type="number" name="price" class="form-control form-control-modern border-start-0" value="{{ $product->price }}" placeholder="Harga" required>
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-box"></i></span>
                                <input type="number" name="stock" class="form-control form-control-modern border-start-0" value="{{ $product->stock }}" placeholder="Stok" required>
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-cube"></i></span>
                                <input type="text" name="material" class="form-control form-control-modern border-start-0" value="{{ $product->material }}" placeholder="Material" required>
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-ruler-combined"></i></span>
                                <input type="text" name="dimensions" class="form-control form-control-modern border-start-0" value="{{ $product->dimensions }}" placeholder="Dimensi" required>
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-image"></i></span>
                                <input type="file" name="image" class="form-control form-control-modern border-start-0">
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-align-left"></i></span>
                                <textarea name="description" class="form-control form-control-modern border-start-0" rows="2" placeholder="Deskripsi">{{ $product->description }}</textarea>
                            </div>
                            {{-- Galeri Foto --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-images text-danger me-1"></i> Foto Galeri Tambahan (Maks 5)</label>
                                <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*">
                                <small class="text-muted">Unggah beberapa foto sekaligus untuk galeri produk.</small>
                            </div>
                            @if($product->images->count() > 0)
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Galeri Saat Ini:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($product->images as $galleryImg)
                                        <div class="position-relative" style="width:70px; height:70px;">
                                            <img src="{{ asset('storage/' . $galleryImg->image_path) }}" class="rounded border" style="width:70px; height:70px; object-fit:cover;">
                                            <label class="position-absolute top-0 end-0" title="Centang untuk hapus">
                                                <input type="checkbox" name="delete_gallery[]" value="{{ $galleryImg->id }}" class="form-check-input" style="width:18px;height:18px;">
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">Centang foto yang ingin dihapus.</small>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer flex-column border-0 gap-2 p-4">
                            <button type="submit" class="btn btn-danger btn-lg btn-modal-action w-100">Simpan</button>
                            <button type="button" class="btn btn-light btn-lg btn-modal-action w-100" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Detail Produk -->
        <div class="modal fade custom-modal-blur" id="detailProdukModal{{ $product->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg animate-modal">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white align-items-center flex-column text-center border-0 rounded-top-4">
                        <span class="mb-2"><i class="fas fa-couch fa-2x"></i></span>
                        <h4 class="modal-title fw-bold w-100">{{ $product->name }}</h4>
                        <small class="text-white-50">Detail Produk</small>
                        <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-5 text-center mb-3 mb-md-0">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('public/images/default-product.jpg') }}" class="img-fluid rounded-4 shadow" style="max-height:300px;object-fit:cover;">
                                @if($product->images->count() > 0)
                                <div class="d-flex flex-wrap gap-2 mt-3 justify-content-center">
                                    @foreach($product->images as $galleryImg)
                                        <img src="{{ asset('storage/' . $galleryImg->image_path) }}" class="rounded border shadow-sm" style="width:60px; height:60px; object-fit:cover; cursor:pointer;" onclick="this.closest('.col-md-5').querySelector('img.img-fluid').src = this.src">
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="col-md-7">
                                <h5 class="fw-bold mb-2 text-danger">Rp {{ number_format($product->price,0,',','.') }}</h5>
                                <p class="mb-2"><span class="fw-semibold">Kategori:</span> {{ $product->category->name ?? '-' }}</p>
                                <p class="mb-2"><span class="fw-semibold">Stok:</span> {{ $product->stock > 0 ? $product->stock : 'Habis' }}</p>
                                <p class="mb-2"><span class="fw-semibold">Material:</span> {{ $product->material }}</p>
                                <p class="mb-2"><span class="fw-semibold">Dimensi:</span> {{ $product->dimensions }}</p>
                                <p class="mb-2"><span class="fw-semibold">Deskripsi:</span><br>{{ $product->description }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer flex-column border-0 gap-2 p-4">
                        <button type="button" class="btn btn-light btn-lg btn-modal-action w-100" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center shadow-sm">Belum ada produk.</div>
        </div>
        @endforelse
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade custom-modal-blur" id="tambahProdukModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md animate-modal">
            <div class="modal-content">
                <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-danger text-white align-items-center flex-column text-center border-0 rounded-top-4">
                        <span class="mb-2"><i class="fas fa-couch fa-2x"></i></span>
                        <h4 class="modal-title fw-bold w-100">Tambah Produk</h4>
                        <small class="text-white-50">Isi data produk baru di bawah ini</small>
                        <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-tag"></i></span>
                            <input type="text" name="name" class="form-control form-control-modern border-start-0" placeholder="Nama Produk" required>
                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-list"></i></span>
                            <select name="category_id" class="form-select form-control-modern border-start-0" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-money-bill-wave"></i></span>
                            <input type="number" name="price" class="form-control form-control-modern border-start-0" placeholder="Harga" required>
                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-box"></i></span>
                            <input type="number" name="stock" class="form-control form-control-modern border-start-0" placeholder="Stok" required>
                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-cube"></i></span>
                            <input type="text" name="material" class="form-control form-control-modern border-start-0" placeholder="Material" required>
                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-ruler-combined"></i></span>
                            <input type="text" name="dimensions" class="form-control form-control-modern border-start-0" placeholder="Dimensi" required>
                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-image"></i></span>
                            <input type="file" name="image" class="form-control form-control-modern border-start-0">
                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-align-left"></i></span>
                            <textarea name="description" class="form-control form-control-modern border-start-0" rows="2" placeholder="Deskripsi"></textarea>
                        </div>
                        {{-- Galeri Foto --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-images text-danger me-1"></i> Foto Galeri Tambahan (Maks 5)</label>
                            <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Unggah beberapa foto sekaligus untuk galeri produk.</small>
                        </div>
                    </div>
                    <div class="modal-footer flex-column border-0 gap-2 p-4">
                        <button type="submit" class="btn btn-danger btn-lg btn-modal-action w-100">Simpan</button>
                        <button type="button" class="btn btn-light btn-lg btn-modal-action w-100" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Produk (custom, satu modal global) -->
    <div class="modal fade custom-modal-blur" id="hapusProdukModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm animate-modal">
            <div class="modal-content text-center p-0" id="hapusProdukModalContent">
                <div class="modal-header bg-danger text-white flex-column align-items-center border-0 rounded-top-4 p-4" style="background: linear-gradient(90deg, #dc3545 0%, #b71c1c 100%) !important;">
                    <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                    <h5 class="fw-bold mb-1 w-100">Konfirmasi Hapus Produk</h5>
                    <small class="text-white-50 mb-0">Tindakan ini tidak dapat dibatalkan</small>
                </div>
                <div class="p-4">
                    <p class="mb-1 text-secondary">Apakah Anda yakin ingin menghapus produk berikut?</p>
                    <div class="fw-bold text-danger mb-3 fs-5" id="hapusNamaProduk">Nama Produk</div>
                    <form id="hapusProdukForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light btn-lg btn-modal-action w-50" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger btn-lg btn-modal-action w-50">Ya, Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script untuk menampilkan modal hapus custom dengan nama produk
    document.querySelectorAll('form[action*="produk.destroy"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('hapusProdukModal'));
            document.getElementById('hapusProdukForm').action = this.action;
            // Ambil nama produk dari card
            const card = this.closest('.card');
            const nama = card ? card.querySelector('.card-title')?.textContent?.trim() : '';
            document.getElementById('hapusNamaProduk').textContent = nama || 'Produk';
            modal.show();
        });
    });
</script>

@push('styles')
<style>
    .product-card-modern {
        transition: box-shadow 0.2s, transform 0.2s;
        border-radius: 18px;
        background: #fff;
        border: 2px solid #fff;
    }
    .product-card-modern:hover {
        box-shadow: 0 12px 36px rgba(220,53,69,0.12), 0 2px 8px rgba(0,0,0,0.08);
        transform: translateY(-6px) scale(1.04);
        border-color: #dc3545;
    }
    .product-img-modern {
        object-fit: cover;
        height: 200px;
        border-radius: 18px 18px 0 0;
        background: #f8f9fa;
        transition: filter 0.3s, transform 0.3s;
    }
    .product-card-modern:hover .product-img-modern {
        filter: brightness(0.95) saturate(1.2);
        transform: scale(1.03);
    }
    .custom-modal-blur .modal-dialog {
        backdrop-filter: blur(6px);
        background: rgba(24,25,26,0.08);
    }
    .animate-modal {
        animation: modalPop 0.25s cubic-bezier(.4,2,.6,1) both;
    }
    @keyframes modalPop {
        0% { transform: scale(0.85) translateY(40px); opacity: 0; }
        100% { transform: scale(1) translateY(0); opacity: 1; }
    }
    .modal-content {
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(220,53,69,0.10), 0 2px 8px rgba(0,0,0,0.08);
    }
    .modal-header.bg-danger {
        background: linear-gradient(90deg, #dc3545 0%, #b71c1c 100%) !important;
        color: #fff;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
    }
    .btn-modal-action {
        font-size: 1.1em;
        font-weight: 600;
        padding: 0.6em 2em;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(220,53,69,0.08);
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    }
    .btn-modal-action.btn-danger:hover, .btn-modal-action.btn-danger:focus {
        background: #b71c1c;
        color: #fff;
    }
    .btn-modal-action.btn-light:hover, .btn-modal-action.btn-light:focus {
        background: #f8d7da;
        color: #b71c1c;
    }
    .btn-close-white {
        filter: invert(1);
    }
    .form-control-modern, .form-select.form-control-modern {
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        box-shadow: 0 2px 8px rgba(220,53,69,0.04);
        transition: border-color 0.2s, box-shadow 0.2s;
        font-size: 1.08em;
    }
    .form-control-modern:focus, .form-select.form-control-modern:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 2px rgba(220,53,69,0.15);
    }
    .input-group-text {
        background: #fff;
        border-radius: 12px 0 0 12px;
        border-right: 0;
        color: #dc3545;
        font-size: 1.1em;
    }
    .input-group .form-control-modern {
        border-left: 0;
    }
    .text-shadow {
        text-shadow: 0 2px 8px rgba(0,0,0,0.25), 0 1px 2px rgba(0,0,0,0.18);
    }
    .bg-dark {
        background: #18191a !important;
    }
    #hapusProdukModal .modal-content {
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(220,53,69,0.15), 0 2px 8px rgba(0,0,0,0.10);
        background: #fff;
        overflow: hidden;
        animation: modalPop 0.25s cubic-bezier(.4,2,.6,1) both;
    }
    #hapusProdukModal .modal-header.bg-danger {
        background: linear-gradient(90deg, #dc3545 0%, #b71c1c 100%) !important;
        color: #fff;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
        box-shadow: 0 4px 16px rgba(220,53,69,0.10);
    }
    #hapusProdukModal .btn-danger {
        font-weight: 600;
        font-size: 1.1em;
        border-radius: 10px;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    }
    #hapusProdukModal .btn-danger:hover, #hapusProdukModal .btn-danger:focus {
        background: #b71c1c;
        color: #fff;
        box-shadow: 0 2px 8px rgba(220,53,69,0.15);
    }
    #hapusProdukModal .btn-light {
        font-weight: 600;
        font-size: 1.1em;
        border-radius: 10px;
        background: #f8f9fa;
        color: #b71c1c;
        border: 1px solid #e0e0e0;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    }
    #hapusProdukModal .btn-light:hover, #hapusProdukModal .btn-light:focus {
        background: #f1f1f1;
        color: #dc3545;
        box-shadow: 0 2px 8px rgba(220,53,69,0.10);
    }
    .btn-detail {
        background: linear-gradient(90deg, #dc3545 0%, #b71c1c 100%);
        color: #fff;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(220,53,69,0.10);
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        border: none;
    }
    .btn-detail:hover, .btn-detail:focus {
        background: #b71c1c;
        color: #fff;
        box-shadow: 0 4px 16px rgba(220,53,69,0.15);
    }
</style>
@endpush
@endsection 