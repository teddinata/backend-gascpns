<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseQuestionController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\StudentAnswerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MentorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageTryOutController;
use App\Http\Controllers\TryOutStudentController;
use App\Http\Controllers\DashboardController;
use App\Models\PackageTryOut;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard-new');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {

        Route::get('/courses/search', [CourseController::class, 'search'])->name('courses.search');
        Route::resource('courses', CourseController::class)->middleware('role:teacher');

        Route::get('/course/question/create/{course}', [CourseQuestionController::class, 'create'])
        ->middleware('role:teacher')
        ->name('course.create.question');

        Route::post('/course/question/save/{course}', [CourseQuestionController::class, 'store'])
        ->middleware('role:teacher')
        ->name('course.question.store');

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

        // add try out to student


        Route::get('/tryout/students/show/{package}', [TryOutStudentController::class, 'index'])
        ->middleware('role:teacher')
        ->name('tryouts.students.index');

        Route::get('/tryout/students/create/{package}', [TryOutStudentController::class, 'create'])
        ->middleware('role:teacher')
        ->name('tryouts.students.create');

        Route::post('/tryout/students/save/{package}', [TryOutStudentController::class, 'store'])
        ->middleware('role:teacher')
        ->name('tryouts.students.store');

        Route::delete('/tryouts/students/delete/{package}/{student}', [TryOutStudentController::class, 'delete'])
        ->middleware('role:teacher')
        ->name('tryouts.students.delete');

        Route::get('/learning/finished/{course}', [LearningController::class, 'learning_finished'])
        ->middleware('role:student')
        ->name('learning.finished.course');

        // Route::get('/learning/rapport/{course}', [LearningController::class, 'learning_rapport'])
        // ->middleware('role:student')
        // ->name('learning.rapport.course');

        // Route::get('/learning/{course}/{question}', [LearningController::class, 'learning'])
        // ->middleware('role:student')
        // ->name('learning.course');

        Route::get('/learning/package/{package}/question/{questionId}', [LearningController::class, 'learning'])
        ->middleware('role:student')
        ->name('learning.package.question');

        Route::get('/learning/package/{package}/result', [LearningController::class, 'learning_rapport'])
        ->middleware('role:student')
        ->name('learning.package.result');

        Route::post('/learning/{course}/{question}', [StudentAnswerController::class, 'store'])
        ->middleware('role:student')
        ->name('learning.course.answer.store');


        Route::resource('learning', LearningController::class)->middleware('role:student');

        Route::get('/packages/tryout/create/{package}', [PackageTryOutController::class, 'create'])
        ->middleware('role:teacher')
        ->name('package.create.tryout');

        Route::post('/packages/tryout/save/{package}', [PackageTryOutController::class, 'store'])
        ->middleware('role:teacher')
        ->name('package.tryout.store');

        Route::resource('packages', PackageController::class)->middleware('role:teacher');

        Route::resource('package_tryouts', PackageTryOutController::class)->middleware('role:teacher');

        Route::resource('students', StudentController::class)->middleware('role:teacher');
        Route::resource('mentor', MentorController::class)->middleware('role:teacher');

        Route::get('/settings', function () {
            return view('dashboard.settings');
        })->name('settings');


    });
});

require __DIR__.'/auth.php';
