<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Toko Buku Aksara</title>
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
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #4f46e5;
            --topbar-height: 68px;
            --sidebar-width: 260px;
        }
        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
        .bg-primary {
            background-color: #4f46e5 !important;
        }
        .text-primary {
            color: #4f46e5 !important;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            overflow-x: hidden;
        }
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: #94a3b8;
            z-index: 1040;
            transition: all 0.3s;
            overflow-y: auto;
        }
        #sidebar .nav-link {
            color: #94a3b8;
            font-weight: 500;
            padding: 10px 18px;
            border-radius: 8px;
            margin: 3px 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
        }
        #sidebar .nav-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }
        #sidebar .nav-link.active {
            color: #ffffff;
            background-color: var(--sidebar-active);
            font-weight: 600;
        }
        #sidebar .nav-heading {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            padding: 12px 24px 6px;
            font-weight: 700;
        }
        #content-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }
        .top-navbar {
            height: var(--topbar-height);
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .table > :not(caption) > * > * {
            padding: 12px 14px;
            vertical-align: middle;
        }
        @media (max-width: 991.98px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.show {
                margin-left: 0;
            }
            #content-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="p-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center gap-2 fs-5 fw-bold">
                <i class="bi bi-book-half text-primary fs-4"></i>
                <span>Aksara Admin</span>
            </a>
            <button class="btn btn-sm btn-dark d-lg-none" id="closeSidebarBtn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="py-3">
            <div class="nav-heading">Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <div class="nav-heading">Manajemen Data Buku</div>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i>
                <span>Kategori Buku</span>
            </a>
            <a href="{{ route('admin.books.index') }}" class="nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                <i class="bi bi-journal-album"></i>
                <span>Data Buku</span>
            </a>

            <div class="nav-heading">Transaksi & Pengguna</div>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-check-fill"></i>
                <span>List Pesanan User</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>List User Terdaftar</span>
            </a>
            <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper-fill"></i>
                <span>Pesan Masuk (Kontak)</span>
            </a>

            <div class="nav-heading">Tautan</div>
            <a href="{{ route('home') }}" target="_blank" class="nav-link text-info">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Lihat Website Toko</span>
            </a>
            
            <div class="px-3 mt-4">
                <form action="{{ route('logout') }}" method="POST" id="logoutFormSidebar">
                    @csrf
                    <button type="button" class="btn btn-danger w-100 btn-sm py-2" onclick="confirmLogout('logoutFormSidebar')">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout Admin
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div id="content-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="toggleSidebarBtn">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="fw-semibold text-dark d-none d-sm-block">
                    <i class="bi bi-shield-lock-fill text-primary me-2"></i>Sistem Manajemen Toko Buku Aksara (BNSP USK)
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-primary px-3 py-2 text-white">
                    <i class="bi bi-person-badge me-1"></i> Administrator: {{ Auth::user()->name }}
                </span>
                <form action="{{ route('logout') }}" method="POST" id="logoutFormTop" class="d-inline">
                    @csrf
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmLogout('logoutFormTop')">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar (Logout)
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Body -->
        <main class="p-4 flex-grow-1">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm rounded-3 border-0 py-3 mb-4" role="alert">
                    <i class="bi bi-shield-x fs-3 text-danger"></i>
                    <div>
                        <strong class="d-block text-dark">Pemberitahuan Sistem:</strong>
                        <span class="text-danger-emphasis">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 border-0 py-3 mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                        <strong class="text-dark">Validasi Gagal:</strong>
                    </div>
                    <ul class="mb-0 ps-4 small text-danger-emphasis">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm rounded-3 border-0 py-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <strong class="d-block text-dark">Berhasil:</strong>
                        <span class="text-success-emphasis">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-top py-3 px-4 text-center text-muted small">
            &copy; {{ date('Y') }} Panel Admin Toko Buku Aksara &bull; Modul USK Junior Web Developer
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Toggle mobile sidebar
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                sidebar.classList.remove('show');
            });
        }

        // Global SweetAlert Confirm Delete Helper
        function confirmDelete(formId, message = 'Data yang dihapus tidak dapat dikembalikan!') {
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
                    document.getElementById(formId).submit();
                }
            });
        }

        // Global SweetAlert Confirm Logout Helper
        function confirmLogout(formId) {
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
        }

        // Global Flash Message
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Perhatian!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Input Gagal!',
                text: "{{ $errors->first() }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>
    @yield('scripts')
</body>
</html>
