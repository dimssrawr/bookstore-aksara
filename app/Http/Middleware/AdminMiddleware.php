<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login sama sekali
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Akses Ditolak! Anda harus login sebagai Administrator untuk membuka panel admin.');
        }

        // Jika sudah login tetapi rolenya bukan admin (misal: user biasa)
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses Ditolak! Akun Anda (' . auth()->user()->email . ') tidak memiliki hak akses Administrator.');
        }

        return $next($request);
    }
}
