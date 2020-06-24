<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CourseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name" => ['required', 'string', 'max:255'],
            "subject" =>  ['nullable', 'string', 'max:255'],
            "section" =>  ['nullable', 'string', 'max:255'],
            "room" =>  ['nullable', 'string', 'max:255']
        ]);

        if ($validation->fails()) {
            return response()->json(["errors" => $validation->errors()], 422);
        }

        $course = auth()->user()->authoredCourses()->create($request->post());

        auth()->user()->courses()->attach($course->id);

        return response()->json(["message" => "Course created successfully"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function people(Course $course)
    {
        return view('courses.people', compact("course"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return view('courses.show', compact("course"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        return view("courses.edit", compact("course"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            "image" => ["nullable", "image"],
            "name" => ['required', 'string', 'max:255'],
            "subject" =>  ['nullable', 'string', 'max:255'],
            "section" =>  ['nullable', 'string', 'max:255'],
            "room" =>  ['nullable', 'string', 'max:255']
        ]);
        if (isset($data['image'])) {
            $image = $request->image;
            $image_name = Str::random(15) . '.' . $image->getClientOriginalExtension();
            Storage::putFileAs('public', $image, $image_name);
            $data["image"] = $image_name;
        }
        $course->update($data);
        $request->session()->flash('success', 'Class updated successfully.');
        return redirect()->route("class.edit", $course->id);
    }

    public function toggleArchive(Request $request, Course $course)
    {
        $course->is_archived = !$course->is_archived;
        $course->save();
        $request->session()->flash('success', 'Class archive status successfully updated.');
        return redirect()->route("pages.archive");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Course $course)
    {
        if ($course->image) {
            Storage::delete('public/' . $course->image);
        }
        $course->delete();
        $request->session()->flash('success', 'Class deleted successfully.');
        return redirect()->route("pages.home");
    }
}
