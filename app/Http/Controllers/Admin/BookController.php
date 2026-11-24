<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%")
                    ->orWhere('isbn', 'like', "%{$q}%")
                    ->orWhere('publisher', 'like', "%{$q}%")
                    ->orWhereHas('category', function ($catQ) use ($q) {
                        $catQ->where('name', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:books,title',
            'category_id' => 'required|exists:categories,id',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'isbn' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.unique' => 'Judul/nama buku ini sudah terdaftar dalam sistem. Silakan gunakan judul buku yang berbeda.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'author.required' => 'Penulis buku wajib diisi.',
            'publisher.required' => 'Penerbit wajib diisi.',
            'publication_year.required' => 'Tahun terbit wajib diisi.',
            'price.required' => 'Harga buku wajib diisi.',
            'stock.required' => 'Jumlah stok buku wajib diisi.',
            'cover_image.image' => 'File sampul harus berupa gambar (JPEG, PNG, JPG, WEBP).',
            'cover_image.max' => 'Ukuran gambar sampul maksimal 2 MB.',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $coverPath = $file->storeAs('books', $filename, 'public');
        }

        Book::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'category_id' => $validated['category_id'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'],
            'publication_year' => $validated['publication_year'],
            'isbn' => $validated['isbn'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'cover_image' => $coverPath,
            'description' => $validated['description'] ?? null,
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Buku baru berhasil ditambahkan!');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:books,title,' . $book->id,
            'category_id' => 'required|exists:categories,id',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'isbn' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.unique' => 'Judul/nama buku ini sudah digunakan pada data buku lainnya. Gunakan judul yang berbeda.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'author.required' => 'Penulis buku wajib diisi.',
            'publisher.required' => 'Penerbit wajib diisi.',
            'publication_year.required' => 'Tahun terbit wajib diisi.',
            'price.required' => 'Harga buku wajib diisi.',
            'stock.required' => 'Jumlah stok buku wajib diisi.',
            'cover_image.image' => 'File sampul harus berupa gambar (JPEG, PNG, JPG, WEBP).',
            'cover_image.max' => 'Ukuran gambar sampul maksimal 2 MB.',
        ]);

        if ($request->hasFile('cover_image')) {
            // Hapus cover lama jika ada di storage
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $file = $request->file('cover_image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $book->cover_image = $file->storeAs('books', $filename, 'public');
        }

        $book->title = $validated['title'];
        $book->category_id = $validated['category_id'];
        $book->author = $validated['author'];
        $book->publisher = $validated['publisher'];
        $book->publication_year = $validated['publication_year'];
        $book->isbn = $validated['isbn'] ?? null;
        $book->price = $validated['price'];
        $book->stock = $validated['stock'];
        $book->description = $validated['description'] ?? null;
        $book->is_featured = $request->has('is_featured');
        $book->save();

        return redirect()->route('admin.books.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy(Book $book)
    {
        // Validasi Integritas Data: Buku yang sudah pernah dipesan TIDAK BOLEH dihapus!
        if ($book->orderItems()->count() > 0) {
            return back()->with('error', "Akses Ditolak! Buku '{$book->title}' tidak dapat dihapus karena sudah memiliki riwayat transaksi pesanan pelanggan. Sebagai alternatif, Anda dapat mengubah stoknya menjadi 0.");
        }

        // Hapus dari keranjang belanja jika masih ada yang menyimpannya
        $book->carts()->delete();

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus!');
    }
}
