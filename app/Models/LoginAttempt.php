<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $table = 'login_attempts';

    protected $fillable = [
        'email',
        'attempts',
        'last_attempt_at',
    ];

    public function resetLoginAttempts()
    {
        $this->attempts = 0;
        $this->last_attempt_at = null;
    }

    // increment login attempts
    public function incrementLoginAttempts()
    {
        $this->attempts++;
        $this->last_attempt_at = now();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
