<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToCollection, WithHeadingRow
{
    private $course;

    public function __construct($course)
    {
        $this->course = $course;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $question = $this->course->questions()->create([
                'question' => $row['question'],
                'explanation' => $row['explanation'],
                'score' => $row['score'],
            ]);

            $answers = explode(';', $row['answers']);
            $scores = explode(';', $row['answer_scores']);

            foreach ($answers as $index => $answerText) {
                $question->answers()->create([
                    'answer' => $answerText,
                    'score' => $scores[$index],
                ]);
            }
        }
    }
}
