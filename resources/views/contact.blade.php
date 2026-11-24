@extends('layouts.app')

@section('title', 'Hubungi Admin - Toko Buku Aksara')

@section('content')
<div class="bg-white border-bottom py-5">
    <div class="container text-center">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-2">
            Bantuan & Layanan Pelanggan
        </span>
        <h1 class="fw-extrabold text-dark display-5 mb-2">Hubungi Admin Aksara</h1>
        <p class="lead text-muted mx-auto" style="max-width: 600px;">
            Punya pertanyaan mengenai stok buku, status pemesanan, atau permohonan kerjasama? Kirim pesan langsung kepada tim admin kami.
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 p-md-5 bg-white rounded-4">
                <h4 class="fw-bold text-dark mb-4">
                    <i class="bi bi-chat-left-text text-primary me-2"></i>Formulir Kontak Admin
                </h4>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <strong>Perhatian:</strong>
                        <ul class="mb-0 mt-1 small">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="Masukkan nama Anda" 
                                   value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   placeholder="nama@email.com" 
                                   value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">Subjek Pesan <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                                   placeholder="Contoh: Tanya ketersediaan buku / Request pengadaan buku" 
                                   value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">Isi Pesan <span class="text-danger">*</span></label>
                            <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" 
                                      placeholder="Tuliskan pesan, pertanyaan, atau masukan Anda secara rinci..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm rounded-pill">
                                <i class="bi bi-send-fill me-2"></i> Kirim Pesan ke Admin
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Information Column -->
        <div class="col-lg-5">
            <div class="d-flex flex-column gap-4">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-4">
                    <h5 class="fw-bold text-dark mb-3">Informasi Kontak Langsung</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                                <i class="bi bi-geo-alt-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Alamat Toko</h6>
                                <p class="text-muted small mb-0">Jl. Matraman Raya No. 42, Kebon Manggis, Jakarta Timur 13150</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                                <i class="bi bi-whatsapp fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">WhatsApp CS</h6>
                                <p class="text-muted small mb-0">+62 812-9876-5432 (Fast Response)</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                                <i class="bi bi-envelope-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Email Resmi</h6>
                                <p class="text-muted small mb-0">admin@bookstore.com / support@aksara.com</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle">
                                <i class="bi bi-clock-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Waktu Operasional</h6>
                                <p class="text-muted small mb-0">Senin - Sabtu: 08.00 - 20.00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 bg-light rounded-4">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-chat-dots-fill text-primary me-2"></i>Fitur Pesan Dua Arah</h6>
                    <p class="small text-secondary mb-0">
                        Admin Toko Buku Aksara dapat langsung membalas pertanyaan Anda. Jika Anda login, riwayat percakapan dan balasan dari admin akan tampil langsung di bawah halaman ini.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Chat Thread (Percakapan Balas-balasan) -->
    @auth
        @if(isset($userMessages) && $userMessages->isNotEmpty())
            <div class="mt-5 pt-4 border-top">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="bi bi-chat-dots-fill text-primary me-2"></i>Percakapan & Balasan dari Admin
                        </h4>
                        <p class="text-muted small mb-0">Lihat balasan tim admin dan lanjutkan percakapan langsung di sini.</p>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">{{ $userMessages->count() }} Percakapan</span>
                </div>

                <div class="row g-4">
                    @foreach($userMessages as $msg)
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-4 bg-white h-100 overflow-hidden">
                                <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                                    <div class="fw-bold text-dark small text-truncate" style="max-width: 320px;">
                                        <i class="bi bi-chat-left-text me-1 text-primary"></i>{{ $msg->subject }}
                                    </div>
                                    <span class="badge {{ $msg->replies->count() > 0 ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill small">
                                        {{ $msg->replies->count() > 0 ? $msg->replies->count() . ' Balasan' : 'Menunggu Balasan' }}
                                    </span>
                                </div>
                                <div class="card-body p-3" style="background-color: #f8fafc; max-height: 320px; overflow-y: auto;">
                                    <!-- Pesan Awal Pengguna -->
                                    <div class="d-flex align-items-start gap-2 mb-3 justify-content-end">
                                        <div class="p-3 rounded-4 shadow-sm text-white" style="max-width: 85%; background-color: #4f46e5;">
                                            <div class="d-flex justify-content-between gap-3 mb-1 small text-white-50" style="font-size: 0.72rem;">
                                                <span>Anda (Pertanyaan Awal)</span>
                                                <span>{{ $msg->created_at->format('d M, H:i') }}</span>
                                            </div>
                                            <p class="small mb-0" style="white-space: pre-line;">{{ $msg->message }}</p>
                                        </div>
                                        <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    </div>

                                    <!-- Balasan-balasan Selanjutnya -->
                                    @forelse($msg->replies as $r)
                                        @if($r->isAdmin())
                                            <!-- Balasan Admin (Sebelah Kiri) -->
                                            <div class="d-flex align-items-start gap-2 mb-3">
                                                <div class="bg-dark text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    <i class="bi bi-shield-check text-warning"></i>
                                                </div>
                                                <div class="bg-white p-3 rounded-4 shadow-sm border" style="max-width: 85%;">
                                                    <div class="d-flex justify-content-between gap-3 mb-1 small text-muted" style="font-size: 0.72rem;">
                                                        <strong class="text-primary"><i class="bi bi-shield-check me-1"></i>Admin Aksara</strong>
                                                        <span>{{ $r->created_at->format('d M, H:i') }}</span>
                                                    </div>
                                                    <p class="small mb-0 text-dark" style="white-space: pre-line;">{{ $r->message }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Balasan User Lanjutan (Sebelah Kanan) -->
                                            <div class="d-flex align-items-start gap-2 mb-3 justify-content-end">
                                                <div class="p-3 rounded-4 shadow-sm text-white" style="max-width: 85%; background-color: #4f46e5;">
                                                    <div class="d-flex justify-content-between gap-3 mb-1 small text-white-50" style="font-size: 0.72rem;">
                                                        <span>Anda</span>
                                                        <span>{{ $r->created_at->format('d M, H:i') }}</span>
                                                    </div>
                                                    <p class="small mb-0" style="white-space: pre-line;">{{ $r->message }}</p>
                                                </div>
                                                <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center py-3 text-muted small fst-italic">
                                            <i class="bi bi-hourglass-split me-1"></i>Admin belum membalas pesan ini. Tim kami akan segera merespons!
                                        </div>
                                    @endforelse
                                </div>
                                <div class="card-footer bg-white p-2">
                                    <form action="{{ route('contact.reply', $msg->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="message" class="form-control" placeholder="Tulis balasan pesan untuk Admin..." required>
                                            <button type="submit" class="btn btn-primary fw-bold px-3">
                                                <i class="bi bi-send-fill me-1"></i> Balas
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endauth
</div>
@endsection
