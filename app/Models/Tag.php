<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    /**
     * ============================================================
     * RELASI MANY TO MANY
     * ============================================================
     * Satu Tag (label) dapat dimiliki oleh banyak Buku melalui tabel pivot book_tag.
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_tag')->withTimestamps();
    }
}
