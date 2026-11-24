<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $carts = Cart::with('book')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong. Silakan pilih buku terlebih dahulu.');
        }

        // Cek ketersediaan stok
        foreach ($carts as $cart) {
            if ($cart->book->stock < $cart->quantity) {
                return redirect()->route('cart.index')->with('error', "Stok buku '{$cart->book->title}' tidak mencukupi (Tersisa: {$cart->book->stock}).");
            }
        }

        $totalAmount = $carts->sum(function ($item) {
            return $item->subtotal;
        });

        return view('checkout.index', compact('user', 'carts', 'totalAmount'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $carts = Cart::with('book')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'payment_method' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'customer_name.required' => 'Nama penerima wajib diisi.',
            'customer_phone.required' => 'Nomor telepon penerima wajib diisi.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        try {
            return DB::transaction(function () use ($user, $carts, $validated) {
                $totalAmount = 0;

                // Validasi stok ulang di dalam transaksi
                foreach ($carts as $cart) {
                    $book = $cart->book;
                    if ($book->stock < $cart->quantity) {
                        throw new \Exception("Maaf, stok buku '{$book->title}' tidak mencukupi karena baru saja dibeli pembeli lain (Tersisa: {$book->stock}).");
                    }
                    $totalAmount += ($book->price * $cart->quantity);
                }

                // Buat order number unik
                $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $user->id,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'unpaid',
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                // Buat order items & kurangi stok buku
                foreach ($carts as $cart) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'book_id' => $cart->book_id,
                        'book_title' => $cart->book->title,
                        'price' => $cart->book->price,
                        'quantity' => $cart->quantity,
                        'subtotal' => $cart->book->price * $cart->quantity,
                    ]);

                    // Kurangi stok
                    $cart->book->decrement('stock', $cart->quantity);
                }

                // Kosongkan keranjang belanja
                Cart::where('user_id', $user->id)->delete();

                return redirect()->route('orders.show', $order->id)->with('success', 'Selamat! Pesanan Anda berhasil dibuat dengan nomor ' . $order->order_number . '.');
            });
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }
}
