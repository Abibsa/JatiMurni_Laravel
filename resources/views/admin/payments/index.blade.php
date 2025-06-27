@extends('admin.layouts.dashboard')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Manajemen Pembayaran</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-header card-header-modern d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Pembayaran</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPaymentModal">
                        <i class="fas fa-plus"></i> Tambah Pembayaran
                    </button>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-modern"><i class="fas fa-check-circle"></i> {{ session('success') }} <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button></div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-modern"><i class="fas fa-exclamation-triangle"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-modern"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }} <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button></div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>ID Pembayaran</th>
                                    <th>Tanggal</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Metode</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_code ?? '-' }}</td>
                                        <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                        <td>{{ $payment->customer_name ?? '-' }}</td>
                                        <td>{{ $payment->payment_method }}</td>
                                        <td>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-status bg-{{ $payment->status === 'confirmed' ? 'success' : ($payment->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ $payment->status === 'confirmed' ? 'Berhasil' : ($payment->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($payment->proof_image)
                                            <button type="button" class="btn btn-sm btn-info btn-action" data-bs-toggle="modal" data-bs-target="#showPaymentModal{{ $payment->id }}">
                                                    <i class="fas fa-image"></i> Lihat
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#showPaymentModal{{ $payment->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#editPaymentModal{{ $payment->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('pembayaran.destroy', $payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Payment Modal -->
<div class="modal fade custom-modal-blur" id="createPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md animate-modal">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white align-items-center flex-column text-center border-0 rounded-top-4">
                <span class="mb-2"><i class="fas fa-wallet fa-2x"></i></span>
                <h4 class="modal-title fw-bold w-100">Tambah Pembayaran Baru</h4>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('pembayaran.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3 input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control form-control-modern border-start-0 @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" placeholder="Nama Pelanggan" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-money-bill-wave"></i></span>
                        <select class="form-select form-control-modern border-start-0 @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Transfer Bank" {{ old('payment_method') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="E-Wallet" {{ old('payment_method') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-calendar"></i></span>
                        <input type="date" class="form-control form-control-modern border-start-0 @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" value="{{ old('payment_date') }}" required>
                        @error('payment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-coins"></i></span>
                        <input type="number" step="1" class="form-control form-control-modern border-start-0 @error('amount_paid') is-invalid @enderror" id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}" placeholder="Jumlah Dibayar" required>
                        @error('amount_paid')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-image"></i></span>
                        <input type="file" class="form-control form-control-modern border-start-0 @error('proof_image') is-invalid @enderror" id="proof_image" name="proof_image">
                        @error('proof_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-check-circle"></i></span>
                        <select class="form-select form-control-modern border-start-0 @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Berhasil</option>
                            <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer flex-column border-0 gap-2 p-4">
                    <button type="submit" class="btn btn-danger btn-lg btn-modal-action w-100">Simpan Pembayaran</button>
                    <button type="button" class="btn btn-light btn-lg btn-modal-action w-100" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($payments as $payment)
                                    <!-- Show Payment Modal -->
                                    <div class="modal fade" id="showPaymentModal{{ $payment->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Pembayaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">ID Pembayaran:</div>
                                                        <div class="col-md-8">{{ $payment->id }}</div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Order ID:</div>
                                                        <div class="col-md-8">{{ $payment->order_id }}</div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Metode Pembayaran:</div>
                                                        <div class="col-md-8">{{ $payment->payment_method }}</div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Tanggal Pembayaran:</div>
                                                        <div class="col-md-8">{{ $payment->payment_date->format('d M Y') }}</div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Jumlah Dibayar:</div>
                                                        <div class="col-md-8">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Status:</div>
                                                        <div class="col-md-8">
                                                            <span class="badge bg-{{ $payment->status === 'confirmed' ? 'success' : ($payment->status === 'rejected' ? 'danger' : 'warning') }}">
                                                                {{ $payment->status === 'confirmed' ? 'Berhasil' : ($payment->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if($payment->proof_image)
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 fw-bold">Bukti Pembayaran:</div>
                                                            <div class="col-md-8">
                                                                <img src="{{ Storage::url($payment->proof_image) }}" alt="Bukti Pembayaran" class="img-thumbnail" style="max-height: 300px;">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edit Payment Modal -->
    <div class="modal fade custom-modal-blur" id="editPaymentModal{{ $payment->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md animate-modal">
                                            <div class="modal-content">
                <div class="modal-header bg-danger text-white align-items-center flex-column text-center border-0 rounded-top-4">
                    <span class="mb-2"><i class="fas fa-edit fa-2x"></i></span>
                    <h4 class="modal-title fw-bold w-100">Edit Pembayaran</h4>
                    <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('pembayaran.update', $payment) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                    <div class="alert alert-info">
                        order_id: {{ session('edit_payment_id') == $payment->id ? old('order_id', $payment->order_id) : $payment->order_id }}<br>
                        customer_name: {{ session('edit_payment_id') == $payment->id ? old('customer_name', $payment->customer_name) : $payment->customer_name }}<br>
                        payment_method: {{ session('edit_payment_id') == $payment->id ? old('payment_method', $payment->payment_method) : $payment->payment_method }}<br>
                        payment_date: {{ session('edit_payment_id') == $payment->id ? old('payment_date', $payment->payment_date->format('Y-m-d')) : $payment->payment_date->format('Y-m-d') }}<br>
                        amount_paid: {{ session('edit_payment_id') == $payment->id ? old('amount_paid', $payment->amount_paid) : $payment->amount_paid }}<br>
                        status: {{ session('edit_payment_id') == $payment->id ? old('status', $payment->status) : $payment->status }}
                    </div>
                    <input type="hidden" name="order_id" value="{{ session('edit_payment_id') == $payment->id ? old('order_id', $payment->order_id) : $payment->order_id }}">
                    <div class="modal-body p-4">
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control form-control-modern border-start-0 @error('customer_name') is-invalid @enderror" id="customer_name_{{ $payment->id }}" name="customer_name" value="{{ old('customer_name') && session('edit_payment_id') == $payment->id ? old('customer_name') : $payment->customer_name }}" placeholder="Nama Pelanggan" required>
                            @if(session('edit_payment_id') == $payment->id)
                                @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @endif
                                                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-money-bill-wave"></i></span>
                            <select class="form-select form-control-modern border-start-0 @error('payment_method') is-invalid @enderror" id="payment_method_{{ $payment->id }}" name="payment_method" required>
                                                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="Transfer Bank" {{ (session('edit_payment_id') == $payment->id ? old('payment_method') : $payment->payment_method) == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="E-Wallet" {{ (session('edit_payment_id') == $payment->id ? old('payment_method') : $payment->payment_method) == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                <option value="Cash" {{ (session('edit_payment_id') == $payment->id ? old('payment_method') : $payment->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                                            </select>
                            @if(session('edit_payment_id') == $payment->id)
                                @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @endif
                                                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-calendar"></i></span>
                            <input type="date" class="form-control form-control-modern border-start-0 @error('payment_date') is-invalid @enderror" id="payment_date_{{ $payment->id }}" name="payment_date" value="{{ session('edit_payment_id') == $payment->id ? old('payment_date') : $payment->payment_date->format('Y-m-d') }}" required>
                            @if(session('edit_payment_id') == $payment->id)
                                @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @endif
                                                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-coins"></i></span>
                            <input type="number" step="1" class="form-control form-control-modern border-start-0 @error('amount_paid') is-invalid @enderror" id="amount_paid_{{ $payment->id }}" name="amount_paid" value="{{ session('edit_payment_id') == $payment->id ? old('amount_paid') : $payment->amount_paid }}" placeholder="Jumlah Dibayar" required>
                            @if(session('edit_payment_id') == $payment->id)
                                @error('amount_paid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @endif
                                                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-image"></i></span>
                            <input type="file" class="form-control form-control-modern border-start-0 @error('proof_image') is-invalid @enderror" id="proof_image_{{ $payment->id }}" name="proof_image">
                                                            @if($payment->proof_image)
                                                                <div class="mb-2">
                                                                    <img src="{{ Storage::url($payment->proof_image) }}" alt="Bukti Pembayaran" class="img-thumbnail" style="max-height: 200px;">
                                                                </div>
                                                            @endif
                            @if(session('edit_payment_id') == $payment->id)
                                @error('proof_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @endif
                                                        </div>
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-check-circle"></i></span>
                            <select class="form-select form-control-modern border-start-0 @error('status') is-invalid @enderror" id="status_{{ $payment->id }}" name="status" required>
                                <option value="pending" {{ (session('edit_payment_id') == $payment->id ? old('status') : $payment->status) == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="confirmed" {{ (session('edit_payment_id') == $payment->id ? old('status') : $payment->status) == 'confirmed' ? 'selected' : '' }}>Berhasil</option>
                                <option value="rejected" {{ (session('edit_payment_id') == $payment->id ? old('status') : $payment->status) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                                            </select>
                            @if(session('edit_payment_id') == $payment->id)
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @endif
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
                            @endforeach

@push('styles')
<style>
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
    /* Modern Table */
    .table-modern {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        background: #fff;
    }
    .table-modern th {
        position: sticky;
        top: 0;
        background: #18191a;
        color: #fff;
        font-weight: 700;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dc3545;
        z-index: 2;
    }
    .table-modern tbody tr:nth-child(even) {
        background: #f8f9fa;
    }
    .table-modern tbody tr:hover {
        background: #ffeaea;
        transition: background 0.2s;
    }
    .table-modern td, .table-modern th {
        vertical-align: middle;
        padding: 0.85em 1em;
    }
    .card-modern {
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(24,25,26,0.08), 0 2px 8px rgba(0,0,0,0.08);
        border: none;
        background: #fff;
    }
    .card-header-modern {
        background: linear-gradient(90deg, #dc3545 0%, #18191a 100%) !important;
        color: #fff;
        border-radius: 18px 18px 0 0 !important;
        padding: 1.2em 1.5em;
        font-size: 1.2em;
        font-weight: 700;
    }
    .badge-status {
        font-size: 1em;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.5em 1em;
        letter-spacing: 0.5px;
        opacity: 0.92;
    }
    .badge-status.bg-success { background: #e6f4ea !important; color: #198754 !important; }
    .badge-status.bg-danger { background: #fbeaea !important; color: #dc3545 !important; }
    .badge-status.bg-warning { background: #fff7e6 !important; color: #ffc107 !important; }
    .btn-action {
        font-size: 1.2em;
        border-radius: 8px;
        margin-right: 0.2em;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .btn-action.btn-primary { background: #0d6efd; border: none; color: #fff; }
    .btn-action.btn-primary:hover { background: #084298; color: #fff; }
    .btn-action.btn-danger { background: #dc3545; border: none; color: #fff; }
    .btn-action.btn-danger:hover { background: #b71c1c; color: #fff; }
    .btn-action.btn-info { background: #0dcaf0; border: none; color: #fff; }
    .btn-action.btn-info:hover { background: #0a6b8a; color: #fff; }
    .alert-modern {
        border-radius: 12px;
        font-size: 1.08em;
        padding: 1em 1.5em;
        box-shadow: 0 2px 8px rgba(220,53,69,0.04);
        display: flex;
        align-items: center;
        gap: 0.7em;
    }
    .alert-modern .fa-check-circle { color: #198754; }
    .alert-modern .fa-exclamation-triangle { color: #dc3545; }
    @media (max-width: 768px) {
        .table-modern th, .table-modern td { padding: 0.6em 0.5em; font-size: 0.98em; }
        .card-header-modern { font-size: 1em; padding: 1em; }
    }
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('[data-bs-toggle="modal"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const target = this.getAttribute('data-bs-target');
        if (target && !document.querySelector(target).classList.contains('show')) {
            try {
                new bootstrap.Modal(document.querySelector(target)).show();
            } catch (err) {}
        }
    });
});

@if(session('edit_payment_id'))
    var editId = @json(session('edit_payment_id'));
    var modal = document.getElementById('editPaymentModal' + editId);
    if (modal) {
        var bsModal = new bootstrap.Modal(modal);
        window.addEventListener('DOMContentLoaded', function() {
            bsModal.show();
        });
    }
@endif
</script>
@endpush
@endsection 