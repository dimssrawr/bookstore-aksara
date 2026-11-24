<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookstoreFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_home_page_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Aksara');
    }

    public function test_about_us_page_loads()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('Tentang Toko Buku Aksara');
    }

    public function test_contact_us_submission()
    {
        $response = $this->post('/contact', [
            'name' => 'Pengunjung Uji',
            'email' => 'pengunjung@test.com',
            'subject' => 'Tanya Jadwal Buka',
            'message' => 'Halo apakah hari libur nasional toko buku tetap melayani pengiriman?',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contacts', [
            'email' => 'pengunjung@test.com',
            'subject' => 'Tanya Jadwal Buka',
        ]);
    }

    public function test_book_catalog_and_search()
    {
        $response = $this->get('/books?q=Clean');
        $response->assertStatus(200);
        $response->assertSee('Clean Code');
    }

    public function test_user_registration_and_checkout_flow()
    {
        // 1. Register User
        $registerResponse = $this->post('/register', [
            'name' => 'Pelanggan Baru',
            'email' => 'pelanggan.baru@test.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'phone' => '089988776655',
            'address' => 'Jl. Kebon Jeruk No. 10, Jakarta Barat',
        ]);

        $registerResponse->assertRedirect('/login');

        // Login with new user
        $loginResponse = $this->post('/login', [
            'email' => 'pelanggan.baru@test.com',
            'password' => 'secret123',
        ]);
        $loginResponse->assertRedirect('/');
        $this->assertAuthenticated();

        $user = User::where('email', 'pelanggan.baru@test.com')->first();
        $this->assertNotNull($user);

        // 2. Add Book to Cart
        $book = Book::first();
        $initialStock = $book->stock;

        $cartResponse = $this->actingAs($user)->post('/cart', [
            'book_id' => $book->id,
            'quantity' => 2,
        ]);
        $cartResponse->assertRedirect();
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'quantity' => 2,
        ]);

        // 3. Checkout with QRIS
        $checkoutResponse = $this->actingAs($user)->post('/checkout', [
            'customer_name' => 'Pelanggan Baru',
            'customer_phone' => '089988776655',
            'shipping_address' => 'Jl. Kebon Jeruk No. 10, Jakarta Barat',
            'payment_method' => 'QRIS (GoPay, OVO, DANA)',
            'notes' => 'Segera dikirim ya',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method' => 'QRIS (GoPay, OVO, DANA)',
            'status' => 'pending',
        ]);

        // Check stock reduced
        $book->refresh();
        $this->assertEquals($initialStock - 2, $book->stock);
    }

    public function test_cart_item_can_be_deleted()
    {
        $user = User::where('role', 'user')->first();
        $book = Book::first();
        $cart = Cart::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'quantity' => 1,
        ]);

        $this->assertDatabaseHas('carts', ['id' => $cart->id]);

        $response = $this->actingAs($user)->delete("/cart/{$cart->id}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    public function test_admin_access_and_crud_functionality()
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Admin Dashboard
        $dashboardResponse = $this->actingAs($admin)->get('/admin');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Administrator');

        // 2. Admin Category CRUD
        $createCategoryResponse = $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'Kategori Tes USK',
            'description' => 'Deskripsi untuk tes kategori USK',
        ]);
        $createCategoryResponse->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Kategori Tes USK']);

        $category = Category::where('name', 'Kategori Tes USK')->first();

        // 3. Admin Book CRUD with Upload
        Storage::fake('public');
        $file = UploadedFile::fake()->image('cover_tes.jpg', 400, 600);

        $createBookResponse = $this->actingAs($admin)->post('/admin/books', [
            'title' => 'Buku Sertifikasi USK',
            'category_id' => $category->id,
            'author' => 'Asesor BNSP',
            'publisher' => 'LSP Informatika',
            'publication_year' => 2026,
            'isbn' => '978-602-00000',
            'price' => 75000,
            'stock' => 15,
            'cover_image' => $file,
            'description' => 'Buku panduan lengkap sertifikasi kompetensi web development.',
            'is_featured' => 1,
        ]);

        $createBookResponse->assertRedirect('/admin/books');
        $this->assertDatabaseHas('books', ['title' => 'Buku Sertifikasi USK']);

        // 4. Admin Users List
        $usersListResponse = $this->actingAs($admin)->get('/admin/users');
        $usersListResponse->assertStatus(200);
        $usersListResponse->assertSee('List seluruh akun user');

        // 5. Admin Orders List & Status Update
        $order = Order::first();
        if ($order) {
            $updateOrderResponse = $this->actingAs($admin)->put("/admin/orders/{$order->id}/status", [
                'status' => 'processing',
                'payment_status' => 'paid',
            ]);
            $updateOrderResponse->assertRedirect();
            $this->assertDatabaseHas('orders', [
                'id' => $order->id,
                'status' => 'processing',
                'payment_status' => 'paid',
            ]);
        }
    }

    public function test_guest_cannot_access_admin_and_gets_notification()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }

    public function test_regular_user_cannot_access_admin_and_gets_notification()
    {
        $user = User::where('role', 'user')->first();
        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_relationships_one_to_one_one_to_many_many_to_many()
    {
        $user = User::where('role', 'user')->first();
        $admin = User::where('role', 'admin')->first();
        $book = Book::first();
        $category = Category::first();

        // 1. One-to-One: User <-> Profile
        $this->assertNotNull($user->profile);
        $this->assertEquals($user->id, $user->profile->user_id);
        $this->assertEquals($user->name, $user->profile->user->name);

        // 2. One-to-Many: Category <-> Books
        $this->assertTrue($category->books()->count() > 0);
        $this->assertEquals($category->id, $book->category->id);

        // 3. Many-to-Many: Book <-> Tags
        $this->assertTrue($book->tags()->count() > 0);
        $firstTag = $book->tags->first();
        $this->assertTrue($firstTag->books()->count() > 0);
    }

    public function test_admin_cannot_access_customer_home_and_gets_notification()
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/');
        $response->assertRedirect('/admin');
        $response->assertSessionHas('error');
    }

    public function test_duplicate_category_name_fails_validation()
    {
        $admin = User::where('role', 'admin')->first();
        $existingCat = Category::first();

        $response = $this->actingAs($admin)->post('/admin/categories', [
            'name' => $existingCat->name,
            'description' => 'Kategori duplikat',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_admin_and_user_can_reply_back_and_forth_to_contact_messages()
    {
        $user = User::where('role', 'user')->first();
        $admin = User::where('role', 'admin')->first();

        // 1. User sends a contact message
        $this->actingAs($user)->post('/contact', [
            'name' => $user->name,
            'email' => $user->email,
            'subject' => 'Tanya Pengadaan Buku Baru',
            'message' => 'Halo Admin, apakah ada novel fiksi terbaru?',
        ]);

        $contact = Contact::where('subject', 'Tanya Pengadaan Buku Baru')->first();
        $this->assertNotNull($contact);

        // 2. Admin replies
        $this->actingAs($admin)->post("/admin/contacts/{$contact->id}/reply", [
            'message' => 'Halo Kak, ada novel baru judul Bumi Manusia dan Hujan!',
        ]);

        $this->assertDatabaseHas('contact_replies', [
            'contact_id' => $contact->id,
            'sender_type' => 'admin',
            'message' => 'Halo Kak, ada novel baru judul Bumi Manusia dan Hujan!',
        ]);

        // 3. User replies back
        $this->actingAs($user)->post("/contact/{$contact->id}/reply", [
            'message' => 'Wah keren, saya mau pesan yang Bumi Manusia ya min.',
        ]);

        $this->assertDatabaseHas('contact_replies', [
            'contact_id' => $contact->id,
            'sender_type' => 'user',
            'message' => 'Wah keren, saya mau pesan yang Bumi Manusia ya min.',
        ]);

        // Check total replies is 2
        $this->assertEquals(2, $contact->replies()->count());
    }

    public function test_duplicate_book_title_fails_validation()
    {
        $admin = User::where('role', 'admin')->first();
        $existingBook = Book::first();

        $response = $this->actingAs($admin)->post('/admin/books', [
            'title' => $existingBook->title,
            'category_id' => $existingBook->category_id,
            'author' => 'Penulis Lain',
            'publisher' => 'Penerbit Lain',
            'publication_year' => 2023,
            'price' => 100000,
            'stock' => 10,
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_user_and_admin_can_login_using_name_or_email()
    {
        // 1. Admin login using name
        $response1 = $this->post('/login', [
            'login' => 'Administrator Aksara',
            'password' => 'admin123',
        ]);
        $response1->assertRedirect('/admin');
        $this->assertAuthenticated();

        $this->post('/logout');
        $this->assertGuest();

        // 2. Admin login using email
        $response2 = $this->post('/login', [
            'login' => 'admin@bookstore.com',
            'password' => 'admin123',
        ]);
        $response2->assertRedirect('/admin');
        $this->assertAuthenticated();

        $this->post('/logout');
        $this->assertGuest();

        // 3. User login using name
        $response3 = $this->post('/login', [
            'login' => 'Budi Santoso',
            'password' => 'password123',
        ]);
        $response3->assertRedirect('/');
        $this->assertAuthenticated();
    }
}
