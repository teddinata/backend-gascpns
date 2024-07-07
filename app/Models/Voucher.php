<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_amount',
        'discount_type',
        'valid_from',
        'valid_to',
        'is_active',
        'min_quantity',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'usage_per_user'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isValidForPurchase($totalAmount, $userId)
    {
        if (!$this->is_active || now()->lt($this->valid_from) || now()->gt($this->valid_to)) {
            return false;
        }

        if ($this->min_purchase && $totalAmount < $this->min_purchase) {
            return false;
        }

        if ($this->usage_limit && $this->transactions()->count() >= $this->usage_limit) {
            return false;
        }

        if ($this->usage_per_user && $this->transactions()->where('user_id', $userId)->count() >= $this->usage_per_user) {
            return false;
        }

        return true;
    }

    public function applyDiscount($totalAmount)
    {
        if ($this->discount_type == 'percentage') {
            $discount = ($totalAmount * $this->discount_amount) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            $discount = $this->discount_amount;
        }

        return $discount;
    }
}
