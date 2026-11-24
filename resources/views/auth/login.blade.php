@extends('layouts.app')

@section('title', 'Masuk Akun - Toko Buku Aksara')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-lg p-4 p-md-5 bg-white rounded-4">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary p-3 rounded-circle mb-3">
                        <i class="bi bi-box-arrow-in-right fs-2"></i>
                    </div>
                    <h3 class="fw-extrabold text-dark">Selamat Datang</h3>
                    <p class="text-muted small">Masuk ke akun Toko Buku Aksara Anda</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center gap-2 small py-2 mb-3">
                        <i class="bi bi-shield-lock-fill fs-5 flex-shrink-0"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 small py-2 mb-3">
                        <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger small py-2">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Email atau Nama Pengguna</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-fill text-muted"></i></span>
                            <input type="text" name="login" id="loginInput" class="form-control border-start-0 @error('login') is-invalid @enderror @error('name') is-invalid @enderror @error('email') is-invalid @enderror" 
                                   value="{{ old('login', old('email', old('name'))) }}" placeholder="Masukkan email atau nama akun" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Kata Sandi (Password)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" id="passwordInput" class="form-control border-start-0" 
                                   placeholder="Masukkan kata sandi" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                            <label class="form-check-label small text-muted" for="rememberMe">
                                Ingat Saya
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill shadow-sm mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
                    </button>

                    <div class="text-center small text-muted">
                        Belum memiliki akun pembeli? 
                        <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar Akun Baru</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
