@extends('layouts.admin')

@section('title', 'List Pesanan Buku User')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1"><i class="bi bi-cart-check text-primary me-2"></i>List Pesanan Buku User</h2>
        <p class="text-muted small mb-0">Pantau, validasi, dan perbarui status pesanan buku dari seluruh pelanggan yang berbeda-beda.</p>
    </div>
</div>

<div class="card border-0 shadow-sm bg-white overflow-hidden mb-4">
    <!-- Filter Bar -->
    <div class="p-3 border-bottom bg-light">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari No. Order, nama pelanggan, no telp...">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Status Pesanan --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi (Pending)</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Sedang Diproses (Processing)</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Sedang Dikirim (Shipped)</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                </select>
            </div>
            <div class="col-md-3 text-md-end">
                @if(request('q') || request('status'))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
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
                    <th>No. Order & Tanggal</th>
                    <th>Nama Pemesan (User)</th>
                    <th>Buku Yang Dipesan</th>
                    <th>Total Tagihan</th>
                    <th>Metode & Status Bayar</th>
                    <th>Status Pesanan</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $index => $order)
                    <tr>
                        <td>{{ $orders->firstItem() + $index }}</td>
                        <td>
                            <strong class="text-primary">{{ $order->order_number }}</strong>
                            <div class="small text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $order->customer_name }}</div>
                            <small class="text-muted d-block"><i class="bi bi-telephone me-1"></i>{{ $order->customer_phone }}</small>
                            <small class="text-muted d-block"><i class="bi bi-person me-1"></i>Akun: {{ $order->user->name ?? '-' }}</small>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">
                                {{ $order->items->first()->book_title ?? '-' }}
                                @if($order->items->count() > 1)
                                    <span class="text-muted fw-normal">+{{ $order->items->count() - 1 }} buku lainnya</span>
                                @endif
                            </div>
                            <small class="text-muted">Total: {{ $order->items->sum('quantity') }} buku</small>
                        </td>
                        <td class="fw-bold text-dark">
                            {{ $order->formatted_total }}
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark mb-1">{{ $order->payment_method }}</div>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success small"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark small"><i class="bi bi-clock me-1"></i>Belum Lunas</span>
                            @endif
                        </td>
                        <td>
                            {!! $order->status_badge !!}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                Kelola
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Belum ada pesanan yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="p-3 border-top d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
