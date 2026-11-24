@extends('layouts.admin')

@section('title', 'Manajemen Data Buku')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1"><i class="bi bi-journal-album text-primary me-2"></i>Data Buku</h2>
        <p class="text-muted small mb-0">Kelola informasi katalog buku, harga, stok, dan gambar sampul.</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold mt-3 mt-sm-0">
        <i class="bi bi-plus-lg me-1"></i> Tambah Buku Baru
    </a>
</div>

<div class="card border-0 shadow-sm bg-white overflow-hidden mb-4">
    <!-- Filter & Search Bar -->
    <div class="p-3 border-bottom bg-light">
        <form action="{{ route('admin.books.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul, penulis, penerbit, ISBN...">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                </div>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 text-md-end">
                @if(request('q') || request('category_id'))
                    <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-muted text-uppercase">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 70px;">Sampul</th>
                    <th>Judul Buku & Penulis</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $index => $book)
                    <tr>
                        <td>{{ $books->firstItem() + $index }}</td>
                        <td>
                            <img src="{{ $book->cover_url }}" onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';" alt="{{ $book->title }}" 
                                 class="rounded shadow-sm" style="width: 48px; height: 68px; object-fit: cover;">
                        </td>
                        <td>
                            <a href="{{ route('books.show', $book->slug) }}" target="_blank" class="fw-bold text-dark text-decoration-none">
                                {{ $book->title }}
                            </a>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-person me-1"></i>{{ $book->author }} &bull; {{ $book->publisher }} ({{ $book->publication_year }})
                            </div>
                            @if($book->isbn)
                                <div class="small text-muted">ISBN: <code>{{ $book->isbn }}</code></div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border">{{ $book->category->name }}</span>
                        </td>
                        <td class="fw-bold text-dark">
                            {{ $book->formatted_price }}
                        </td>
                        <td class="text-center">
                            @if($book->stock > 5)
                                <span class="badge bg-success">{{ $book->stock }} unit</span>
                            @elseif($book->stock > 0)
                                <span class="badge bg-warning text-dark">{{ $book->stock }} unit</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($book->is_featured)
                                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Unggulan</span>
                            @else
                                <span class="badge bg-light text-muted border">Reguler</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Buku">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" id="del-book-{{ $book->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete('del-book-{{ $book->id }}', 'Hapus buku: {{ addslashes($book->title) }}?')" 
                                            title="Hapus Buku">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Tidak ada data buku yang sesuai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($books->hasPages())
        <div class="p-3 border-top d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    @endif
</div>
@endsection
