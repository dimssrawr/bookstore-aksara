<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $featuredBooks = Book::with('category')
            ->where('is_featured', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $latestBooks = Book::with('category')
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('books')->take(6)->get();
        $totalBooksCount = Book::count();

        return view('home', compact('featuredBooks', 'latestBooks', 'categories', 'totalBooksCount'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        $userMessages = collect();
        if (Auth::check()) {
            $userMessages = Contact::with(['replies.user'])
                ->where(function ($q) {
                    $q->where('user_id', Auth::id())
                      ->orWhere('email', Auth::user()->email);
                })
                ->latest()
                ->get();
        }

        return view('contact', compact('userMessages'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subjek pesan wajib diisi.',
            'message.required' => 'Isi pesan wajib diisi.',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return back()->with('success', 'Pesan Anda berhasil dikirim ke Admin Toko Buku Aksara! Kami akan segera merespons.');
    }

    /**
     * User membalas pesan Admin dalam percakapan
     */
    public function contactReply(Request $request, Contact $contact)
    {
        if (Auth::check() && ($contact->user_id === Auth::id() || $contact->email === Auth::user()->email)) {
            $validated = $request->validate([
                'message' => 'required|string|max:2000',
            ], [
                'message.required' => 'Isi balasan pesan wajib diisi.',
            ]);

            $contact->replies()->create([
                'user_id' => Auth::id(),
                'sender_type' => 'user',
                'message' => $validated['message'],
            ]);

            // Tandai belum dibaca agar Admin tahu ada balasan baru dari User
            $contact->update(['is_read' => false]);

            return back()->with('success', 'Balasan pesan berhasil dikirim ke Admin!');
        }

        return back()->with('error', 'Akses ditolak ke percakapan ini.');
    }
}
