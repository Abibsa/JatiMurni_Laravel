@extends('pengguna.layouts.user')

@section('title', 'Profil')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
        min-height: 100vh;
    }
    .profile-card {
        background: rgba(255,255,255,0.95);
        border-radius: 2rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        transition: transform 0.2s;
    }
    .profile-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 16px 40px 0 rgba(31, 38, 135, 0.20);
    }
    .avatar-border {
        border: 4px solid #0D8ABC;
        padding: 3px;
        background: #fff;
        display: inline-block;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(13,138,188,0.15);
    }
    .profile-badge {
        background: #0D8ABC;
        color: #fff;
        font-size: 0.9rem;
        padding: 0.3em 1em;
        border-radius: 1em;
        margin-bottom: 1rem;
        display: inline-block;
        letter-spacing: 1px;
    }
    .profile-list .list-group-item {
        background: transparent;
        border: none;
        padding-left: 0;
        padding-right: 0;
        font-size: 1.08rem;
    }
    .profile-label {
        font-weight: 600;
        color: #0D8ABC;
        min-width: 120px;
        display: inline-block;
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="profile-card p-5">
                <div class="text-center">
                    <span class="avatar-border">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff&size=120"
                             alt="Avatar" class="rounded-circle shadow" width="120" height="120">
                    </span>
                    <div class="profile-badge mt-2">Pengguna Aktif</div>
                    <h2 class="fw-bold mt-3 mb-1">{{ $user->name }}</h2>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                </div>
                <hr>
                <ul class="list-group list-group-flush mb-4 profile-list">
                    <li class="list-group-item">
                        <span class="profile-label">Nama</span>: {{ $user->name }}
                    </li>
                    <li class="list-group-item">
                        <span class="profile-label">Email</span>: {{ $user->email }}
                    </li>
                    <li class="list-group-item">
                        <span class="profile-label">No. HP</span>: {{ $user->phone ?? '-' }}
                    </li>
                    <li class="list-group-item">
                        <span class="profile-label">Alamat</span>: {{ $user->address ?? '-' }}
                    </li>
                    <li class="list-group-item">
                        <span class="profile-label">Tanggal Bergabung</span>: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 