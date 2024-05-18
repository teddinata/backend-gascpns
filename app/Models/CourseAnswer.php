<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'answer',
        'course_question_id',
        'score',
        'image',
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

    public function question()
    {
        return $this->belongsTo(CourseQuestion::class, 'course_question_id');
    }

    // public function detail()
    // {
    //     return $this->hasMany(TryOutDetail::class, 'answer_id');
    // }
}
