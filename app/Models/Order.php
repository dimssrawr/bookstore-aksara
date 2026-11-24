<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'customer_name',
        'customer_phone',
        'shipping_address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    /**
     * ============================================================
     * RELASI 1 TO MANY (INVERSE / BELONGS TO)
     * ============================================================
     * Setiap Transaksi Pesanan dimiliki oleh 1 User pembeli.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ============================================================
     * RELASI 1 TO MANY (One-to-Many)
     * ============================================================
     * 1 Transaksi Pesanan memiliki banyak baris rincian buku (OrderItem).
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * ============================================================
     * RELASI MANY TO MANY (Order <-> Book via pivot order_items)
     * ============================================================
     * 1 Order dapat berisi banyak Buku, dan 1 Buku dapat dipesan di banyak Order.
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'order_items')
                    ->withPivot('quantity', 'price', 'subtotal')
                    ->withTimestamps();
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Menunggu Konfirmasi</span>',
            'processing' => '<span class="badge bg-info text-dark"><i class="bi bi-gear-fill me-1"></i>Sedang Diproses</span>',
            'shipped' => '<span class="badge bg-primary"><i class="bi bi-truck me-1"></i>Sedang Dikirim</span>',
            'completed' => '<span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Selesai</span>',
            'cancelled' => '<span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>Dibatalkan</span>',
            default => '<span class="badge bg-secondary">' . e($this->status) . '</span>',
        };
    }
}
