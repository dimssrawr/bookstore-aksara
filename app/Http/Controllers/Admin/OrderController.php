<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.book']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%")
                    ->orWhere('customer_phone', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.book']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        // Aturan Bisnis: Pesanan tidak boleh 'completed' (selesai) jika pembayaran belum lunas (unpaid)
        if ($validated['status'] === 'completed' && $validated['payment_status'] === 'unpaid') {
            return back()->with('error', 'Pesanan tidak dapat diselesaikan jika status pembayaran masih Belum Lunas (Unpaid). Silakan ubah Status Pembayaran menjadi Lunas (Paid) terlebih dahulu.');
        }

        $order->update([
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
        ]);

        return back()->with('success', "Status pesanan {$order->order_number} berhasil diperbarui.");
    }
}
