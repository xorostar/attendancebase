<?php

namespace App\Http\Controllers;

use App\Course;
use App\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function create(Course $course)
    {
        return view("lectures.create", compact("course"));
    }

    /**
     * Create a lecture and generate a QR code for it
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function generateQR(Request $request, Course $course)
    {
        $validation = Validator::make($request->all(), [
            "conducted_at" => ["required", "date"],
            "note" => ["nullable", "string"],
        ]);
        if ($validation->fails()) {
            return response()->json(["errors" => $validation->errors()], 422);
        }
        $data['lecture_uuid'] = Str::uuid();
        $lecture = $course->lectures()->create($data);
        return response()->json(["qrcode" => \QrCode::size(500)->generate(url("lecture", [$lecture->lecture_uuid])), "redirectUrl" => route('class.lecture.edit', [$course->id, $lecture->id]), "message" => "Course created successfully"], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            "note" => ["nullable", "string"],
            "conducted_at" => ["required", "date"],
            "attendance" => ["nullable", "array"]
        ]);

        $studentIds = $course->students()->pluck("id")->toArray();
        $isValid = true;
        if (isset($data['attendance'])) {
            foreach ($data['attendance'] as $studentId) {
                $isValid = in_array($studentId, $studentIds);
            }
        }
        if ($isValid === false) {
            $request->session()->flash('danger', 'Submission failed. The system has detected a malformed request. Make sure that the form you are submitting is not tinkered with.');
            return redirect()->route('class.lecture.create', $course->id);
        }
        $lecture = $course->lectures()->create($request->except("attendance"));
        if (isset($data['attendance'])) {
            foreach ($studentIds as $studentId) {
                $isPresent = in_array($studentId, $data['attendance']);
                $lecture->students()->attach($studentId, ['is_present' => $isPresent]);
            }
        } else {
            foreach ($studentIds as $studentId) {
                $lecture->students()->attach($studentId, ['is_present' => false]);
            }
        }
        $request->session()->flash('success', 'Lecture attendance recorded successfully.');
        return redirect()->route('class.show', $course->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @param  \App\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course, Lecture $lecture)
    {
        return view("lectures.show", compact('course', 'lecture'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @param  \App\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course, Lecture $lecture)
    {
        return view("lectures.edit", compact('course', 'lecture'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @param  \App\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course, Lecture $lecture)
    {
        $data = $request->validate([
            "note" => ["nullable", "string"],
            "conducted_at" => ["required", "date"],
            "attendance" => ["nullable", "array"]
        ]);
        $studentIds = $course->students()->pluck("id")->toArray();
        $isValid = true;
        if (isset($data['attendance'])) {
            foreach ($data['attendance'] as $studentId) {
                $isValid = in_array($studentId, $studentIds);
            }
        }
        if ($isValid === false) {
            $request->session()->flash('danger', 'Submission failed. The system has detected a malformed request. Make sure that the form you are submitting is not tinkered with.');
            return redirect()->route('class.lecture.edit', [$course->id, $lecture->id]);
        }
        $lecture->update($request->only(["note", "conducted_at"]));
        $lecture->students()->detach();
        if (isset($data['attendance'])) {
            foreach ($studentIds as $studentId) {
                $isPresent = in_array($studentId, $data['attendance']);
                $lecture->students()->attach($studentId, ['is_present' => $isPresent]);
            }
        } else {
            foreach ($studentIds as $studentId) {
                $lecture->students()->attach($studentId, ['is_present' => false]);
            }
        }
        $request->session()->flash('success', 'Lecture attendance updated successfully.');
        return redirect()->route('class.lecture.edit', [$course->id, $lecture->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @param  \App\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Course $course, Lecture $lecture)
    {
        $lecture->delete();
        $request->session()->flash('success', 'Lecture deleted successfully.');
        return redirect()->route('class.show', $course->id);
    }
}
