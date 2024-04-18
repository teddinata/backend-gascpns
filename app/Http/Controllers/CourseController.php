<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Http\Requests\CourseRequests;
// use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $courses = Course::orderBy('id', 'desc')->paginate(5);

        $title = 'Delete Course!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('admin.courses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequests $request)
    {
        // validate the request
        $validated = $request->validated();

        // store the data with beginTransaction
        DB::beginTransaction();

        try {
            if ($request->hasFile('cover')) {
                $coverPath = $request->file('cover')->store('course_cover', 'public');
                $validated['cover'] = $coverPath;
                // save with original name
                // $cover = $request->file('cover');
                // $coverName = time().'_'.$cover->getClientOriginalName();
                // $cover->move(public_path('course_cover'), $coverName);
                // $validated['cover'] = $coverName;
            }

            $validated['slug'] = Str::slug($validated['name']);
            $newCourse = Course::create($validated);

            DB::commit();

            return redirect()->route('dashboard.courses.index')->with('success', 'Course created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'error' => [
                    'Course failed to create '. $e->getMessage()
                ]
            ]);
            throw $error;
            // return redirect()->back()->with('error', 'Course failed to create');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        // get the course
        $categories = Category::all();
        return view('admin.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequests $request, Course $course)
    {
        // validate the request
        $validated = $request->validated();

        // store the data with beginTransaction
        DB::beginTransaction();

        try {
            if ($request->hasFile('cover')) {
                $coverPath = $request->file('cover')->store('course_cover', 'public');
                $validated['cover'] = $coverPath;
                // save with original name
                // $cover = $request->file('cover');
                // $coverName = time().'_'.$cover->getClientOriginalName();
                // $cover->move(public_path('course_cover'), $coverName);
                // $validated['cover'] = $coverName;
            } else {
                $validated['cover'] = $course->cover;
            }

            $validated['slug'] = Str::slug($validated['name']);
            $course->update($validated);

            DB::commit();

            return redirect()->route('dashboard.courses.index')->with('success', 'Course updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'error' => [
                    'Course failed to update '. $e->getMessage()
                ]
            ]);
            throw $error;
            // return redirect()->back()->with('error', 'Course failed to update');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        // store the data with beginTransaction
        DB::beginTransaction();

        try {
            $course->delete();

            DB::commit();

            return redirect()->route('dashboard.courses.index')->with('success', 'Course deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'error' => [
                    'Course failed to delete '. $e->getMessage()
                ]
            ]);
            throw $error;
            // return redirect()->back()->with('error', 'Course failed to delete');
        }
    }
}
