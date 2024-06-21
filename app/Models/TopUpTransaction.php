<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopUpTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'payment_status',
        'payment_response',
        'payment_number',
        'payment_url',
        'payment_expired',
    ];

    protected $casts = [
        'amount' => 'float',
        'payment_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
