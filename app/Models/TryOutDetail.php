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
    ];

    public function tryout()
    {
        return $this->belongsTo(Tryout::class, 'tryout_id', 'id');
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
