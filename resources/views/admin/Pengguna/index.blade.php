@extends('admin.layouts.dashboard')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Manajemen Pengguna</h2>
                    <p class="text-muted">Kelola data pengguna sistem</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-import me-2"></i>Import
                    </button>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="fas fa-file-export me-2"></i>Export
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-user-plus me-2"></i>Tambah Pengguna
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('pengguna.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search" placeholder="Cari pengguna..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="role">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- User Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>Daftar Pengguna
                        </h5>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal">
                                        <i class="fas fa-trash me-2"></i>Hapus Terpilih
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkUpdateModal">
                                        <i class="fas fa-edit me-2"></i>Update Terpilih
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}">
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <div class="avatar-initial rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ Str::limit($user->address, 30) }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $user->role === 'admin' ? 'primary' : 'success' }}">
                                            <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : 'user' }} me-1"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $user->status === 'active' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            <i class="fas fa-{{ $user->status === 'active' ? 'check-circle' : 'times-circle' }} me-1"></i>
                                            {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $user->id }}">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-2x mb-3"></i>
                                            <p class="mb-0">Tidak ada data pengguna</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari {{ $users->total() }} data
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: linear-gradient(90deg, #007bff 60%, #6610f2 100%); border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <div class="d-flex align-items-center gap-2">
                    <span class="display-6"><i class="fas fa-user-plus"></i></span>
                    <h5 class="modal-title mb-0">Tambah Pengguna</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengguna.store') }}" method="POST" id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama <i class="fas fa-info-circle text-muted" data-bs-toggle="tooltip" title="Nama lengkap pengguna."></i></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama pengguna">
                        </div>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <i class="fas fa-info-circle text-muted" data-bs-toggle="tooltip" title="Email aktif pengguna."></i></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
                        </div>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <i class="fas fa-info-circle text-muted" data-bs-toggle="tooltip" title="Minimal 8 karakter."></i></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="addPassword" required placeholder="Password kuat">
                        </div>
                        <div class="form-text">
                            <div class="d-flex align-items-center mt-2">
                                <div class="password-strength-meter me-2"></div>
                                <span class="password-strength-text"></span>
                            </div>
                        </div>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx">
                        </div>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3" required placeholder="Alamat lengkap pengguna">{{ old('address') }}</textarea>
                        </div>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-user-tag"></i></span>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                            </select>
                        </div>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-toggle-on"></i></span>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-import me-2"></i>Import Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengguna.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">File Excel</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls" required>
                        <div class="form-text">
                            <a href="{{ asset('templates/user_import_template.xlsx') }}" class="text-decoration-none">
                                <i class="fas fa-download me-1"></i>Download Template
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-export me-2"></i>Export Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengguna.export') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select class="form-select" name="format" required>
                            <option value="xlsx">Excel (.xlsx)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kolom</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="id" checked>
                            <label class="form-check-label">ID</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="name" checked>
                            <label class="form-check-label">Nama</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="email" checked>
                            <label class="form-check-label">Email</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="phone" checked>
                            <label class="form-check-label">No. Telepon</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="address" checked>
                            <label class="form-check-label">Alamat</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="role" checked>
                            <label class="form-check-label">Role</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="status" checked>
                            <label class="form-check-label">Status</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash me-2"></i>Hapus Pengguna Terpilih
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengguna.bulk-delete') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pengguna yang dipilih?</p>
                    <input type="hidden" name="ids" id="bulkDeleteIds">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Update Pengguna Terpilih
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengguna.bulk-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">-- Pilih Status --</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>
                    <input type="hidden" name="ids" id="bulkUpdateIds">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
@foreach($users as $user)
<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: linear-gradient(90deg, #007bff 60%, #6610f2 100%); border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <div class="d-flex align-items-center gap-2">
                    <span class="display-6"><i class="fas fa-edit"></i></span>
                    <h5 class="modal-title mb-0">Edit Pengguna</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengguna.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required placeholder="Masukkan nama pengguna">
                        </div>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required placeholder="nama@email.com">
                        </div>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-muted" style="font-size:0.9em;">(Kosongkan jika tidak ingin mengubah)</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="editPassword{{ $user->id }}" placeholder="Password baru">
                        </div>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="08xxxxxxxxxx">
                        </div>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3" required placeholder="Alamat lengkap pengguna">{{ old('address', $user->address) }}</textarea>
                        </div>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-user-tag"></i></span>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                            </select>
                        </div>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fas fa-toggle-on"></i></span>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key me-2"></i>Reset Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengguna.reset-password', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mereset password untuk pengguna <strong>{{ $user->name }}</strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" name="password" id="resetPassword{{ $user->id }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .badge {
        padding: 0.5em 0.75em;
    }
    
    .btn-group .btn {
        border-radius: 5px;
        margin: 0 2px;
    }
    
    .modal-content {
        border-radius: 10px;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    
    .form-control, .form-select {
        border-left: none;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #ced4da;
        box-shadow: none;
    }
    
    .avatar-initial {
        font-weight: 600;
        font-size: 0.875rem;
    }

    .password-strength-meter {
        height: 5px;
        width: 100px;
        background-color: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
    }

    .password-strength-meter-fill {
        height: 100%;
        width: 0;
        transition: width 0.3s ease;
    }

    .password-strength-text {
        font-size: 0.75rem;
        color: #6c757d;
    }
</style>

@push('scripts')
<script>
    // Password Strength Meter
    const passwordInput = document.getElementById('password');
    const strengthMeter = document.querySelector('.password-strength-meter');
    const strengthText = document.querySelector('.password-strength-text');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let text = '';

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        switch(strength) {
            case 0:
            case 1:
                text = 'Sangat Lemah';
                strengthMeter.style.backgroundColor = '#dc3545';
                break;
            case 2:
                text = 'Lemah';
                strengthMeter.style.backgroundColor = '#ffc107';
                break;
            case 3:
                text = 'Sedang';
                strengthMeter.style.backgroundColor = '#fd7e14';
                break;
            case 4:
                text = 'Kuat';
                strengthMeter.style.backgroundColor = '#20c997';
                break;
            case 5:
                text = 'Sangat Kuat';
                strengthMeter.style.backgroundColor = '#198754';
                break;
        }

        strengthMeter.style.width = (strength * 20) + '%';
        strengthText.textContent = text;
    });

    // Generate Random Password
    document.getElementById('generatePassword').addEventListener('click', function() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('password').value = password;
        passwordInput.dispatchEvent(new Event('input'));
    });

    function generatePassword(inputId) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById(inputId).value = password;
    }

    // Bulk Actions
    const selectAll = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');

    selectAll.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    document.getElementById('bulkDeleteModal').addEventListener('show.bs.modal', function() {
        const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(checkbox => checkbox.value);
        document.getElementById('bulkDeleteIds').value = selectedIds.join(',');
    });

    document.getElementById('bulkUpdateModal').addEventListener('show.bs.modal', function() {
        const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(checkbox => checkbox.value);
        document.getElementById('bulkUpdateIds').value = selectedIds.join(',');
    });

    // Toggle show/hide password (event delegation, works for dynamic modals and icon click)
    document.addEventListener('click', function(e) {
        let btn = null;
        if (e.target.classList.contains('toggle-password')) {
            btn = e.target;
        } else if (e.target.closest('.toggle-password')) {
            btn = e.target.closest('.toggle-password');
        }
        if (btn) {
            const targetSelector = btn.getAttribute('data-target');
            const target = document.querySelector(targetSelector);
            if (target) {
                if (target.type === 'password') {
                    target.type = 'text';
                    btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    target.type = 'password';
                    btn.innerHTML = '<i class="fas fa-eye"></i>';
                }
            }
        }
    });

    // Aktifkan tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection 