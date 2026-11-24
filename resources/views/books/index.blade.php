@extends('layouts.app')

@section('title', 'Katalog Buku Lengkap')

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 fw-bold text-dark mb-1">Katalog Koleksi Buku</h1>
                <p class="text-muted small mb-0">
                    Menampilkan {{ $books->total() }} judul buku pilihan
                    @if(request('q'))
                        untuk pencarian "<strong>{{ request('q') }}</strong>"
                    @endif
                    @if($activeCategory)
                        dalam kategori "<strong>{{ $activeCategory->name }}</strong>"
                    @endif
                </p>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <form action="{{ route('books.index') }}" method="GET" class="d-flex gap-2">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul, penulis, ISBN...">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm p-4 bg-white sticky-top" style="top: 90px; z-index: 10;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-funnel me-2"></i>Filter & Kategori</h6>
                    @if(request('category') || request('q') || request('sort'))
                        <a href="{{ route('books.index') }}" class="text-danger small text-decoration-none">Reset</a>
                    @endif
                </div>

                <div class="list-group list-group-flush mb-4">
                    <a href="{{ route('books.index', ['q' => request('q'), 'sort' => request('sort')]) }}" 
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-2 py-2 border-0 rounded-2 {{ !request('category') ? 'active bg-primary text-white' : '' }}">
                        <span>Semua Kategori</span>
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('books.index', ['category' => $cat->slug, 'q' => request('q'), 'sort' => request('sort')]) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-2 py-2 border-0 rounded-2 {{ request('category') === $cat->slug ? 'active bg-primary text-white' : '' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="badge {{ request('category') === $cat->slug ? 'bg-light text-primary' : 'bg-light text-secondary' }} rounded-pill">
                                {{ $cat->books_count }}
                            </span>
                        </a>
                    @endforeach
                </div>

                <!-- Sorting Filter -->
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-sort-down me-2"></i>Urutkan Menurut</h6>
                <form action="{{ route('books.index') }}" method="GET">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul: A - Z</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="col-lg-9">
            @if($books->isEmpty())
                <div class="card border-0 shadow-sm p-5 text-center bg-white">
                    <div class="my-4">
                        <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                        <h4 class="fw-bold mt-3 text-dark">Buku Tidak Ditemukan</h4>
                        <p class="text-muted mx-auto" style="max-width: 450px;">
                            Maaf, tidak ada buku yang sesuai dengan kriteria pencarian Anda. Silakan coba kata kunci lain atau bersihkan filter.
                        </p>
                        <a href="{{ route('books.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                            Lihat Semua Koleksi Buku
                        </a>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($books as $book)
                        <div class="col-xl-4 col-md-6">
                            <div class="card h-100 card-hover border-0 shadow-sm overflow-hidden bg-white">
                                <div class="position-relative">
                                    <a href="{{ route('books.show', $book->slug) }}">
                                        <div class="book-cover-wrapper">
                                            <img src="{{ $book->cover_url }}" onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';" alt="{{ $book->title }}" class="book-cover-img" loading="lazy">
                                        </div>
                                    </a>
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-primary shadow-sm">
                                        {{ $book->category->name }}
                                    </span>
                                    @if($book->is_featured)
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark shadow-sm">
                                            <i class="bi bi-star-fill me-1"></i> Unggulan
                                        </span>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <small class="text-muted mb-1"><i class="bi bi-person me-1"></i>{{ $book->author }}</small>
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

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
