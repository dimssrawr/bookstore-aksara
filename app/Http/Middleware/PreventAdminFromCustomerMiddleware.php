<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminFromCustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Mencegah akun Administrator mengakses halaman depan / customer (/)
     * dan mengembalikannya ke panel admin (/admin) dengan notifikasi alert.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses Ditolak! Anda sedang login sebagai Administrator. Halaman toko hanya untuk pembeli/tamu. Silakan logout terlebih dahulu jika ingin membuka halaman toko.');
        }

        return $next($request);
    }
}
