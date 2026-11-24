<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // ============================================================
        // PENGATURAN MODE LOGIN (SOAL USK)
        // 'auto'  : BISA DUA-DUANYA (Otomatis bisa pakai Email ATAU Nama!)
        // 'name'  : HANYA BISA LOGIN PAKAI NAMA (Soal tantangan USK)
        // 'email' : HANYA BISA LOGIN PAKAI EMAIL
        // ============================================================
        $loginWith = 'auto'; // Cukup ubah ke 'name' atau 'email' jika diminta penguji

        // Ambil nilai yang diketik pengguna di form
        $inputValue = $request->input('login') ?? $request->input('name') ?? $request->input('email');

        if ($loginWith === 'name') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required'],
            ], [
                'name.required' => 'Nama pengguna wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]);

            $credentials = [
                'name' => $inputValue,
                'password' => $request->password,
            ];
        } elseif ($loginWith === 'email') {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ], [
                'email.required' => 'Alamat email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
            ]);

            $credentials = [
                'email' => $inputValue,
                'password' => $request->password,
            ];
        } else {
            // Mode 'auto': Mendukung Nama maupun Email secara otomatis
            if (empty($inputValue)) {
                return back()->withErrors(['login' => 'Email atau Nama akun wajib diisi.'])->withInput();
            }

            $request->validate([
                'password' => ['required'],
            ], [
                'password.required' => 'Password wajib diisi.',
            ]);

            // Deteksi otomatis apakah input berupa email atau nama
            $fieldType = filter_var($inputValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

            $credentials = [
                $fieldType => $inputValue,
                'password' => $request->password,
            ];
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, Admin ' . Auth::user()->name . '!');
            }

            return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'login' => 'Kombinasi akun dan kata sandi tidak cocok dengan data kami.',
            'name' => 'Kombinasi nama dan kata sandi tidak cocok dengan data kami.',
            'email' => 'Kombinasi email dan kata sandi tidak cocok dengan data kami.',
        ])->onlyInput('login', 'name', 'email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'phone.required' => 'Nomor WhatsApp / Telepon wajib diisi.',
            'address.required' => 'Alamat pengiriman wajib diisi.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        // Auto create 1-to-1 Profile
        $user->profile()->create([
            'phone_number' => $validated['phone'],
            'city' => 'Indonesia',
            'bio' => 'Pelanggan setia Toko Buku Aksara.',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi akun berhasil! Silakan masuk menggunakan email dan kata sandi Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari akun (Logout Berhasil).');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan pengguna lain.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'current_password.required_with' => 'Password saat ini wajib diisi jika ingin mengganti password.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!empty($validated['new_password'])) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
