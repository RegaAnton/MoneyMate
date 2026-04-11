<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'date',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relasi Pengeluaran dimiliki oleh 1 User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Pengeluaran dimiliki oleh 1 Kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
