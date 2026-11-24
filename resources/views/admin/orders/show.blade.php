@extends('layouts.admin')

@section('title', 'Detail Pesanan: ' . $order->order_number)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h2 class="h3 fw-bold text-dark mb-0">Pesanan: {{ $order->order_number }}</h2>
            {!! $order->status_badge !!}
        </div>
        <p class="text-muted small mb-0 mt-1">Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
    </div>
    <div class="d-flex gap-2 mt-3 mt-sm-0">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke List Pesanan
        </a>
        <a href="{{ route('orders.show', $order->id) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-printer me-1"></i> Buka Format Cetak / Invoice
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Order Items & Customer Info -->
    <div class="col-lg-8">
        <!-- Items Table -->
        <div class="card border-0 shadow-sm bg-white mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-box-seam text-primary me-2"></i>Daftar Buku Yang Dipesan</h6>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small text-muted text-uppercase">
                        <tr>
                            <th style="width: 60px;">Sampul</th>
                            <th>Judul Buku</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <img src="{{ $item->book ? $item->book->cover_url : asset('images/default-book.svg') }}" 
                                         onerror="this.onerror=null; this.src='{{ asset('images/default-book.svg') }}';"
                                         alt="{{ $item->book_title }}" class="rounded shadow-sm" style="width: 45px; height: 60px; object-fit: cover;">
                                </td>
                                <td>
                                    <strong class="text-dark">{{ $item->book_title }}</strong>
                                    @if($item->book)
                                        <small class="text-muted d-block">{{ $item->book->author }} ({{ $item->book->category->name }})</small>
                                    @endif
                                </td>
                                <td class="text-end">{{ $item->formatted_price }}</td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="text-end fw-bold text-primary">{{ $item->formatted_subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top">
                        <tr>
                            <td colspan="4" class="text-end fw-bold text-secondary">Total Tagihan:</td>
                            <td class="text-end fw-extrabold text-primary fs-5">{{ $order->formatted_total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Shipping & Customer Info -->
        <div class="card border-0 shadow-sm bg-white p-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i>Informasi Pemesan & Alamat Pengiriman</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <small class="text-muted text-uppercase fw-semibold d-block mb-1">Identitas Pemesan</small>
                        <div class="fw-bold text-dark fs-6">{{ $order->customer_name }}</div>
                        <div class="text-secondary small mt-1"><i class="bi bi-telephone me-1"></i>{{ $order->customer_phone }}</div>
                        <div class="text-secondary small mt-1"><i class="bi bi-person me-1"></i>Akun Terdaftar: {{ $order->user->name }} ({{ $order->user->email }})</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <small class="text-muted text-uppercase fw-semibold d-block mb-1">Alamat Tujuan Pengiriman</small>
                        <p class="text-secondary small mb-2"><i class="bi bi-geo-alt text-danger me-1"></i>{{ $order->shipping_address }}</p>
                        @if($order->notes)
                            <div class="small bg-white p-2 rounded border text-muted">
                                <strong>Catatan:</strong> {{ $order->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Management Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm bg-white p-4 sticky-top" style="top: 90px;">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-sliders text-primary me-2"></i>Kelola Status Pesanan</h6>
            
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" id="orderStatusForm">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Metode Pembayaran:</label>
                    <div class="p-2 bg-light rounded border fw-semibold small text-dark">
                        {{ $order->payment_method }}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Status Pembayaran:</label>
                    <div class="d-flex align-items-center gap-2 p-2 bg-success bg-opacity-10 text-success rounded border border-success border-opacity-25 fw-bold small">
                        <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
                        <div>
                            <div>Lunas (Paid)</div>
                            <small class="text-muted fw-normal d-block">Payment at Delivery: Pembayaran terverifikasi lunas saat pesanan dibuat.</small>
                        </div>
                    </div>
                    <input type="hidden" name="payment_status" value="paid">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary">Status Pesanan <span class="text-danger">*</span></label>
                    <select name="status" id="orderStatusSelect" class="form-select">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                            Menunggu Konfirmasi (Pending)
                        </option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                            Sedang Diproses (Processing)
                        </option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>
                            Sedang Dikirim (Shipped)
                        </option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>
                            Selesai (Completed)
                        </option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                            Dibatalkan (Cancelled)
                        </option>
                    </select>
                    <small class="text-muted d-block mt-1">Ubah status pesanan sesuai tahapan pengiriman buku.</small>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill py-2">
                    <i class="bi bi-check-circle me-1"></i> Simpan Perubahan Status
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
