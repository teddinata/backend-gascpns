<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseStudent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'course_students';

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'course_id',
        'package_tryout_id',
        'user_id',
    ];

    // created by auto fill
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = auth()->id();
            $model->save();
        });
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'user_id', 'course_id');
    }

    public function packageTryOuts()
    {
        // return $this->belongsToMany(PackageTryOut::class, 'course_students', 'user_id', 'package_tryout_id');
        return $this->belongsTo(Package::class, 'package_tryout_id');
    }
}
