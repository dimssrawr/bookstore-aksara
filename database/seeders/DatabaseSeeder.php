<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Profile;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin & Users
        $admin = User::create([
            'name' => 'Administrator Aksara',
            'email' => 'admin@bookstore.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081298765432',
            'address' => 'Kantor Pusat Toko Buku Aksara, Jakarta Pusat',
        ]);

        $user1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '081234567890',
            'address' => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
        ]);

        $user2 = User::create([
            'name' => 'Siti Rahmawati',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '085712345678',
            'address' => 'Jl. Diponegoro No. 88, Bandung',
        ]);

        $userDemo = User::create([
            'name' => 'User Demo',
            'email' => 'user@bookstore.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'phone' => '082199887766',
            'address' => 'Jl. Malioboro No. 20, Yogyakarta',
        ]);

        // ============================================================
        // RELASI 1 TO 1 (One to One): User hasOne Profile
        // ============================================================
        $admin->profile()->create([
            'bio' => 'Kepala Pengelola Sistem Aksara BookStore.',
            'phone_number' => '081298765432',
            'city' => 'Jakarta Pusat',
            'gender' => 'L',
        ]);

        $user1->profile()->create([
            'bio' => 'Penggemar buku pemrograman & rekayasa perangkat lunak.',
            'phone_number' => '081234567890',
            'city' => 'Jakarta Selatan',
            'gender' => 'L',
        ]);

        $user2->profile()->create([
            'bio' => 'Pembaca novel fiksi dan pengembangan karakter.',
            'phone_number' => '085712345678',
            'city' => 'Bandung',
            'gender' => 'P',
        ]);

        $userDemo->profile()->create([
            'bio' => 'Akun uji coba untuk demonstrasi sertifikasi BNSP USK.',
            'phone_number' => '082199887766',
            'city' => 'Yogyakarta',
            'gender' => 'L',
        ]);

        // ============================================================
        // RELASI 1 TO MANY (One to Many): Category hasMany Book
        // ============================================================
        $catTeknologi = Category::create([
            'name' => 'Teknologi & Pemrograman',
            'slug' => 'teknologi-pemrograman',
            'description' => 'Buku-buku seputar rekayasa perangkat lunak, coding, database, dan arsitektur sistem.',
        ]);

        $catFiksi = Category::create([
            'name' => 'Fiksi & Sastra',
            'slug' => 'fiksi-sastra',
            'description' => 'Novel inspiratif, sastra klasik, dan cerita fiksi terbaik karya penulis ternama.',
        ]);

        $catBisnis = Category::create([
            'name' => 'Bisnis & Keuangan',
            'slug' => 'bisnis-keuangan',
            'description' => 'Strategi bisnis modern, manajemen finansial, kewirausahaan, dan investasi.',
        ]);

        $catSelfDev = Category::create([
            'name' => 'Pengembangan Diri',
            'slug' => 'pengembangan-diri',
            'description' => 'Panduan motivasi, psikologi praktis, produktivitas, dan kebiasaan positif.',
        ]);

        $catKomik = Category::create([
            'name' => 'Komik & Grafis',
            'slug' => 'komik-grafis',
            'description' => 'Koleksi komik, novel grafis, dan manga pilihan terpopuler.',
        ]);

        // ============================================================
        // RELASI MANY TO MANY: Tags Master
        // ============================================================
        $tagBestSeller = Tag::create(['name' => 'Best Seller', 'slug' => 'best-seller', 'color' => '#f59e0b']);
        $tagUsk = Tag::create(['name' => 'Rekomendasi USK', 'slug' => 'rekomendasi-usk', 'color' => '#4f46e5']);
        $tagPopuler = Tag::create(['name' => 'Paling Diminati', 'slug' => 'paling-diminati', 'color' => '#10b981']);
        $tagEdisiBaru = Tag::create(['name' => 'Edisi Terbaru', 'slug' => 'edisi-terbaru', 'color' => '#06b6d4']);

        // 3. Create Books
        $booksData = [
            // Teknologi
            [
                'category_id' => $catTeknologi->id,
                'title' => 'Clean Code: Panduan Praktis Pemrograman Bersih',
                'slug' => 'clean-code-panduan-praktis-pemrograman-bersih',
                'author' => 'Robert C. Martin (Uncle Bob)',
                'publisher' => 'Prentice Hall',
                'publication_year' => 2021,
                'isbn' => '978-0132350884',
                'price' => 145000,
                'stock' => 18,
                'cover_image' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3edd4?auto=format&fit=crop&w=600&q=80',
                'description' => 'Buku legendaris yang mengajarkan cara menulis kode yang mudah dibaca, dirawat, dan diuji. Wajib dibaca oleh calon Junior Web Developer!',
                'is_featured' => true,
            ],
            [
                'category_id' => $catTeknologi->id,
                'title' => 'Mastering Laravel 11: From Scratch to Production',
                'slug' => 'mastering-laravel-11-from-scratch-to-production',
                'author' => 'Taylor Otwell & Komunitas',
                'publisher' => 'Informatika Pratama',
                'publication_year' => 2024,
                'isbn' => '978-6028123456',
                'price' => 125000,
                'stock' => 12,
                'cover_image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
                'description' => 'Panduan komprehensif membangun aplikasi web berskala enterprise menggunakan framework PHP modern Laravel 11.',
                'is_featured' => true,
            ],
            [
                'category_id' => $catTeknologi->id,
                'title' => 'The Pragmatic Programmer: 20th Anniversary Edition',
                'slug' => 'the-pragmatic-programmer-20th-anniversary-edition',
                'author' => 'David Thomas & Andrew Hunt',
                'publisher' => 'Addison-Wesley',
                'publication_year' => 2020,
                'isbn' => '978-0135957059',
                'price' => 160000,
                'stock' => 8,
                'cover_image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80',
                'description' => 'Salah satu buku software development terpopuler yang mengupas pola pikir, disiplin, dan strategi programmer pragmatis.',
                'is_featured' => true,
            ],

            // Fiksi
            [
                'category_id' => $catFiksi->id,
                'title' => 'Bumi Manusia',
                'slug' => 'bumi-manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Lentera Dipantara',
                'publication_year' => 2019,
                'isbn' => '978-9799731234',
                'price' => 98000,
                'stock' => 25,
                'cover_image' => 'https://images.unsplash.com/photo-1495640388908-05fa85288e61?auto=format&fit=crop&w=600&q=80',
                'description' => 'Karya sastra agung Tetralogi Buru yang mengisahkan perjuangan Minke di era kolonial Hindia Belanda.',
                'is_featured' => true,
            ],
            [
                'category_id' => $catFiksi->id,
                'title' => 'Laskar Pelangi',
                'slug' => 'laskar-pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publication_year' => 2018,
                'isbn' => '978-9793062792',
                'price' => 85000,
                'stock' => 20,
                'cover_image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80',
                'description' => 'Kisah inspiratif tentang sepuluh anak Belitong yang pantang menyerah menuntut ilmu di tengah keterbatasan sarana sekolah.',
                'is_featured' => false,
            ],
            [
                'category_id' => $catFiksi->id,
                'title' => 'Hujan',
                'slug' => 'hujan',
                'author' => 'Tere Liye',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2020,
                'isbn' => '978-6020324784',
                'price' => 78000,
                'stock' => 15,
                'cover_image' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80',
                'description' => 'Novel fiksi ilmiah romantis yang menceritakan tentang persahabatan, cinta, perpisahan, dan melupakan di masa depan futuristik.',
                'is_featured' => false,
            ],

            // Bisnis
            [
                'category_id' => $catBisnis->id,
                'title' => 'Psychology of Money: Pelajaran Abadi Mengenai Kekayaan',
                'slug' => 'psychology-of-money-pelajaran-abadi-mengenai-kekayaan',
                'author' => 'Morgan Housel',
                'publisher' => 'Penerbit Baca',
                'publication_year' => 2021,
                'isbn' => '978-6026486516',
                'price' => 95000,
                'stock' => 30,
                'cover_image' => 'https://images.unsplash.com/photo-1553729459-efe14ef6055d?auto=format&fit=crop&w=600&q=80',
                'description' => 'Bagaimana manusia memperlakukan uang dipengaruhi oleh ego dan psikologis, bukan semata hitung-hitungan rumus keuangan.',
                'is_featured' => true,
            ],
            [
                'category_id' => $catBisnis->id,
                'title' => 'Zero to One: Catatan Tentang Startups',
                'slug' => 'zero-to-one-catatan-tentang-startups',
                'author' => 'Peter Thiel with Blake Masters',
                'publisher' => 'Crown Business',
                'publication_year' => 2019,
                'isbn' => '978-0804139298',
                'price' => 110000,
                'stock' => 14,
                'cover_image' => 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?auto=format&fit=crop&w=600&q=80',
                'description' => 'Panduan membangun bisnis monopoli kreatif yang menciptakan inovasi baru (dari 0 ke 1), bukan sekadar meniru hal yang ada (dari 1 ke N).',
                'is_featured' => false,
            ],

            // Self Development
            [
                'category_id' => $catSelfDev->id,
                'title' => 'Atomic Habits: Perubahan Kecil yang Memberikan Hasil Luar Biasa',
                'slug' => 'atomic-habits-perubahan-kecil-yang-memberikan-hasil-luar-biasa',
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2021,
                'isbn' => '978-6020633176',
                'price' => 108000,
                'stock' => 40,
                'cover_image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80',
                'description' => 'Metode praktis membentuk kebiasaan baik dan meruntuhkan kebiasaan buruk dengan perbaikan 1% setiap harinya.',
                'is_featured' => true,
            ],
            [
                'category_id' => $catSelfDev->id,
                'title' => 'Filosofi Teras: Menemukan Kedamaian Lewat Stoikisme',
                'slug' => 'filosofi-teras-menemukan-kedamaian-lewat-stoikisme',
                'author' => 'Henry Manampiring',
                'publisher' => 'Buku Kompas',
                'publication_year' => 2020,
                'isbn' => '978-6024125189',
                'price' => 88000,
                'stock' => 22,
                'cover_image' => 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?auto=format&fit=crop&w=600&q=80',
                'description' => 'Penerapan filsafat stoisisme kuno Yunani-Romawi secara santai dan aplikatif untuk mengatasi kekhawatiran mental anak muda masa kini.',
                'is_featured' => false,
            ],

            // Komik & Grafis
            [
                'category_id' => $catKomik->id,
                'title' => 'One Piece Volume 100 Spesial Edition',
                'slug' => 'one-piece-volume-100-spesial-edition',
                'author' => 'Eiichiro Oda',
                'publisher' => 'Elex Media Komputindo',
                'publication_year' => 2023,
                'isbn' => '978-6230030112',
                'price' => 50000,
                'stock' => 50,
                'cover_image' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=600&q=80',
                'description' => 'Puncak pertempuran dahsyat di Onigashima antara bajak laut Topi Jerami melawan aliansi Yonko Kaido dan Big Mom.',
                'is_featured' => false,
            ],
        ];

        $createdBooks = [];
        foreach ($booksData as $data) {
            $createdBooks[] = Book::create($data);
        }

        // ============================================================
        // RELASI MANY TO MANY: Hubungkan Book dengan Tag melalui book_tag
        // ============================================================
        $createdBooks[0]->tags()->attach([$tagBestSeller->id, $tagUsk->id]); // Clean Code
        $createdBooks[1]->tags()->attach([$tagUsk->id, $tagEdisiBaru->id]);   // Laravel 11
        $createdBooks[2]->tags()->attach([$tagBestSeller->id]);               // Pragmatic Programmer
        $createdBooks[3]->tags()->attach([$tagPopuler->id]);                  // Bumi Manusia
        $createdBooks[6]->tags()->attach([$tagBestSeller->id, $tagPopuler->id]); // Psychology of Money
        $createdBooks[8]->tags()->attach([$tagBestSeller->id, $tagPopuler->id]); // Atomic Habits

        // ============================================================
        // RELASI 1 TO MANY & MANY TO MANY: Orders & OrderItems
        // ============================================================
        $order1 = Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-0001',
            'user_id' => $user1->id,
            'total_amount' => 240000,
            'status' => 'completed',
            'payment_method' => 'QRIS (GoPay, OVO, DANA)',
            'payment_status' => 'paid',
            'customer_name' => $user1->name,
            'customer_phone' => $user1->phone,
            'shipping_address' => $user1->address,
            'notes' => 'Tolong dibungkus bubble wrap tebal ya.',
            'created_at' => now()->subDays(3),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'book_id' => $createdBooks[0]->id,
            'book_title' => $createdBooks[0]->title,
            'price' => $createdBooks[0]->price,
            'quantity' => 1,
            'subtotal' => $createdBooks[0]->price,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'book_id' => $createdBooks[6]->id,
            'book_title' => $createdBooks[6]->title,
            'price' => $createdBooks[6]->price,
            'quantity' => 1,
            'subtotal' => $createdBooks[6]->price,
        ]);

        $order2 = Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-0002',
            'user_id' => $user2->id,
            'total_amount' => 108000,
            'status' => 'processing',
            'payment_method' => 'Bank BCA (Virtual Account / Transfer)',
            'payment_status' => 'unpaid',
            'customer_name' => $user2->name,
            'customer_phone' => $user2->phone,
            'shipping_address' => $user2->address,
            'notes' => 'Kirim saat jam kantor.',
            'created_at' => now()->subDay(),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'book_id' => $createdBooks[8]->id,
            'book_title' => $createdBooks[8]->title,
            'price' => $createdBooks[8]->price,
            'quantity' => 1,
            'subtotal' => $createdBooks[8]->price,
        ]);

        // 5. Create Sample Contact Messages & Conversation Replies
        $contact1 = Contact::create([
            'user_id' => $user1->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'subject' => 'Pertanyaan Ketersediaan Stok Buku Pemrograman',
            'message' => 'Halo Admin Toko Buku Aksara, apakah buku Clean Architecture karya Robert C. Martin akan segera restock? Terima kasih!',
            'is_read' => true,
            'created_at' => now()->subDays(2),
        ]);

        $contact1->replies()->create([
            'user_id' => $admin->id,
            'sender_type' => 'admin',
            'message' => 'Halo Kak Budi! Buku Clean Architecture sudah dalam proses pengiriman dari penerbit dan akan restock lusa. Silakan pantau katalog ya!',
            'created_at' => now()->subDay(),
        ]);

        $contact1->replies()->create([
            'user_id' => $user1->id,
            'sender_type' => 'user',
            'message' => 'Wah siap min, terima kasih banyak atas infonya!',
            'created_at' => now()->subHours(12),
        ]);

        Contact::create([
            'user_id' => null,
            'name' => 'Ahmad Fadillah',
            'email' => 'ahmad.f@yahoo.com',
            'subject' => 'Kerjasama Pengadaan Buku Perpustakaan Sekolah',
            'message' => 'Selamat siang, kami dari pengurus perpustakaan SMK ingin menanyakan apakah ada diskon khusus untuk pembelian buku secara grosir dalam jumlah banyak?',
            'is_read' => false,
            'created_at' => now()->subHours(5),
        ]);
    }
}
