@extends('layouts.app')

@section('title', 'Keranjang Belanja - Toko Buku Aksara')

@section('content')
<!-- Page Header with Step Wizard -->
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <h1 class="h4 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                        <i class="bi bi-cart3 fs-5"></i>
                    </span>
                    <span>Keranjang Belanja</span>
                </h1>
                <p class="text-muted small mb-0">Tinjau daftar buku pesanan Anda sebelum menuju langkah pengiriman.</p>
            </div>
            <div class="col-md-6">
                <!-- Checkout Steps Indicator -->
                <div class="d-flex align-items-center justify-content-md-end gap-2 text-nowrap small">
                    <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold">
                        <i class="bi bi-1-circle-fill me-1"></i> 1. Keranjang
                    </span>
                    <i class="bi bi-chevron-right text-muted"></i>
                    <span class="badge bg-light text-muted px-3 py-2 rounded-pill border">
                        <i class="bi bi-2-circle me-1"></i> 2. Pengiriman
                    </span>
                    <i class="bi bi-chevron-right text-muted"></i>
                    <span class="badge bg-light text-muted px-3 py-2 rounded-pill border">
                        <i class="bi bi-3-circle me-1"></i> 3. Pembayaran
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    @if($carts->isEmpty())
        <div class="card border-0 shadow-sm p-5 text-center bg-white rounded-4">
            <div class="my-4">
                <div class="d-inline-flex p-4 rounded-circle bg-primary bg-opacity-10 text-primary mb-3">
                    <i class="bi bi-bag-x" style="font-size: 3.5rem;"></i>
                </div>
                <h4 class="fw-bold text-dark mt-2">Keranjang Belanja Masih Kosong</h4>
                <p class="text-muted mx-auto" style="max-width: 480px;">
                    Anda belum memasukkan buku apapun ke dalam keranjang. Jelajahi ribuan koleksi buku berkualitas di katalog Toko Buku Aksara.
                </p>
                <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold mt-2 shadow-sm">
                    <i class="bi bi-compass me-2"></i> Jelajahi Buku Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            <!-- Cart Items: Card-Based List -->
            <div class="col-lg-8">
                <!-- Free Shipping Banner -->
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-3 mb-4 rounded-3 p-3">
                    <i class="bi bi-truck text-success fs-3"></i>
                    <div class="small">
                        <strong class="d-block text-dark">Promo Pengiriman Toko Buku Aksara</strong>
                        <span class="text-success-emphasis">Pesanan Anda berhak mendapatkan fasilitas <strong>Bebas Biaya Pengiriman (Gratis Ongkir)</strong> ke seluruh wilayah Indonesia.</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">
                        Item Terpilih ({{ $carts->count() }} Buku)
                    </h6>
                    <a href="{{ route('books.index') }}" class="text-primary small text-decoration-none fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Buku Lainnya
                    </a>
                </div>

                <!-- Individual Item Cards -->
                <div class="d-flex flex-column gap-3">
                    @foreach($carts as $cart)
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                            <div class="row align-items-center g-3">
                                <!-- Cover Image -->
                                <div class="col-auto">
                                    <a href="{{ route('books.show', $cart->book->slug) }}">
                                        <img src="{{ $cart->book->cover_url }}" 
                                             onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';" 
                                             alt="{{ $cart->book->title }}" 
                                             class="rounded-3 shadow-sm" 
                                             style="width: 80px; height: 110px; object-fit: cover;">
                                    </a>
                                </div>

                                <!-- Details -->
                                <div class="col">
                                    <div class="d-flex align-items-start justify-content-between gap-2">
                                        <div>
                                            <span class="badge bg-light text-primary border small mb-1">
                                                {{ $cart->book->category->name }}
                                            </span>
                                            <h6 class="fw-bold text-dark mb-1">
                                                <a href="{{ route('books.show', $cart->book->slug) }}" class="text-dark text-decoration-none">
                                                    {{ $cart->book->title }}
                                                </a>
                                            </h6>
                                            <div class="text-muted small mb-2">
                                                <i class="bi bi-pen me-1"></i>{{ $cart->book->author }}
                                            </div>
                                            <div class="fw-semibold text-primary">
                                                {{ $cart->book->formatted_price }} <span class="text-muted fw-normal small">/ buku</span>
                                            </div>
                                        </div>

                                        <!-- Delete Action Button -->
                                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" id="delete-cart-{{ $cart->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-2" 
                                                    onclick="confirmDelete('delete-cart-{{ $cart->id }}', 'Hapus buku ini dari keranjang?')" 
                                                    title="Hapus dari Keranjang">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <hr class="my-2 text-muted opacity-25">

                                    <!-- Bottom Row: Stepper & Subtotal -->
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-1">
                                        <!-- Interactive Quantity Control -->
                                        <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-inline-flex align-items-center gap-2" id="cart-form-{{ $cart->id }}">
                                            @csrf
                                            @method('PUT')
                                            <span class="small text-muted fw-semibold">Jumlah:</span>
                                            <div class="input-group input-group-sm" style="width: 130px;">
                                                <button class="btn btn-light border" type="button" 
                                                        onclick="updateQty('{{ $cart->id }}', -1, {{ $cart->book->stock }})">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" name="quantity" id="qty-input-{{ $cart->id }}" 
                                                       value="{{ $cart->quantity }}" min="1" max="{{ $cart->book->stock }}" 
                                                       class="form-control text-center fw-bold bg-white" 
                                                       onchange="document.getElementById('cart-form-{{ $cart->id }}').submit()">
                                                <button class="btn btn-light border" type="button" 
                                                        onclick="updateQty('{{ $cart->id }}', 1, {{ $cart->book->stock }})">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">Stok: <strong>{{ $cart->book->stock }}</strong></small>
                                        </form>

                                        <!-- Subtotal Pill -->
                                        <div class="text-end">
                                            <span class="small text-muted d-block">Subtotal Item:</span>
                                            <span class="fw-bold text-primary fs-6">{{ $cart->formatted_subtotal }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-receipt text-primary"></i>
                        <span>Ringkasan Transaksi</span>
                    </h5>
                    
                    <div class="d-flex justify-content-between mb-2 text-secondary small">
                        <span>Total Kuantitas:</span>
                        <span class="fw-bold text-dark">{{ $carts->sum('quantity') }} Buku</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-secondary small">
                        <span>Harga Subtotal:</span>
                        <span class="fw-bold text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-secondary small">
                        <span>Ongkos Kirim:</span>
                        <span class="text-success fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Rp 0 (GRATIS)</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 text-secondary small">
                        <span>Biaya Layanan:</span>
                        <span class="text-muted">Rp 0</span>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="fw-bold text-dark">Total Biaya:</span>
                            <span class="fw-extrabold text-primary fs-4">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill shadow-sm py-3 mb-3">
                        Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                    </a>

                    <!-- Security & Service Features -->
                    <div class="border-top pt-3">
                        <div class="d-flex flex-column gap-2 small text-secondary">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check text-success fs-5"></i>
                                <span>100% Produk Buku Cetak Original</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-qr-code text-primary fs-5"></i>
                                <span>Mendukung QRIS, E-Wallet & Bank Transfer</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-box-seam text-warning fs-5"></i>
                                <span>Pengemasan Bubble Wrap Tebal & Aman</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function updateQty(cartId, delta, maxStock) {
        const input = document.getElementById('qty-input-' + cartId);
        const form = document.getElementById('cart-form-' + cartId);
        let current = parseInt(input.value) || 1;
        let nextVal = current + delta;
        if (nextVal >= 1 && nextVal <= maxStock) {
            input.value = nextVal;
            form.submit();
        } else if (nextVal > maxStock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: 'Maksimum pembelian sesuai stok saat ini adalah ' + maxStock + ' buku.',
                confirmButtonColor: '#4f46e5'
            });
        }
    }
</script>
@endsection
