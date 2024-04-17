<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'name',
        'description',
        'slug',
        'cover',
        'type_id'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
