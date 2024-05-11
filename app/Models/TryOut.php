<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryOut extends Model
{
    use HasFactory;

    protected $table = 'tryouts';

    protected $fillable = [
        'user_id',
        'package_id',
        'started_at',
        'finished_at',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function tryout_details()
    {
        return $this->hasMany(TryoutDetail::class, 'tryout_id', 'id');
    }
}
