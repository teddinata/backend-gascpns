<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseStudent extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'course_id',
        'user_id',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'user_id', 'course_id');
    }
}
