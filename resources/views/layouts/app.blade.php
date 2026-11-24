<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Buku Aksara') - Toko Buku Terlengkap & Terpercaya</title>
    <meta name="description" content="Toko Buku Aksara - Pilihan buku berkualitas dengan layanan terbaik, pembayaran mudah dan aman.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-primary: #4f46e5;
            --bs-primary-rgb: 79, 70, 229;
            --primary-hover: #4338ca;
            --accent-color: #f59e0b;
            --dark-color: #0f172a;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #1e1b4b !important;
        }
        .navbar-brand span {
            color: #4f46e5;
        }
        .nav-link {
            font-weight: 500;
            color: #475569;
            transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            color: #4f46e5 !important;
        }
        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.1);
        }
        .hero-banner {
            background-color: #0f172a !important;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%) !important;
            color: #ffffff !important;
        }
        .hero-banner .hero-floating-card {
            background: #ffffff !important;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
        }
        .hero-banner .hero-floating-card * {
            color: #0f172a !important;
        }
        .hero-banner .hero-floating-card .text-success {
            color: #16a34a !important;
        }
        .hero-banner .hero-floating-card .text-muted {
            color: #64748b !important;
        }
        .book-cover-wrapper {
            position: relative;
            padding-top: 140%;
            overflow: hidden;
            border-radius: 10px;
            background-color: #f1f5f9;
        }
        .book-cover-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .card-hover:hover .book-cover-img {
            transform: scale(1.04);
        }
        footer {
            background-color: #0f172a;
            color: #94a3b8;
        }
        footer h5 {
            color: #f8fafc;
            font-weight: 700;
        }
        footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }
        footer a:hover {
            color: #ffffff;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Top Announcement Bar -->
    <div class="bg-dark text-white py-1 px-3 small border-bottom border-secondary border-opacity-25">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-truck me-1 text-warning"></i> Melayani Pengiriman ke Seluruh Indonesia | <span class="badge bg-primary bg-opacity-75">Pembayaran Praktis QRIS & Transfer Bank</span>
            </div>
            <div class="d-none d-md-flex gap-3">
                <span><i class="bi bi-telephone me-1"></i> (021) 8899-7722</span>
                <span><i class="bi bi-envelope me-1"></i> info@aksarabookstore.com</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fs-4 d-flex align-items-center gap-2" href="{{ route('home') }}">
                <i class="bi bi-book-half text-primary fs-3"></i>
                <span>Aksara</span>BookStore
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Search Box in Navbar -->
                <form action="{{ route('books.index') }}" method="GET" class="d-flex mx-lg-4 my-2 my-lg-0 flex-grow-1" style="max-width: 450px;">
                    <div class="input-group">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-end-0 rounded-start-pill ps-3" placeholder="Cari judul buku, penulis, atau ISBN...">
                        <button class="btn btn-light border border-start-0 rounded-end-pill px-3 text-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">Katalog Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak Admin</a>
                    </li>
                </ul>

                <!-- User & Cart Controls -->
                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    <!-- Cart Button -->
                    <a href="{{ route('cart.index') }}" class="btn btn-light position-relative rounded-pill px-3 py-2 border shadow-sm me-2">
                        <i class="bi bi-cart3 text-primary fs-5 align-middle"></i>
                        <span class="d-none d-sm-inline ms-1 fw-semibold">Keranjang</span>
                        @if($globalCartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $globalCartCount }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-5"></i>
                                <span>{{ Str::limit(Auth::user()->name, 15) }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                                <li class="px-3 py-2 bg-light border-bottom">
                                    <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                                    <div class="small text-muted">{{ Auth::user()->email }}</div>
                                    <span class="badge {{ Auth::user()->isAdmin() ? 'bg-danger' : 'bg-primary' }} mt-1">
                                        {{ ucfirst(Auth::user()->role) }}
                                    </span>
                                </li>
                                @if(Auth::user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item py-2 text-primary fw-semibold" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Panel Administrator
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('orders.index') }}">
                                        <i class="bi bi-bag-check me-2"></i>Pesanan Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profile') }}">
                                        <i class="bi bi-person-gear me-2"></i>Profil Akun
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" id="logoutFormApp">
                                        @csrf
                                        <button type="button" class="dropdown-item py-2 text-danger" onclick="confirmLogout('logoutFormApp')">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar (Logout)
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="d-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-3 fw-semibold">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-3 fw-semibold">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="main-content">
        @if(session('error'))
            <div class="container pt-3">
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm rounded-3 border-0 py-3" role="alert">
                    <i class="bi bi-shield-lock-fill fs-3 text-danger"></i>
                    <div>
                        <strong class="d-block text-dark">Pemberitahuan Hak Akses:</strong>
                        <span class="text-danger-emphasis">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('success'))
            <div class="container pt-3">
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm rounded-3 border-0 py-3" role="alert">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <strong class="d-block text-dark">Berhasil:</strong>
                        <span class="text-success-emphasis">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-book-half text-primary fs-3"></i>
                        <span class="fs-4 fw-bold text-white">Aksara<span class="text-primary">BookStore</span></span>
                    </div>
                    <p class="small text-secondary mb-3">
                        Toko Buku Aksara menyediakan ribuan koleksi buku pilihan berkualitas dari penerbit terkemuka nasional maupun internasional. Belanja buku mudah, cepat, dan terpercaya.
                    </p>
                    <div class="d-flex gap-3 fs-5 text-secondary">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="fs-6 mb-3">Navigasi</h5>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('books.index') }}">Katalog Buku</a></li>
                        <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}">Kontak Admin</a></li>
                        <li><a href="{{ route('orders.index') }}">Status Pesanan</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="fs-6 mb-3">Kategori Pilihan</h5>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        @foreach($navbarCategories->take(5) as $cat)
                            <li>
                                <a href="{{ route('books.index', ['category' => $cat->slug]) }}">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="fs-6 mb-3">Kontak & Layanan</h5>
                    <p class="small text-secondary mb-2">
                        <i class="bi bi-geo-alt me-2 text-warning"></i> Jl. Matraman Raya No. 42, Jakarta Timur
                    </p>
                    <p class="small text-secondary mb-2">
                        <i class="bi bi-whatsapp me-2 text-success"></i> +62 812-9876-5432
                    </p>
                    <p class="small text-secondary mb-2">
                        <i class="bi bi-clock me-2 text-info"></i> Senin - Sabtu: 08.00 - 20.00 WIB
                    </p>
                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25 mt-2">
                        <i class="bi bi-shield-check me-1"></i> 100% Produk Buku Asli
                    </span>
                </div>
            </div>

            <hr class="border-secondary border-opacity-25 my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-secondary">
                <div>&copy; {{ date('Y') }} Toko Buku Aksara. Proyek Uji Sertifikasi Kompetensi (USK) BNSP Junior Web Developer.</div>
                <div class="mt-2 mt-md-0">
                    Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> menggunakan Laravel & Bootstrap 5
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global SweetAlert Confirm Delete Helper for User Portal
        function confirmDelete(formId, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Hapus?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const targetForm = document.getElementById(formId);
                        if (targetForm) {
                            targetForm.submit();
                        }
                    }
                });
            } else {
                if (confirm(message)) {
                    const targetForm = document.getElementById(formId);
                    if (targetForm) {
                        targetForm.submit();
                    }
                }
            }
        }

        // Global SweetAlert Confirm Logout Helper
        function confirmLogout(formId = 'logoutFormApp') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Keluar?',
                    text: 'Apakah Anda yakin ingin keluar dari akun?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Keluar!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const targetForm = document.getElementById(formId);
                        if (targetForm) targetForm.submit();
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin keluar dari akun?')) {
                    const targetForm = document.getElementById(formId);
                    if (targetForm) targetForm.submit();
                }
            }
        }

        // Global Flash Message Handler using SweetAlert2
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3500,
                showConfirmButton: false,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Tutup'
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: "{{ session('info') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
    @yield('scripts')
</body>
</html>
