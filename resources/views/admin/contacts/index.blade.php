@extends('layouts.admin')

@section('title', 'Pesan Masuk (Kontak Pengunjung)')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1"><i class="bi bi-envelope-paper text-primary me-2"></i>Pesan Masuk (Contact to Admin)</h2>
        <p class="text-muted small mb-0">Daftar pertanyaan, pesan saran, dan percakapan interaktif dengan pelanggan.</p>
    </div>
</div>

<div class="card border-0 shadow-sm bg-white overflow-hidden mb-4">
    <div class="p-3 border-bottom bg-light">
        <form action="{{ route('admin.contacts.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama, email, subjek, isi pesan...">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    @if(request('q'))
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-muted text-uppercase">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Pengirim</th>
                    <th>Subjek & Percakapan</th>
                    <th>Waktu</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $index => $c)
                    <tr class="{{ !$c->is_read ? 'table-light' : '' }}">
                        <td>{{ $contacts->firstItem() + $index }}</td>
                        <td>
                            <strong class="text-dark">{{ $c->name }}</strong>
                            <div class="small text-muted"><i class="bi bi-envelope me-1"></i>{{ $c->email }}</div>
                            @if($c->user)
                                <span class="badge bg-light text-primary border small">Pelanggan Terdaftar</span>
                            @else
                                <span class="badge bg-light text-muted border small">Tamu Web</span>
                            @endif
                        </td>
                        <td style="max-width: 380px;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-dark">{{ $c->subject }}</span>
                                @if($c->replies->count() > 0)
                                    <span class="badge bg-info-subtle text-info border small">
                                        <i class="bi bi-chat-dots-fill me-1"></i>{{ $c->replies->count() }} Balasan
                                    </span>
                                @endif
                            </div>
                            <p class="text-secondary small mb-0 text-truncate" style="max-width: 360px;">
                                {{ $c->message }}
                            </p>
                            @if($c->replies->isNotEmpty())
                                <small class="text-primary d-block mt-1">
                                    <i class="bi bi-reply-fill me-1"></i>Terakhir dibalas: {{ $c->replies->last()->created_at->diffForHumans() }}
                                </small>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $c->created_at->format('d M Y, H:i') }}<br>
                            <span style="font-size: 0.75rem;">({{ $c->created_at->diffForHumans() }})</span>
                        </td>
                        <td class="text-center">
                            @if($c->is_read)
                                <span class="badge bg-success"><i class="bi bi-check2-all me-1"></i>Sudah Dibaca</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-envelope-fill me-1"></i>Belum Dibaca</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <!-- Tombol Buka Percakapan / Balas -->
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-2" 
                                        data-bs-toggle="modal" data-bs-target="#chatModal-{{ $c->id }}"
                                        title="Buka Chat & Balas Pesan">
                                    <i class="bi bi-chat-text-fill me-1"></i> Balas
                                </button>

                                @if(!$c->is_read)
                                    <form action="{{ route('admin.contacts.markAsRead', $c->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Tandai Sudah Dibaca">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.contacts.destroy', $c->id) }}" method="POST" id="del-contact-{{ $c->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete('del-contact-{{ $c->id }}', 'Hapus pesan dari {{ addslashes($c->name) }}?')" 
                                            title="Hapus Pesan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL CHAT PERCAKAPAN & BALAS PESAN -->
                    <div class="modal fade" id="chatModal-{{ $c->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light">
                                    <div>
                                        <h6 class="modal-title fw-bold text-dark mb-0">
                                            <i class="bi bi-chat-left-dots-fill text-primary me-2"></i>Percakapan: {{ $c->subject }}
                                        </h6>
                                        <small class="text-muted">Pengirim: <strong>{{ $c->name }}</strong> ({{ $c->email }})</small>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4" style="background-color: #f8fafc; max-height: 480px; overflow-y: auto;">
                                    <!-- Pesan Asli dari User -->
                                    <div class="d-flex align-items-start gap-2 mb-3">
                                        <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="bg-white p-3 rounded-4 shadow-sm border" style="max-width: 80%;">
                                            <div class="d-flex justify-content-between align-items-baseline gap-3 mb-1">
                                                <strong class="text-dark small">{{ $c->name }} (Pertanyaan Awal)</strong>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $c->created_at->format('d M, H:i') }}</span>
                                            </div>
                                            <p class="text-secondary small mb-0" style="white-space: pre-line;">{{ $c->message }}</p>
                                        </div>
                                    </div>

                                    <!-- Daftar Balasan / Thread Percakapan -->
                                    @foreach($c->replies as $reply)
                                        @if($reply->isAdmin())
                                            <!-- Balasan dari Admin (Rata Kanan) -->
                                            <div class="d-flex align-items-start gap-2 justify-content-end mb-3">
                                                <div class="p-3 rounded-4 shadow-sm text-white" style="max-width: 80%; background-color: #4f46e5;">
                                                    <div class="d-flex justify-content-between align-items-baseline gap-3 mb-1">
                                                        <strong class="text-white small"><i class="bi bi-shield-check me-1"></i>Admin Aksara (Anda)</strong>
                                                        <span class="text-white-50 small" style="font-size: 0.75rem;">{{ $reply->created_at->format('d M, H:i') }}</span>
                                                    </div>
                                                    <p class="small mb-0" style="white-space: pre-line;">{{ $reply->message }}</p>
                                                </div>
                                                <div class="bg-dark text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                    <i class="bi bi-shield-lock-fill text-warning"></i>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Balasan dari User (Rata Kiri) -->
                                            <div class="d-flex align-items-start gap-2 mb-3">
                                                <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div class="bg-white p-3 rounded-4 shadow-sm border" style="max-width: 80%;">
                                                    <div class="d-flex justify-content-between align-items-baseline gap-3 mb-1">
                                                        <strong class="text-dark small">{{ $c->name }}</strong>
                                                        <span class="text-muted small" style="font-size: 0.75rem;">{{ $reply->created_at->format('d M, H:i') }}</span>
                                                    </div>
                                                    <p class="text-secondary small mb-0" style="white-space: pre-line;">{{ $reply->message }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <!-- Form Balas Pesan -->
                                <div class="modal-footer bg-white p-3">
                                    <form action="{{ route('admin.contacts.reply', $c->id) }}" method="POST" class="w-100">
                                        @csrf
                                        <label class="form-label small fw-bold text-dark mb-1">
                                            <i class="bi bi-reply-fill text-primary me-1"></i>Tulis Balasan Pesan:
                                        </label>
                                        <div class="input-group">
                                            <textarea name="message" rows="2" class="form-control" placeholder="Tuliskan jawaban atau konfirmasi untuk {{ $c->name }}..." required></textarea>
                                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                                <i class="bi bi-send-fill me-1"></i> Kirim
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Tidak ada pesan masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($contacts->hasPages())
        <div class="p-3 border-top d-flex justify-content-center">
            {{ $contacts->links() }}
        </div>
    @endif
</div>
@endsection
