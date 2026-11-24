@extends('layouts.app')

@section('title', 'Profil Akun - Toko Buku Aksara')

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h1 class="h3 fw-bold text-dark mb-1"><i class="bi bi-person-gear text-primary me-2"></i>Pengaturan Profil Akun</h1>
        <p class="text-muted small mb-0">Kelola informasi data diri, alamat pengiriman utama, dan keamanan kata sandi Anda.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 bg-white text-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary p-4 rounded-circle mx-auto mb-3" style="width: 90px; height: 90px;">
                    <i class="bi bi-person-circle fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-2">{{ $user->email }}</p>
                <div>
                    <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-primary' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                @if($user->profile && $user->profile->bio)
                    <p class="text-muted small fst-italic px-2 mt-2 mb-0">"{{ $user->profile->bio }}"</p>
                @endif
                <hr class="my-3">
                <div class="small text-start text-secondary d-flex flex-column gap-2">
                    @if($user->profile && $user->profile->city)
                        <div><strong>Kota:</strong> {{ $user->profile->city }}</div>
                    @endif
                    <div><strong>Bergabung:</strong> {{ $user->created_at->format('d M Y') }}</div>
                    <div><strong>Total Pesanan:</strong> {{ $user->orders->count() }} Pesanan</div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 p-md-5 bg-white">
                <h5 class="fw-bold text-dark mb-4">Perbarui Informasi Profil</h5>

                @if($errors->any())
                    <div class="alert alert-danger small py-2 mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Alamat Pengiriman</label>
                            <textarea name="address" rows="3" class="form-control" required>{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold text-dark mb-3">Ganti Kata Sandi (Kosongkan jika tidak ingin mengubah)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control" placeholder="Password lama">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Password baru">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
