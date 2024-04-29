<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageTryOut extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'package_tryouts';

    protected $fillable = [
        'package_id',
        'course_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
