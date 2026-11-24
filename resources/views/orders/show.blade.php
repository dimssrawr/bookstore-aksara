@extends('layouts.app')

@section('title', 'Rincian Pesanan ' . $order->order_number . ' - Toko Buku Aksara')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Pesanan
        </a>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-primary rounded-pill">
                <i class="bi bi-printer me-1"></i> Cetak Invoice
            </button>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary rounded-pill">
                    <i class="bi bi-gear-fill me-1"></i> Kelola di Admin
                </a>
            @endif
        </div>
    </div>

    <!-- Invoice Card -->
    <div class="card border-0 shadow p-4 p-md-5 bg-white">
        <!-- Invoice Header -->
        <div class="row align-items-center pb-4 border-bottom g-3">
            <div class="col-sm-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-book-half text-primary fs-3"></i>
                    <span class="fs-4 fw-bold text-dark">Aksara<span class="text-primary">BookStore</span></span>
                </div>
                <div class="small text-muted">
                    Jl. Matraman Raya No. 42, Jakarta Timur<br>
                    WhatsApp: +62 812-9876-5432 | info@aksarabookstore.com
                </div>
            </div>
            <div class="col-sm-6 text-sm-end">
                <h3 class="fw-extrabold text-primary mb-1">INVOICE PESANAN</h3>
                <div class="fw-bold text-dark fs-5">{{ $order->order_number }}</div>
                <div class="small text-muted">Tanggal: {{ $order->created_at->format('d F Y, H:i') }} WIB</div>
                <div class="mt-2">
                    Status Pesanan: {!! $order->status_badge !!}
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Information -->
        <div class="row g-4 my-3 pb-3 border-bottom">
            <div class="col-md-6">
                <h6 class="fw-bold text-dark text-uppercase small text-muted mb-2">Informasi Penerima</h6>
                <div class="fw-bold text-dark fs-6">{{ $order->customer_name }}</div>
                <div class="text-secondary small mb-1"><i class="bi bi-telephone me-1"></i>{{ $order->customer_phone }}</div>
                <div class="text-secondary small"><i class="bi bi-envelope me-1"></i>{{ $order->user->email }}</div>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-dark text-uppercase small text-muted mb-2">Alamat Pengiriman & Catatan</h6>
                <div class="text-secondary small mb-2">
                    <i class="bi bi-geo-alt me-1 text-danger"></i>{{ $order->shipping_address }}
                </div>
                @if($order->notes)
                    <div class="p-2 bg-light rounded small text-muted">
                        <strong>Catatan:</strong> {{ $order->notes }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive my-3">
            <table class="table align-middle">
                <thead class="table-light small text-muted text-uppercase">
                    <tr>
                        <th>#</th>
                        <th>Buku Yang Dipesan</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-dark">{{ $item->book_title }}</strong>
                                @if($item->book)
                                    <small class="text-muted d-block">{{ $item->book->author }} ({{ $item->book->publisher }})</small>
                                @endif
                            </td>
                            <td class="text-end">{{ $item->formatted_price }}</td>
                            <td class="text-center fw-semibold">{{ $item->quantity }}</td>
                            <td class="text-end fw-bold text-dark">{{ $item->formatted_subtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-top">
                    <tr>
                        <td colspan="4" class="text-end fw-bold text-secondary">Subtotal Buku:</td>
                        <td class="text-end fw-bold">{{ $order->formatted_total }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold text-secondary">Biaya Pengiriman:</td>
                        <td class="text-end text-success fw-bold">Rp 0 (Gratis)</td>
                    </tr>
                    <tr class="table-light">
                        <td colspan="4" class="text-end fw-bold fs-5 text-dark">Total Pembayaran:</td>
                        <td class="text-end fw-bold fs-5 text-primary">{{ $order->formatted_total }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Info Banner -->
        <div class="p-3 bg-light rounded-3 mt-3">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="small text-muted">Metode Pembayaran:</div>
                    <div class="fw-bold text-dark fs-6">{{ $order->payment_method }}</div>
                    @if(str_contains($order->payment_method, 'GoPay') || str_contains($order->payment_method, 'OVO') || str_contains($order->payment_method, 'DANA') || str_contains($order->payment_method, 'QRIS') || str_contains($order->payment_method, 'ShopeePay'))
                        <small class="text-primary"><i class="bi bi-phone me-1"></i>Transfer/Scan ke E-Wallet resmi Toko: <strong>0812-9876-5432</strong> (a.n. Toko Buku Aksara).</small>
                    @else
                        <small class="text-primary"><i class="bi bi-bank me-1"></i>Transfer ke Rekening Resmi: <strong>BCA 8829-1029-338</strong> a.n. Toko Buku Aksara.</small>
                    @endif
                </div>
                <div class="col-md-5 text-md-end mt-2 mt-md-0">
                    <div class="small text-muted">Status Pembayaran:</div>
                    @if($order->payment_status === 'paid')
                        <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-check-circle me-1"></i>Lunas (PAID)</span>
                    @else
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2"><i class="bi bi-hourglass-split me-1"></i>Belum Lunas (UNPAID)</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
