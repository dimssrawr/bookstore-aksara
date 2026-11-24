@extends('layouts.app')

@section('title', 'Pesanan Saya - Toko Buku Aksara')

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h1 class="h3 fw-bold text-dark mb-1"><i class="bi bi-bag-check text-primary me-2"></i>Riwayat Pesanan Buku</h1>
        <p class="text-muted small mb-0">Pantau status pengiriman dan detail transaksi buku yang telah Anda pesan.</p>
    </div>
</div>

<div class="container pb-5">
    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm p-5 text-center bg-white">
            <div class="my-4">
                <i class="bi bi-box2 text-muted" style="font-size: 4rem;"></i>
                <h4 class="fw-bold text-dark mt-3">Belum Ada Pesanan</h4>
                <p class="text-muted mx-auto" style="max-width: 450px;">
                    Anda belum pernah membuat pesanan buku di Aksara BookStore. Jelajahi katalog kami dan temukan buku favorit Anda sekarang!
                </p>
                <a href="{{ route('books.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                    Mulai Belanja Buku
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small text-muted text-uppercase">
                        <tr>
                            <th>Nomor Order</th>
                            <th>Tanggal Pesan</th>
                            <th>Daftar Buku</th>
                            <th>Total Tagihan</th>
                            <th>Metode Bayar</th>
                            <th>Status Pesanan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $order->order_number }}</strong>
                                </td>
                                <td class="small text-muted">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">
                                        {{ $order->items->first()->book_title ?? 'Item Buku' }}
                                        @if($order->items->count() > 1)
                                            <span class="text-muted fw-normal">+{{ $order->items->count() - 1 }} buku lainnya</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $order->items->sum('quantity') }} Total Eksemplar</small>
                                </td>
                                <td class="fw-bold text-dark">
                                    {{ $order->formatted_total }}
                                </td>
                                <td class="small">
                                    <span class="badge bg-light text-dark border">{{ $order->payment_method }}</span>
                                </td>
                                <td>
                                    {!! $order->status_badge !!}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i> Rincian
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
