@extends('layouts.admin')

@section('title', 'Tambah Buku Baru')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">Tambah Buku Baru</h2>
        <p class="text-muted small mb-0">Isi formulir di bawah ini untuk menambahkan data buku ke dalam katalog toko.</p>
    </div>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Buku
    </a>
</div>

<div class="card border-0 shadow-sm bg-white p-4 p-md-5">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kesalahan Pengisian:</h6>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Left Column: Primary Details -->
            <div class="col-lg-8">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                           placeholder="Contoh: Belajar Pemrograman Web Modern" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Kategori Buku <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Penulis / Pengarang <span class="text-danger">*</span></label>
                        <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" 
                               placeholder="Nama penulis" value="{{ old('author') }}" required>
                        @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold text-secondary">Penerbit <span class="text-danger">*</span></label>
                        <input type="text" name="publisher" class="form-control @error('publisher') is-invalid @enderror" 
                               placeholder="Nama penerbit" value="{{ old('publisher') }}" required>
                        @error('publisher')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-secondary">Tahun Terbit <span class="text-danger">*</span></label>
                        <input type="number" name="publication_year" class="form-control @error('publication_year') is-invalid @enderror" 
                               value="{{ old('publication_year', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                        @error('publication_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Nomor ISBN</label>
                        <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror" 
                               placeholder="Contoh: 978-602-..." value="{{ old('isbn') }}">
                        @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Harga Buku (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                   placeholder="0" value="{{ old('price') }}" min="0" step="1000" required>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                               placeholder="0" value="{{ old('stock', 10) }}" min="0" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Sinopsis / Deskripsi Buku</label>
                    <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" 
                              placeholder="Tuliskan ulasan atau sinopsis ringkas tentang buku ini...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-dark" for="isFeatured">
                        Tampilkan sebagai Buku Unggulan (Featured) di Halaman Depan
                    </label>
                </div>
            </div>

            <!-- Right Column: Image Upload & Preview -->
            <div class="col-lg-4">
                <div class="card bg-light border p-3 text-center">
                    <label class="form-label fw-bold text-dark mb-2">Sampul Buku (Cover Image)</label>
                    <p class="small text-muted mb-3">Format JPG, JPEG, PNG, atau WEBP. Maks 2 MB.</p>
                    
                    <div class="mb-3 mx-auto" style="width: 180px; height: 250px; background: #e2e8f0; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                        <img id="coverPreview" src="{{ asset('images/default-book.svg') }}" 
                             alt="Preview Sampul" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>

                    <input type="file" name="cover_image" id="coverImageInput" class="form-control form-control-sm @error('cover_image') is-invalid @enderror" accept="image/*" onchange="previewImage(this)">
                    @error('cover_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12 mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill">
                    <i class="bi bi-save me-1"></i> Simpan Buku
                </button>
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('coverPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
