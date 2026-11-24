@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Banner Section -->
<section class="py-5 hero-banner text-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">
                    <i class="bi bi-stars me-1"></i> Toko Buku Online No. 1 Terpercaya
                </span>
                <h1 class="display-4 fw-extrabold text-white mb-3" style="font-weight: 800; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    Jelajahi Dunia Lewat Halaman Buku Pilihan
                </h1>
                <p class="lead text-white text-opacity-90 mb-4" style="text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                    Temukan ribuan judul buku terbaik: Pemrograman, Sastra Fiksi, Bisnis Finansial, hingga Pengembangan Diri. Kemudahan pembayaran dengan <strong>Payment at Delivery (COD)</strong> atau Transfer Bank.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('books.index') }}" class="btn btn-warning btn-lg px-4 fw-bold shadow-sm rounded-pill text-dark">
                        <i class="bi bi-compass me-2"></i> Jelajahi Katalog Buku
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg px-4 fw-semibold rounded-pill">
                        Tentang Kami
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <div class="position-relative d-inline-block">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80" 
                         onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';"
                         alt="Hero Books" class="img-fluid rounded-4 shadow-lg border border-4 border-white border-opacity-25" style="max-height: 380px; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 translate-middle-x hero-floating-card p-3 shadow-lg text-start border ms-5 mb-3" style="max-width: 230px;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <span class="fw-bold small text-dark">Koleksi Lengkap</span>
                        </div>
                        <div class="text-muted small">Lebih dari {{ $totalBooksCount ?? 50 }}+ judul buku siap kirim!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Bar -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div class="text-start">
                        <h6 class="fw-bold mb-0">100% Buku Asli</h6>
                        <small class="text-muted">Langsung dari penerbit</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                        <i class="bi bi-cash-coin fs-4"></i>
                    </div>
                    <div class="text-start">
                        <h6 class="fw-bold mb-0">Payment at Delivery</h6>
                        <small class="text-muted">Bayar aman saat sampai</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                        <i class="bi bi-truck fs-4"></i>
                    </div>
                    <div class="text-start">
                        <h6 class="fw-bold mb-0">Pengiriman Cepat</h6>
                        <small class="text-muted">Kemasan rapi & aman</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle">
                        <i class="bi bi-headset fs-4"></i>
                    </div>
                    <div class="text-start">
                        <h6 class="fw-bold mb-0">Layanan Prima</h6>
                        <small class="text-muted">Respon cepat admin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h6 class="text-primary fw-bold text-uppercase tracking-wider small">Eksplorasi Minat</h6>
                <h3 class="fw-bold mb-0">Kategori Buku Pilihan</h3>
            </div>
            <a href="{{ route('books.index') }}" class="text-decoration-none fw-semibold">
                Lihat Semua Kategori <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3">
            @foreach($categories as $category)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('books.index', ['category' => $category->slug]) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 p-3 card-hover border-0 shadow-sm bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                                        <i class="bi bi-bookmark-star-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $category->name }}</h6>
                                        <span class="text-muted small">{{ $category->books_count }} Koleksi Buku</span>
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Books Section -->
@if($featuredBooks->isNotEmpty())
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1 rounded-pill fw-semibold mb-1">
                    <i class="bi bi-fire me-1"></i> Paling Diminati
                </span>
                <h3 class="fw-bold mb-0">Buku Unggulan (Featured)</h3>
            </div>
            <a href="{{ route('books.index') }}" class="text-decoration-none fw-semibold">
                Katalog Lengkap <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($featuredBooks as $book)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 card-hover border-0 shadow-sm overflow-hidden">
                        <div class="position-relative">
                            <a href="{{ route('books.show', $book->slug) }}">
                                <div class="book-cover-wrapper">
                                    <img src="{{ $book->cover_url }}" onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';" alt="{{ $book->title }}" class="book-cover-img" loading="lazy">
                                </div>
                            </a>
                            <span class="position-absolute top-0 start-0 m-2 badge bg-primary">
                                {{ $book->category->name }}
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <small class="text-muted mb-1"><i class="bi bi-pen me-1"></i>{{ $book->author }}</small>
                            <h6 class="card-title fw-bold text-dark mb-2" style="height: 44px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <a href="{{ route('books.show', $book->slug) }}" class="text-decoration-none text-dark">
                                    {{ $book->title }}
                                </a>
                            </h6>
                            <div class="mt-auto pt-2">
                                <div class="d-flex align-items-baseline justify-content-between mb-3">
                                    <span class="fs-5 fw-bold text-primary">{{ $book->formatted_price }}</span>
                                    <small class="text-{{ $book->stock > 0 ? 'success' : 'danger' }} fw-semibold">
                                        {{ $book->stock > 0 ? 'Stok: ' . $book->stock : 'Stok Habis' }}
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('books.show', $book->slug) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                        Detail
                                    </a>
                                    @if($book->stock > 0)
                                        <form action="{{ route('cart.store') }}" method="POST" class="flex-fill">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-cart-plus me-1"></i> Beli
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm flex-fill" disabled>Habis</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest Books Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h6 class="text-primary fw-bold text-uppercase tracking-wider small">Koleksi Baru</h6>
                <h3 class="fw-bold mb-0">Buku Rilis Terbaru</h3>
            </div>
            <a href="{{ route('books.index', ['sort' => 'latest']) }}" class="text-decoration-none fw-semibold">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($latestBooks as $book)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 card-hover border-0 shadow-sm overflow-hidden">
                        <div class="position-relative">
                            <a href="{{ route('books.show', $book->slug) }}">
                                <div class="book-cover-wrapper">
                                    <img src="{{ $book->cover_url }}" onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';" alt="{{ $book->title }}" class="book-cover-img" loading="lazy">
                                </div>
                            </a>
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75">
                                {{ $book->category->name }}
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <small class="text-muted mb-1"><i class="bi bi-pen me-1"></i>{{ $book->author }}</small>
                            <h6 class="card-title fw-bold text-dark mb-2" style="height: 44px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <a href="{{ route('books.show', $book->slug) }}" class="text-decoration-none text-dark">
                                    {{ $book->title }}
                                </a>
                            </h6>
                            <div class="mt-auto pt-2">
                                <div class="d-flex align-items-baseline justify-content-between mb-3">
                                    <span class="fs-5 fw-bold text-primary">{{ $book->formatted_price }}</span>
                                    <small class="text-{{ $book->stock > 0 ? 'success' : 'danger' }} fw-semibold">
                                        {{ $book->stock > 0 ? 'Stok: ' . $book->stock : 'Stok Habis' }}
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('books.show', $book->slug) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                        Detail
                                    </a>
                                    @if($book->stock > 0)
                                        <form action="{{ route('cart.store') }}" method="POST" class="flex-fill">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-cart-plus me-1"></i> Beli
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm flex-fill" disabled>Habis</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center py-3">
        <h2 class="fw-bold mb-3">Butuh Rekomendasi Buku atau Memiliki Pertanyaan?</h2>
        <p class="lead text-light text-opacity-90 mb-4" style="max-width: 650px; margin: 0 auto;">
            Tim Toko Buku Aksara selalu siap membantu Anda menemukan buku yang sesuai dengan minat baca atau kebutuhan studi Anda.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('contact') }}" class="btn btn-warning btn-lg px-4 fw-bold rounded-pill text-dark">
                <i class="bi bi-chat-dots-fill me-2"></i> Hubungi Admin Kami
            </a>
            <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg px-4 fw-semibold rounded-pill">
                Pelajari Tentang Kami
            </a>
        </div>
    </div>
</section>
@endsection
