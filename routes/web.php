<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseQuestionController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\StudentAnswerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard-main', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {

        Route::get('/courses/search', [CourseController::class, 'search'])->name('courses.search');
        Route::resource('courses', CourseController::class)->middleware('role:teacher');

        Route::get('/course/question/create/{course}', [CourseQuestionController::class, 'create'])
        ->middleware('role:teacher')
        ->name('courses.create.question');

        Route::post('/course/question/save/{course}', [CourseQuestionController::class, 'store'])
        ->middleware('role:teacher')
        ->name('courses.store.question');

        Route::resource('course_questions', CourseQuestionController::class)->middleware('role:teacher');

        Route::get('/course/students/show/{course}', [CourseStudentController::class, 'index'])
        ->middleware('role:teacher')
        ->name('courses.course_students.index');

        Route::get('/course/students/create/{course}', [CourseStudentController::class, 'create'])
        ->middleware('role:teacher')
        ->name('courses.course_students.create');

        Route::post('/course/students/save/{course}', [CourseStudentController::class, 'store'])
        ->middleware('role:teacher')
        ->name('courses.course_students.store');

        Route::get('/learning/finished/{course}', [LearningController::class, 'learning_finished'])
        ->middleware('role:student')
        ->name('learning.finished.course');

        Route::get('/learning/rapport/{course}', [LearningController::class, 'learning_rapport'])
        ->middleware('role:student')
        ->name('learning.rapport.course');

        Route::resource('learning', LearningController::class)->middleware('role:student');

        Route::get('/learning/{course}/{question}', [StudentAnswerController::class, 'store'])
        ->middleware('role:student')
        ->name('learning.course.answer.store');

        Route::get('/settings', function () {
            return view('dashboard.settings');
        })->name('settings');


    });
});

require __DIR__.'/auth.php';
