<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * ============================================================
     * RELASI 1 TO 1 (One-to-One)
     * ============================================================
     * Setiap User memiliki 1 profil akun detail (Profile).
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * ============================================================
     * RELASI 1 TO MANY (One-to-Many)
     * ============================================================
     * Setiap User dapat memiliki banyak transaksi pesanan (Order).
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi 1 to Many:
     * Setiap User dapat memiliki banyak item di keranjang belanja (Cart).
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relasi 1 to Many:
     * Setiap User dapat mengirim banyak pesan kontak ke Admin (Contact).
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
