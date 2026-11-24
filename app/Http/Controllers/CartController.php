<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('book.category')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $carts->sum(function ($item) {
            return $item->subtotal;
        });

        return view('cart.index', compact('carts', 'subtotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $book = Book::findOrFail($request->book_id);
        $quantity = (int) $request->input('quantity', 1);

        if ($book->stock < $quantity) {
            return back()->with('error', "Maaf, stok buku '{$book->title}' tidak mencukupi (Tersisa: {$book->stock}).");
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $quantity;
            if ($newQuantity > $book->stock) {
                return back()->with('error', "Jumlah di keranjang melebihi stok yang tersedia (Tersisa: {$book->stock}).");
            }
            $cart->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', "Buku '{$book->title}' berhasil dimasukkan ke keranjang belanja!");
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = (int) $request->quantity;

        if ($cart->book->stock < $quantity) {
            return back()->with('error', "Stok tidak mencukupi! Tersisa {$cart->book->stock} buku.");
        }

        $cart->update(['quantity' => $quantity]);

        return back()->with('success', 'Jumlah buku di keranjang berhasil diperbarui.');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Buku berhasil dihapus dari keranjang belanja.');
    }
}
