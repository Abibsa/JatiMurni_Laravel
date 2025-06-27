@extends('pengguna.layouts.user')

@section('title', 'Beranda Pengguna')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="hero-section position-relative overflow-hidden rounded-4 mb-4 shadow" style="background: linear-gradient(90deg, #18191a 60%, #dc3545 100%); min-height: 320px;">
        <div class="row align-items-center h-100">
            <div class="col-md-7 p-5 text-white">
                <h1 class="display-4 fw-bold mb-3">Temukan <span class="text-danger">Meubel Jati</span> Impianmu</h1>
                <p class="lead mb-4">Kualitas terbaik, desain elegan, dan harga terjangkau hanya di Meubel Jati Murni. Percantik rumah Anda dengan produk pilihan kami!</p>
                <a href="#" class="btn btn-lg btn-light text-danger fw-bold px-4 shadow-sm me-2"><i class="fas fa-shopping-bag me-2"></i>Belanja Sekarang</a>
                <a href="#" class="btn btn-lg btn-outline-light fw-bold px-4 shadow-sm"><i class="fas fa-tags me-2"></i>Lihat Promo</a>
            </div>
            <div class="col-md-5 d-none d-md-block">
                <img src="{{ asset('images/default-product.jpg') }}" alt="Produk Unggulan" class="img-fluid rounded-4 shadow-lg float-end" style="max-height: 360px; object-fit: cover;">
            </div>
        </div>
        <i class="fas fa-leaf position-absolute opacity-25" style="font-size: 8rem; right: 2rem; bottom: -2rem; color: #fff;"></i>
    </div>
    <!-- Kenapa Harus Beli di Kami? -->
    <h4 class="fw-bold mb-3 text-dark">Kenapa Harus Beli di Kami?</h4>
    <div class="row g-4 mb-4 justify-content-center">
        <div class="col-12 col-md-3">
            <div class="modern-card feature-card glass-card border-0 text-center py-4">
                <div class="icon-circle bg-gradient-red mb-3 mx-auto"><i class="fas fa-certificate fa-lg text-white"></i></div>
                <h6 class="fw-bold mb-2 fs-5">Produk Bergaransi</h6>
                <p class="mb-0 small text-muted">Belanja tanpa khawatir! Semua produk kami bergaransi resmi dan didukung layanan purna jual yang responsif.</p>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="modern-card feature-card glass-card border-0 text-center py-4">
                <div class="icon-circle bg-gradient-green mb-3 mx-auto"><i class="fas fa-tree fa-lg text-white"></i></div>
                <h6 class="fw-bold mb-2 fs-5">Bahan Kayu Jati Asli</h6>
                <p class="mb-0 small text-muted">Kami hanya menggunakan kayu jati pilihan, kuat, tahan lama, dan ramah lingkungan untuk setiap produk.</p>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="modern-card feature-card glass-card border-0 text-center py-4">
                <div class="icon-circle bg-gradient-blue mb-3 mx-auto"><i class="fas fa-shipping-fast fa-lg text-white"></i></div>
                <h6 class="fw-bold mb-2 fs-5">Pengiriman Aman & Cepat</h6>
                <p class="mb-0 small text-muted">Pengemasan ekstra aman dan pengiriman cepat ke seluruh Indonesia, sampai tujuan tanpa khawatir.</p>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="modern-card feature-card glass-card border-0 text-center py-4">
                <div class="icon-circle bg-gradient-orange mb-3 mx-auto"><i class="fas fa-tags fa-lg text-white"></i></div>
                <h6 class="fw-bold mb-2 fs-5">Harga Kompetitif</h6>
                <p class="mb-0 small text-muted">Dapatkan harga terbaik langsung dari pengrajin, tanpa perantara, lebih hemat dan transparan.</p>
            </div>
        </div>
    </div>
    <style>
        .glass-card {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(8px);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10), 0 1.5px 6px rgba(220,53,69,0.08);
            transition: transform 0.25s, box-shadow 0.25s, border-top 0.25s;
        }
        .modern-card:hover {
            transform: translateY(-10px) scale(1.04);
            box-shadow: 0 16px 48px rgba(220,53,69,0.18), 0 2px 8px rgba(0,0,0,0.10);
            border-top: 4px solid #dc3545 !important;
            z-index: 2;
        }
        .icon-circle {
            width: 54px; height: 54px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        }
        .bg-gradient-red {
            background: linear-gradient(135deg, #dc3545 60%, #ff6a6a 100%);
        }
        .bg-gradient-green {
            background: linear-gradient(135deg, #198754 60%, #43e97b 100%);
        }
        .bg-gradient-blue {
            background: linear-gradient(135deg, #0d6efd 60%, #5ee7df 100%);
        }
        .bg-gradient-orange {
            background: linear-gradient(135deg, #fd7e14 60%, #fbb040 100%);
        }
    </style>
    <!-- Q & A Section -->
    <div class="container mb-5">
        <div class="d-flex align-items-center mb-3">
            <div style="width:6px; height:32px; background:linear-gradient(180deg,#dc3545 60%,#ff6a6a 100%); border-radius:6px; margin-right:12px;"></div>
            <h4 class="fw-bold text-dark mb-0"><i class="fas fa-question-circle text-danger me-2"></i>Q & A (Tanya Jawab)</h4>
        </div>
        <div class="accordion modern-faq shadow-lg rounded-4" id="faqAccordion">
            <div class="accordion-item mb-3 border-0 rounded-4 overflow-hidden">
                <h2 class="accordion-header" id="faq1">
                    <button class="accordion-button collapsed fw-bold text-dark bg-white align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#answer1" aria-expanded="false" aria-controls="answer1" style="font-size:1.1rem;">
                        <span class="icon-circle bg-gradient-red me-3"><i class="fas fa-question text-white"></i></span> Apakah semua produk benar-benar dari kayu jati asli?
                    </button>
                </h2>
                <div id="answer1" class="accordion-collapse collapse" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted bg-faq-answer">
                        <span class="icon-circle bg-gradient-green me-2"><i class="fas fa-check text-white"></i></span> Ya, semua produk kami dibuat dari kayu jati asli pilihan, tanpa campuran kayu lain.
                    </div>
                </div>
            </div>
            <div class="accordion-item mb-3 border-0 rounded-4 overflow-hidden">
                <h2 class="accordion-header" id="faq2">
                    <button class="accordion-button collapsed fw-bold text-dark bg-white align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#answer2" aria-expanded="false" aria-controls="answer2" style="font-size:1.1rem;">
                        <span class="icon-circle bg-gradient-red me-3"><i class="fas fa-question text-white"></i></span> Bagaimana cara klaim garansi produk?
                    </button>
                </h2>
                <div id="answer2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted bg-faq-answer">
                        <span class="icon-circle bg-gradient-green me-2"><i class="fas fa-check text-white"></i></span> Anda cukup menghubungi admin kami dengan bukti pembelian, dan tim kami akan membantu proses klaim garansi dengan mudah.
                    </div>
                </div>
            </div>
            <div class="accordion-item mb-3 border-0 rounded-4 overflow-hidden">
                <h2 class="accordion-header" id="faq3">
                    <button class="accordion-button collapsed fw-bold text-dark bg-white align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#answer3" aria-expanded="false" aria-controls="answer3" style="font-size:1.1rem;">
                        <span class="icon-circle bg-gradient-red me-3"><i class="fas fa-question text-white"></i></span> Apakah bisa custom desain atau ukuran produk?
                    </button>
                </h2>
                <div id="answer3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted bg-faq-answer">
                        <span class="icon-circle bg-gradient-green me-2"><i class="fas fa-check text-white"></i></span> Tentu! Kami menerima pesanan custom desain dan ukuran sesuai kebutuhan Anda. Silakan konsultasikan ke admin kami.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .modern-faq .accordion-button {
            border-radius: 1.2rem !important;
            box-shadow: none;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .modern-faq .accordion-button:focus {
            box-shadow: 0 0 0 2px #dc3545;
        }
        .modern-faq .accordion-item {
            background: rgba(255,255,255,0.85);
            box-shadow: 0 4px 16px rgba(220,53,69,0.07);
        }
        .bg-faq-answer {
            background: rgba(245,245,245,0.85);
            border-radius: 1rem;
            padding-left: 1.5rem !important;
            font-size: 1rem;
        }
        .modern-faq .accordion-body {
            transition: background 0.2s;
        }
    </style>
    <!-- Kenapa Memilih Kami -->
    <div class="row my-5 text-center">
        <div class="col-6 col-md-3">
            <i class="fas fa-tree fa-2x text-danger mb-2"></i>
            <div class="fw-bold">Kayu Jati Asli</div>
        </div>
        <div class="col-6 col-md-3">
            <i class="fas fa-shield-alt fa-2x text-danger mb-2"></i>
            <div class="fw-bold">Garansi Produk</div>
        </div>
        <div class="col-6 col-md-3">
            <i class="fas fa-truck fa-2x text-danger mb-2"></i>
            <div class="fw-bold">Pengiriman Nasional</div>
        </div>
        <div class="col-6 col-md-3">
            <i class="fas fa-tags fa-2x text-danger mb-2"></i>
            <div class="fw-bold">Harga Terbaik</div>
        </div>
    </div>
    <!-- Aksi Cepat -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <a href="#" class="btn btn-outline-danger btn-lg mx-2"><i class="fas fa-box-open me-2"></i>Lihat Semua Produk</a>
            <a href="#" class="btn btn-outline-dark btn-lg mx-2"><i class="fas fa-headset me-2"></i>Hubungi Admin</a>
        </div>
    </div>
</div>
@endsection 