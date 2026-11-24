@extends('layouts.app')

@section('title', 'Tentang Kami - Toko Buku Aksara')

@section('content')
<!-- Header Page -->
<div class="bg-white border-bottom py-5">
    <div class="container text-center">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-2">
            Profil Perusahaan
        </span>
        <h1 class="fw-extrabold text-dark display-5 mb-3">Tentang Toko Buku Aksara</h1>
        <p class="lead text-muted mx-auto" style="max-width: 650px;">
            Menyediakan buku-buku bermutu dan original untuk mencerdaskan generasi bangsa dengan kemudahan belanja dan pengiriman ke seluruh nusantara.
        </p>
    </div>
</div>

<div class="container py-5">
    <!-- Story Section -->
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
            <h6 class="text-primary fw-bold text-uppercase">Sejarah & Dedikasi</h6>
            <h2 class="fw-bold mb-4">Menghubungkan Pembaca dengan Gudang Pengetahuan</h2>
            <p class="text-secondary leading-relaxed">
                Didirikan pada tahun 2021, <strong>Toko Buku Aksara</strong> hadir sebagai wujud nyata dedikasi kami dalam menghidupkan budaya literasi di Indonesia. Berawal dari toko buku komunitas di Matraman, Jakarta Timur, kini Aksara berkembang pesat menjadi platform belanja buku terpercaya yang melayani ribuan pembaca dari Sabang sampai Merauke.
            </p>
            <p class="text-secondary leading-relaxed">
                Kami bermitra langsung dengan puluhan penerbit terkemuka nasional maupun internasional untuk menjamin setiap buku yang tiba di tangan pembaca adalah cetakan resmi 100% original.
            </p>
            <div class="row g-3 pt-3">
                <div class="col-sm-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <h4 class="fw-bold text-primary mb-1">65.000+</h4>
                        <span class="text-muted small">Buku Terdistribusi</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <h4 class="fw-bold text-success mb-1">99.9%</h4>
                        <span class="text-muted small">Tingkat Kepuasan</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=700&q=80" 
                     onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';"
                     alt="Toko Buku Aksara" class="img-fluid rounded-4 shadow">
            </div>
        </div>
    </div>

    <!-- Vision & Mission -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card h-100 p-4 border-0 shadow-sm bg-primary text-white">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-white text-primary p-3 rounded-circle">
                        <i class="bi bi-eye-fill fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-0">Visi Kami</h3>
                </div>
                <p class="fs-5 text-light text-opacity-90 leading-relaxed mb-0">
                    Menjadi toko buku digital pilihan utama di Indonesia yang menginspirasi, membuka wawasan, dan mempermudah akses bahan bacaan berkualitas bagi seluruh kalangan.
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 p-4 border-0 shadow-sm bg-white">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-warning text-dark p-3 rounded-circle">
                        <i class="bi bi-bullseye fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">Misi Kami</h3>
                </div>
                <ul class="text-secondary d-flex flex-column gap-2 mb-0 ps-3">
                    <li>Menyediakan kurasi buku cetak original terlengkap dari berbagai genre dan disiplin ilmu.</li>
                    <li>Memberikan kepastian belanja aman melalui beragam metode pembayaran, termasuk QRIS instan, E-Wallet (GoPay, OVO, DANA), dan Transfer Bank resmi.</li>
                    <li>Menawarkan harga yang transparan dan kompetitif bagi seluruh lapisan masyarakat.</li>
                    <li>Mendukung ekosistem kepenulisan nasional dengan mengapresiasi hak cipta para pengarang.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Store Info & Hours -->
    <div class="card border-0 shadow-sm p-4 p-md-5 bg-white">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <h4 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Kantor & Gerai Fisik Kami</h4>
                <p class="text-secondary mb-3">
                    Kunjungi toko buku fisik kami untuk menikmati atmosfer membaca buku yang tenang:
                </p>
                <div class="d-flex flex-column gap-2 text-secondary mb-4">
                    <div><strong>Alamat:</strong> Jl. Matraman Raya No. 42, Kebon Manggis, Jakarta Timur 13150</div>
                    <div><strong>WhatsApp CS:</strong> +62 812-9876-5432</div>
                    <div><strong>Email:</strong> info@aksarabookstore.com / support@aksarabookstore.com</div>
                    <div><strong>Jam Operasional:</strong> Senin – Sabtu: 08:00 – 20:00 WIB (Minggu Libur)</div>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary fw-semibold rounded-pill px-4">
                    <i class="bi bi-envelope me-1"></i> Hubungi Customer Service
                </a>
            </div>
            <div class="col-lg-6">
                <div class="p-4 bg-light rounded-4 border">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-patch-check-fill text-success me-2"></i>Komitmen Pelayanan Toko Buku Aksara</h5>
                    <div class="d-flex flex-column gap-3 small text-secondary">
                        <div class="d-flex gap-3">
                            <i class="bi bi-box-seam fs-4 text-primary"></i>
                            <div>
                                <strong class="text-dark d-block">Pengemasan Ekstra Aman</strong>
                                Setiap buku dibungkus dengan plastik bubble wrap berlapis dan kardus tebal tahan benturan.
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <i class="bi bi-arrow-repeat fs-4 text-warning"></i>
                            <div>
                                <strong class="text-dark d-block">Garansi Ganti Baru</strong>
                                Jika buku cacat cetak atau rusak saat perjalanan, kami tukar dengan yang baru tanpa biaya tambahan.
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <i class="bi bi-wallet2 fs-4 text-success"></i>
                            <div>
                                <strong class="text-dark d-block">Kemudahan Pembayaran Digital</strong>
                                Nikmati beragam opsi transaksi instan via QRIS, e-wallet, dan transfer bank terpercaya.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
