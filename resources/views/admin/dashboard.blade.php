@extends('layouts.admin')

@section('title', 'Dashboard Administrator - Toko Buku Aksara')

@section('content')
<!-- Hero Welcome Header -->
<div class="card border-0 text-white shadow-sm rounded-4 mb-4 overflow-hidden" 
     style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 55%, #4f46e5 100%);">
    <div class="card-body p-4 p-md-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge bg-white bg-opacity-20 text-white px-3 py-2 rounded-pill fw-semibold mb-2">
                    <i class="bi bi-shield-check me-1 text-warning"></i> Panel Kontrol Toko Buku Aksara &bull; BNSP USK
                </span>
                <h2 class="fw-extrabold display-6 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="text-white-50 mb-3" style="max-width: 620px;">
                    Sistem operasional dan manajemen katalog Toko Buku Aksara beroperasi normal. Pantau performa penjualan, ketersediaan stok buku, dan pesan pelanggan secara realtime.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.books.create') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Buku Baru
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold">
                        <i class="bi bi-bag-check me-1"></i> Kelola Pesanan ({{ $pendingOrders }} Baru)
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold">
                        <i class="bi bi-tags me-1"></i> Data Kategori
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur border border-white border-opacity-10 text-start">
                    <div class="small text-white-50 mb-1"><i class="bi bi-cash-stack me-1"></i>Total Estimasi Pendapatan:</div>
                    <div class="h3 fw-extrabold text-white mb-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="d-flex justify-content-between text-white-50 small border-top border-white border-opacity-10 pt-2">
                        <span>Pesanan Tertunda:</span>
                        <strong class="text-warning">{{ $pendingOrders }} pesanan</strong>
                    </div>
                    <div class="d-flex justify-content-between text-white-50 small pt-1">
                        <span>Pesan Belum Dibaca:</span>
                        <strong class="text-info">{{ $unreadMessages }} pesan</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 4 Modern Metric Cards -->
<div class="row g-3 mb-4">
    <!-- Metric 1: Books -->
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Total Koleksi Buku</span>
                    <h3 class="fw-extrabold text-dark mt-1 mb-0">{{ $totalBooks }}</h3>
                    <div class="mt-2">
                        <a href="{{ route('admin.books.index') }}" class="text-primary text-decoration-none small fw-semibold">
                            Buka Katalog Buku &rarr;
                        </a>
                    </div>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                    <i class="bi bi-journal-bookmark-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Metric 2: Categories -->
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-info">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Kategori Terdaftar</span>
                    <h3 class="fw-extrabold text-dark mt-1 mb-0">{{ $totalCategories }}</h3>
                    <div class="mt-2">
                        <a href="{{ route('admin.categories.index') }}" class="text-info text-decoration-none small fw-semibold">
                            Kelola Kategori &rarr;
                        </a>
                    </div>
                </div>
                <div class="bg-info bg-opacity-10 text-info p-3 rounded-4">
                    <i class="bi bi-grid-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Metric 3: Users -->
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Pelanggan Terdaftar</span>
                    <h3 class="fw-extrabold text-dark mt-1 mb-0">{{ $totalUsers }}</h3>
                    <div class="mt-2">
                        <a href="{{ route('admin.users.index') }}" class="text-success text-decoration-none small fw-semibold">
                            Data User &rarr;
                        </a>
                    </div>
                </div>
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-4">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Metric 4: Orders -->
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Total Transaksi</span>
                    <h3 class="fw-extrabold text-dark mt-1 mb-0">{{ $totalOrders }}</h3>
                    <div class="mt-2">
                        <a href="{{ route('admin.orders.index') }}" class="text-warning text-dark text-decoration-none small fw-semibold">
                            Riwayat Order &rarr;
                        </a>
                    </div>
                </div>
                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-4">
                    <i class="bi bi-bag-check-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table: Recent Orders Full-Width Container -->
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4 overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-receipt text-primary me-2"></i>Transaksi Pesanan Terbaru
            </h5>
            <small class="text-muted">Daftar transaksi buku masuk yang membutuhkan pemrosesan.</small>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
            Lihat Semua Pesanan
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-muted text-uppercase">
                <tr>
                    <th class="ps-4">No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Total Bayar</th>
                    <th>Metode Pembayaran</th>
                    <th>Status Pesanan</th>
                    <th>Waktu Masuk</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                {{ $order->order_number }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong class="d-block text-dark">{{ $order->customer_name }}</strong>
                                    <small class="text-muted">{{ $order->customer_phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="fw-bold text-primary">
                            {{ $order->formatted_total }}
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border px-2 py-1">
                                <i class="bi bi-wallet2 me-1"></i>{{ $order->payment_method }}
                            </span>
                        </td>
                        <td>
                            {!! $order->status_badge !!}
                        </td>
                        <td class="small text-muted">
                            {{ $order->created_at->diffForHumans() }}
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                Rincian &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Belum ada riwayat pesanan yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Bottom Section: Customer Messages & Low Stock Alert -->
<div class="row g-4">
    <!-- Messages (Col-7) -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-chat-dots-fill text-info me-2"></i>Pesan Masuk Terbaru
                    </h6>
                    <small class="text-muted">Pesan dari pengunjung toko buku via form Kontak Admin.</small>
                </div>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                    Lihat Semua ({{ $unreadMessages }} Baru)
                </a>
            </div>
            <div class="p-3">
                @forelse($recentMessages as $msg)
                    <div class="p-3 rounded-3 mb-2 border {{ $msg->is_read ? 'bg-light' : 'bg-primary bg-opacity-10 border-primary border-opacity-25' }}">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <strong class="text-dark">{{ $msg->name }}</strong>
                                <span class="text-muted small">({{ $msg->email }})</span>
                            </div>
                            <span class="badge {{ $msg->is_read ? 'bg-secondary' : 'bg-primary' }} rounded-pill small">
                                {{ $msg->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="fw-semibold text-dark small mb-1">{{ $msg->subject }}</div>
                        <p class="text-secondary small mb-0">{{ Str::limit($msg->message, 90) }}</p>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        Belum ada pesan yang masuk.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Low Stock Alert (Col-5) -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Peringatan Stok Menipis
                    </h6>
                    <small class="text-muted">Buku dengan persediaan &le; 5 eksemplar.</small>
                </div>
                <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3">
                    Update Stok
                </a>
            </div>
            <div class="p-3">
                @forelse($lowStockBooks as $lowBook)
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border mb-2 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $lowBook->cover_url }}" onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';" 
                                 class="rounded shadow-sm" style="width: 38px; height: 50px; object-fit: cover;">
                            <div>
                                <strong class="d-block text-dark small text-truncate" style="max-width: 190px;">{{ $lowBook->title }}</strong>
                                <small class="text-muted">{{ $lowBook->category->name }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger rounded-pill px-2 py-1">Sisa {{ $lowBook->stock }}</span>
                            <div class="mt-1">
                                <a href="{{ route('admin.books.edit', $lowBook->id) }}" class="small text-primary text-decoration-none">Edit &rarr;</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                        Semua persediaan buku dalam kondisi aman (&gt; 5 buku).
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
