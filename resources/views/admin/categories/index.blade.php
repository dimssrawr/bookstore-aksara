@extends('layouts.admin')

@section('title', 'Manajemen Kategori Buku')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1"><i class="bi bi-tags text-primary me-2"></i>Kategori Buku</h2>
        <p class="text-muted small mb-0">Kelola kategori untuk mengelompokkan koleksi buku di toko.</p>
    </div>
    <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold mt-3 mt-sm-0" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori Baru
    </button>
</div>

<div class="card border-0 shadow-sm bg-white overflow-hidden mb-4">
    <div class="p-3 border-bottom bg-light">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau deskripsi kategori...">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    @if(request('q'))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-muted text-uppercase">
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Slug URL</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Jumlah Buku</th>
                    <th class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $cat)
                    <tr>
                        <td>{{ $categories->firstItem() + $index }}</td>
                        <td>
                            <strong class="text-dark">{{ $cat->name }}</strong>
                        </td>
                        <td>
                            <code class="text-primary">{{ $cat->slug }}</code>
                        </td>
                        <td class="small text-muted" style="max-width: 300px;">
                            {{ $cat->description ?? '-' }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary rounded-pill">{{ $cat->books_count }} Buku</span>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}')" 
                                        title="Edit Kategori">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" id="del-cat-{{ $cat->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete('del-cat-{{ $cat->id }}', 'Hapus kategori {{ $cat->name }}?')" 
                                            title="Hapus Kategori">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Belum ada data kategori buku yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="p-3 border-top d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    @endif
</div>

<!-- Modal Create Category -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Pemrograman & Komputer" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Deskripsi Kategori (Opsional)</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Ringkasan penjelasan kategori..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm fw-bold">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Category -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCategoryForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark">Edit Kategori Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="editCategoryName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Deskripsi Kategori</label>
                    <textarea name="description" id="editCategoryDesc" rows="3" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm fw-bold">Perbarui Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function editCategory(id, name, desc) {
        const form = document.getElementById('editCategoryForm');
        form.action = `/admin/categories/${id}`;
        document.getElementById('editCategoryName').value = name;
        document.getElementById('editCategoryDesc').value = desc;

        const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        modal.show();
    }
</script>
@endsection
