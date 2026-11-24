@extends('layouts.app')

@section('title', $book->title . ' - Toko Buku Aksara')

@section('content')
<!-- Breadcrumbs -->
<div class="bg-white border-bottom py-3 mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">Katalog Buku</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('books.index', ['category' => $book->category->slug]) }}" class="text-decoration-none">
                        {{ $book->category->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active text-truncate" style="max-width: 250px;" aria-current="page">{{ $book->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="card border-0 shadow-sm p-4 p-md-5 bg-white mb-5">
        <div class="row g-5">
            <!-- Book Cover -->
            <div class="col-lg-4 col-md-5">
                <div class="position-sticky" style="top: 100px;">
                    <div class="book-cover-wrapper shadow rounded-3 border">
                        <img src="{{ $book->cover_url }}" onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';" alt="{{ $book->title }}" class="book-cover-img">
                    </div>
                    @if($book->is_featured)
                        <div class="mt-3 text-center">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-semibold shadow-sm">
                                <i class="bi bi-star-fill me-1"></i> Buku Pilihan Editor
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Book Info & Purchase Form -->
            <div class="col-lg-8 col-md-7">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill fw-semibold">
                        {{ $book->category->name }}
                    </span>
                    @if($book->tags->isNotEmpty())
                        @foreach($book->tags as $tag)
                            <span class="badge rounded-pill text-white px-2 py-1 small" style="background-color: {{ $tag->color }}; font-size: 0.75rem;">
                                <i class="bi bi-tag-fill me-1"></i>{{ $tag->name }}
                            </span>
                        @endforeach
                    @endif
                </div>
                <h1 class="h2 fw-extrabold text-dark mb-2">{{ $book->title }}</h1>
                <p class="text-muted fs-6 mb-3">
                    Karya <strong class="text-dark">{{ $book->author }}</strong>
                </p>

                <!-- Price & Stock Status -->
                <div class="p-3 bg-light rounded-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <span class="small text-muted d-block">Harga Resmi:</span>
                        <span class="display-6 fw-bold text-primary">{{ $book->formatted_price }}</span>
                    </div>
                    <div class="text-end">
                        <span class="small text-muted d-block">Status Ketersediaan:</span>
                        @if($book->stock > 0)
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i> Tersedia (Stok: {{ $book->stock }})
                            </span>
                        @else
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                <i class="bi bi-x-circle me-1"></i> Stok Habis
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Book Specifications Table -->
                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-3">Detail & Spesifikasi Buku:</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm small">
                            <tbody>
                                <tr>
                                    <th class="bg-light text-muted w-25">Kategori</th>
                                    <td>{{ $book->category->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted">Penulis</th>
                                    <td>{{ $book->author }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted">Penerbit</th>
                                    <td>{{ $book->publisher }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted">Tahun Terbit</th>
                                    <td>{{ $book->publication_year }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted">ISBN</th>
                                    <td>{{ $book->isbn ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add to Cart Form -->
                @if($book->stock > 0)
                    <form action="{{ route('cart.store') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        
                        <div class="row g-3 align-items-end">
                            <div class="col-sm-4">
                                <label class="form-label small fw-semibold text-muted">Jumlah Beli:</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button" onclick="decrementQty()">-</button>
                                    <input type="number" name="quantity" id="quantityInput" class="form-control text-center" value="1" min="1" max="{{ $book->stock }}" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="incrementQty({{ $book->stock }})">+</button>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm rounded-pill">
                                    <i class="bi bi-cart-plus me-2"></i> Tambahkan ke Keranjang
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Buku ini sedang habis stok. Silakan hubungi admin melalui menu <a href="{{ route('contact') }}" class="alert-link">Kontak Kami</a> untuk informasi cetak ulang / restock.
                    </div>
                @endif

                <!-- Payment & Shipping Guarantee Cards -->
                <div class="p-3 border rounded-3 bg-light">
                    <div class="row g-2 small text-secondary">
                        <div class="col-sm-6 d-flex align-items-center gap-2">
                            <i class="bi bi-wallet2 fs-5 text-success"></i>
                            <span>Pembayaran Aman (QRIS & E-Wallet)</span>
                        </div>
                        <div class="col-sm-6 d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check fs-5 text-primary"></i>
                            <span>Jaminan 100% Produk Buku Original</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <!-- Synopsis / Description -->
        <div>
            <h4 class="fw-bold text-dark mb-3"><i class="bi bi-card-text text-primary me-2"></i>Sinopsis & Deskripsi Buku</h4>
            <div class="text-secondary leading-relaxed fs-6">
                @if($book->description)
                    {!! nl2br(e($book->description)) !!}
                @else
                    <p class="text-muted italic">Belum ada deskripsi lengkap untuk buku ini.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Books -->
    @if($relatedBooks->isNotEmpty())
        <div class="mt-5">
            <h4 class="fw-bold text-dark mb-4">Buku Serupa di Kategori {{ $book->category->name }}</h4>
            <div class="row g-4">
                @foreach($relatedBooks as $rel)
                    <div class="col-lg-3 col-md-6">
                        <div class="card h-100 card-hover border-0 shadow-sm overflow-hidden bg-white">
                            <a href="{{ route('books.show', $rel->slug) }}">
                                <div class="book-cover-wrapper">
                                    <img src="{{ $rel->cover_url }}" onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';" alt="{{ $rel->title }}" class="book-cover-img" loading="lazy">
                                </div>
                            </a>
                            <div class="card-body d-flex flex-column p-3">
                                <small class="text-muted mb-1">{{ $rel->author }}</small>
                                <h6 class="card-title fw-bold text-dark mb-2 text-truncate">
                                    <a href="{{ route('books.show', $rel->slug) }}" class="text-decoration-none text-dark">
                                        {{ $rel->title }}
                                    </a>
                                </h6>
                                <div class="mt-auto d-flex justify-content-between align-items-center pt-2">
                                    <span class="fw-bold text-primary">{{ $rel->formatted_price }}</span>
                                    <a href="{{ route('books.show', $rel->slug) }}" class="btn btn-outline-primary btn-sm">Lihat</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function incrementQty(max) {
        const input = document.getElementById('quantityInput');
        let val = parseInt(input.value) || 1;
        if (val < max) {
            input.value = val + 1;
        }
    }
    function decrementQty() {
        const input = document.getElementById('quantityInput');
        let val = parseInt(input.value) || 1;
        if (val > 1) {
            input.value = val - 1;
        }
    }
</script>
@endsection
