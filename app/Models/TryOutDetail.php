<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryOutDetail extends Model
{
    use HasFactory;

    protected $table = 'try_out_details';

    protected $fillable = [
        'tryout_id',
        'course_question_id',
        'course_answer_id',
        'answer',
        'score',
        'created_by',
        'updated_by',
        'start_time',
        'end_time',
    ];

    // start_time and end_time are casted to datetime
    // protected $casts = [
    //     'start_time' => 'datetime',
    //     'end_time' => 'datetime',
    // ];

    public function tryout()
    {
        return $this->belongsTo(TryOut::class, 'tryout_id', 'id');
    }

    public function courseQuestion()
    {
        return $this->belongsTo(CourseQuestion::class);
    }

    // public function answers()
    // {
    //     return $this->belongsTo(CourseAnswer::class);
    // }
}
