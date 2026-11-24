<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'author',
        'publisher',
        'publication_year',
        'isbn',
        'price',
        'stock',
        'cover_image',
        'description',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'publication_year' => 'integer',
        'is_featured' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            if (empty($book->slug)) {
                $book->slug = Str::slug($book->title) . '-' . Str::random(5);
            }
        });
    }

    /**
     * ============================================================
     * RELASI 1 TO MANY (INVERSE / MANY TO ONE)
     * ============================================================
     * Setiap Buku dimiliki oleh 1 Kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * ============================================================
     * RELASI MANY TO MANY (Book <-> Tag via book_tag)
     * ============================================================
     * 1 Buku dapat memiliki banyak Tag, dan 1 Tag dimiliki banyak Buku.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'book_tag')->withTimestamps();
    }

    /**
     * ============================================================
     * RELASI MANY TO MANY (Book <-> Order via pivot order_items)
     * ============================================================
     * 1 Buku dapat dibeli di banyak Pesanan (Order) melalui tabel pivot order_items.
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'price', 'subtotal')
                    ->withTimestamps();
    }

    /**
     * Relasi 1 to Many:
     * 1 Buku bisa berada di banyak baris Keranjang Belanja (Cart).
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relasi 1 to Many:
     * 1 Buku memiliki banyak rincian pesanan (OrderItem).
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image && file_exists(public_path('storage/' . $this->cover_image))) {
            return asset('storage/' . $this->cover_image);
        }
        if ($this->cover_image && filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }
        return asset('images/default-book.svg');
    }
}
