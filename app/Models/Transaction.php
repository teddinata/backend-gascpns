<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'invoice_code',
        'student_id',
        'student_id_transaction',
        'package_id',
        'tax',
        'total_amount',
        'payment_status',
        'payment_id',
        'payment_response',
        'payment_url',
        'payment_method',
        'payment_date',
        'payment_expired',
        'payment_token',
        'payment_timer',
        'voucher_code',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function studentTransaction()
    {
        return $this->belongsTo(User::class, 'student_id_transaction');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
