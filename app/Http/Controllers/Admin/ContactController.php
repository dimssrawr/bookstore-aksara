<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactReply;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::with(['user', 'replies.user']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('subject', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%");
            });
        }

        $contacts = $query->latest()->paginate(10)->withQueryString();

        return view('admin.contacts.index', compact('contacts'));
    }

    public function markAsRead(Contact $contact)
    {
        $contact->update(['is_read' => true]);
        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    /**
     * Admin membalas pesan dari pengguna
     */
    public function reply(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ], [
            'message.required' => 'Isi pesan balasan wajib diisi.',
        ]);

        ContactReply::create([
            'contact_id' => $contact->id,
            'user_id' => auth()->id(),
            'sender_type' => 'admin',
            'message' => $validated['message'],
        ]);

        $contact->update(['is_read' => true]);

        return back()->with('success', 'Balasan pesan berhasil dikirim kepada ' . $contact->name . '!');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}
