@extends('layouts.admin')

@section('title', 'List User Terdaftar')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1"><i class="bi bi-people text-primary me-2"></i>Daftar Pengguna (User)</h2>
        <p class="text-muted small mb-0">List seluruh akun user / pelanggan dan administrator yang telah terdaftar di sistem.</p>
    </div>
</div>

<div class="card border-0 shadow-sm bg-white overflow-hidden mb-4">
    <!-- Filter Bar -->
    <div class="p-3 border-bottom bg-light">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama, email, nomor HP...">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Role --</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User (Pelanggan)</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>
            <div class="col-md-4 text-md-end">
                @if(request('q') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
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
                    <th>Nama & Email Pengguna</th>
                    <th>Kontak Telepon</th>
                    <th>Alamat Lengkap</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">Total Pesanan</th>
                    <th>Tgl Bergabung</th>
                    <th class="text-center" style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $u)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>
                            <strong class="text-dark">{{ $u->name }}</strong>
                            <div class="small text-muted"><i class="bi bi-envelope me-1"></i>{{ $u->email }}</div>
                        </td>
                        <td class="small">
                            {{ $u->phone ?? '-' }}
                        </td>
                        <td class="small text-muted" style="max-width: 250px;">
                            {{ $u->address ?? '-' }}
                        </td>
                        <td class="text-center">
                            @if($u->role === 'admin')
                                <span class="badge bg-danger"><i class="bi bi-shield-lock-fill me-1"></i>Admin</span>
                            @else
                                <span class="badge bg-primary">Pelanggan</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $u->orders_count }} Pesanan</span>
                        </td>
                        <td class="small text-muted">
                            {{ $u->created_at->format('d M Y') }}
                        </td>
                        <td class="text-center">
                            @if($u->id !== Auth::id())
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" id="del-user-{{ $u->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete('del-user-{{ $u->id }}', 'Hapus user: {{ addslashes($u->name) }}?')" 
                                            title="Hapus Akun User">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-light text-muted small">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Belum ada pengguna yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="p-3 border-top d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
