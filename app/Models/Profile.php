<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'phone_number',
        'city',
        'avatar',
        'birth_date',
        'gender',
    ];

    /**
     * ============================================================
     * RELASI 1 TO 1 (One-to-One Inverse)
     * ============================================================
     * Setiap record Profile dimiliki tepat oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
