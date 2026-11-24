@extends('layouts.app')

@section('title', 'Checkout Pembayaran - Toko Buku Aksara')

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h1 class="h3 fw-bold text-dark mb-1"><i class="bi bi-shield-check text-primary me-2"></i>Checkout & Pembayaran Pesanan</h1>
        <p class="text-muted small mb-0">Lengkapi alamat pengiriman dan pilih metode pembayaran untuk menyelesaikan pesanan Anda.</p>
    </div>
</div>

<div class="container pb-5">
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Left Column: Shipping & Payment Information -->
            <div class="col-lg-7">
                <!-- Shipping Address Card -->
                <div class="card border-0 shadow-sm p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-danger"></i>
                        <span>1. Data Penerima & Alamat Pengiriman</span>
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" 
                                   value="{{ old('customer_name', $user->name) }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                   value="{{ old('customer_phone', $user->phone) }}" placeholder="Contoh: 081234567890" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Alamat Lengkap Tujuan <span class="text-danger">*</span></label>
                            <textarea name="shipping_address" rows="3" class="form-control @error('shipping_address') is-invalid @enderror" 
                                      placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, kode pos" required>{{ old('shipping_address', $user->address) }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Catatan Khusus untuk Kurir (Opsional)</label>
                            <input type="text" name="notes" class="form-control" 
                                   value="{{ old('notes') }}" placeholder="Contoh: Titipkan di pos satpam / hubungi via WA saat sampai">
                        </div>
                    </div>
                </div>

                <!-- Payment Method Selection Card -->
                <div class="card border-0 shadow-sm p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-wallet2 text-success"></i>
                            <span>2. Metode Pembayaran</span>
                        </h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 py-1 px-2 small">
                            Payment at Delivery
                        </span>
                    </div>

                    <!-- Banner Penjelasan Sesuai Soal USK -->
                    <div class="alert alert-primary bg-primary bg-opacity-10 border-primary border-opacity-25 py-2 px-3 small rounded-3 mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-primary fs-4 flex-shrink-0"></i>
                        <div>
                            <strong>Payment at Delivery (Pembayaran Buku Sebelum Buku Dikirim):</strong>
                            <div class="text-secondary small mt-1">
                                Pembeli melakukan pembayaran via QRIS, E-Wallet, atau Transfer Bank terlebih dahulu agar pesanan dapat diverifikasi dan dikirimkan oleh kurir toko ke alamat tujuan.
                            </div>
                        </div>
                    </div>

                    <!-- Category 1: E-Wallet & QRIS -->
                    <div class="small fw-bold text-uppercase text-muted mb-2"><i class="bi bi-phone me-1 text-primary"></i>Dompet Digital (E-Wallet) & QRIS</div>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <!-- QRIS -->
                        <div class="border rounded-3 p-3 bg-white">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payQRIS" 
                                       value="QRIS (GoPay, OVO, DANA, ShopeePay)" checked>
                                <label class="form-check-label w-100 ms-2" for="payQRIS">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark"><i class="bi bi-qr-code text-danger me-1"></i>QRIS Universal</strong>
                                        <span class="badge bg-danger">Otomatis / Bebas Biaya</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Scan kode QRIS instan mendukung semua aplikasi: GoPay, OVO, DANA, ShopeePay, LinkAja, & Mobile Banking.
                                    </small>
                                </label>
                            </div>
                        </div>

                        <!-- GoPay -->
                        <div class="border rounded-3 p-3 bg-white">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payGoPay" 
                                       value="GoPay">
                                <label class="form-check-label w-100 ms-2" for="payGoPay">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark"><i class="bi bi-wallet-fill text-info me-1"></i>GoPay</strong>
                                        <span class="badge bg-info text-dark">Populer</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Pembayaran via aplikasi Gojek / GoPay ke nomor resmi: <strong>0812-9876-5432</strong>
                                    </small>
                                </label>
                            </div>
                        </div>

                        <!-- DANA -->
                        <div class="border rounded-3 p-3 bg-white">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payDANA" 
                                       value="DANA">
                                <label class="form-check-label w-100 ms-2" for="payDANA">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark"><i class="bi bi-credit-card-fill text-primary me-1"></i>DANA</strong>
                                        <span class="badge bg-primary">Instant</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Transfer saldo DANA ke nomor akun toko: <strong>0812-9876-5432</strong>
                                    </small>
                                </label>
                            </div>
                        </div>

                        <!-- OVO -->
                        <div class="border rounded-3 p-3 bg-white">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payOVO" 
                                       value="OVO">
                                <label class="form-check-label w-100 ms-2" for="payOVO">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>OVO Cash</strong>
                                        <span class="badge bg-warning text-dark">Instant</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Transfer OVO ke nomor akun toko: <strong>0812-9876-5432</strong>
                                    </small>
                                </label>
                            </div>
                        </div>

                        <!-- ShopeePay -->
                        <div class="border rounded-3 p-3 bg-white">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payShopee" 
                                       value="ShopeePay">
                                <label class="form-check-label w-100 ms-2" for="payShopee">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark"><i class="bi bi-bag-heart-fill text-danger me-1"></i>ShopeePay</strong>
                                        <span class="badge bg-secondary">Tersedia</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Transfer saldo ShopeePay ke akun merchant Toko Buku Aksara.
                                    </small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Category 3: Bank Transfer -->
                    <div class="small fw-bold text-uppercase text-muted mb-2"><i class="bi bi-bank me-1 text-primary"></i>Transfer Bank (Sebelum Dikirim)</div>
                    <div class="d-flex flex-column gap-2">
                        <!-- BCA -->
                        <div class="border rounded-3 p-3 bg-white">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payBCA" 
                                       value="Bank BCA (Virtual Account / Transfer)">
                                <label class="form-check-label w-100 ms-2" for="payBCA">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark"><i class="bi bi-building me-1 text-primary"></i>Bank Central Asia (BCA)</strong>
                                        <span class="badge bg-light text-primary border">No. Rek: 8829-1029-338</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Transfer via BCA Mobile / KlikBCA / ATM sebelum pesanan kami proses & kirimkan.
                                    </small>
                                </label>
                            </div>
                        </div>

                        <!-- Mandiri / BRI / BNI -->
                        <div class="border rounded-3 p-3 bg-white">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payOtherBank" 
                                       value="Bank Mandiri / BRI / BNI">
                                <label class="form-check-label w-100 ms-2" for="payOtherBank">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark"><i class="bi bi-bank2 me-1 text-secondary"></i>Bank Mandiri / BRI / BNI</strong>
                                        <span class="badge bg-light text-secondary border">ATM & M-Banking</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Transfer antar bank atau sesama bank melalui nomor rekening resmi toko buku.
                                    </small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Items Summary & Submit -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4 bg-white sticky-top" style="top: 90px;">
                    <h5 class="fw-bold text-dark mb-3">Ringkasan Pesanan</h5>

                    <div class="d-flex flex-column gap-3 mb-4" style="max-height: 280px; overflow-y: auto;">
                        @foreach($carts as $cart)
                            <div class="d-flex align-items-center gap-3 pb-2 border-bottom">
                                <img src="{{ $cart->book->cover_url }}" onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';" alt="{{ $cart->book->title }}" 
                                     class="rounded" style="width: 48px; height: 65px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="small fw-bold text-dark mb-0 text-truncate" style="max-width: 200px;">
                                        {{ $cart->book->title }}
                                    </h6>
                                    <small class="text-muted d-block">{{ $cart->quantity }} x {{ $cart->book->formatted_price }}</small>
                                </div>
                                <div class="fw-bold small text-dark">
                                    {{ $cart->formatted_subtotal }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-secondary small">
                        <span>Jumlah Total Buku:</span>
                        <span class="fw-semibold text-dark">{{ $carts->sum('quantity') }} Item</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-secondary small">
                        <span>Subtotal Produk:</span>
                        <span class="fw-semibold text-dark">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 text-secondary small">
                        <span>Ongkos Kirim:</span>
                        <span class="text-success fw-bold">GRATIS (Rp 0)</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold text-dark">Total Tagihan:</span>
                        <span class="fw-extrabold text-primary fs-4">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill shadow py-3">
                        <i class="bi bi-check2-circle me-2"></i> Konfirmasi & Buat Pesanan
                    </button>

                    <a href="{{ route('cart.index') }}" class="btn btn-link w-100 text-muted small mt-2 text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Keranjang Belanja
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
