<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInstruction extends Model
{
    use HasFactory;

    protected $table = 'payment_instructions';

    protected $fillable = [
        'bank_code', 'method', 'title', 'instructions'
    ];
}
