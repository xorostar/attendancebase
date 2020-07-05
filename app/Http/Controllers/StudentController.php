<?php

namespace App\Http\Controllers;

use App\Course;
use App\Mail\InvitationMail;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Course $course)
    {
        return view("students.create", compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            "name" => ["string", "max:255"],
            "email" => ["email", "max:255"],
            "roll_no" => ["nullable", "string", "max:255"],
            "contact_no" => ["nullable", "string", "max:255"],
        ]);
        $data["enrollment_no"] = Str::uuid();
        $data["invite_code"] = Str::uuid();

        $request->session()->flash('success', 'Student successfully enrolled. For QR attendance, an invitation email has also been sent to the student at the provided email.');

        $student = $course->students()->create($data);

        Mail::to($data["email"])->send(new InvitationMail($course, $student));

        return redirect()->route("class.people", $course->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course, Student $student)
    {
        return view("students.edit", compact("course", "student"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course, Student $student)
    {
        $data = $request->validate([
            "name" => ["string", "max:255"],
            "email" => ["email", "max:255"],
            "roll_no" => ["nullable", "string", "max:255"],
            "contact_no" => ["nullable", "string", "max:255"],
        ]);

        $request->session()->flash('success', 'Student updated successfully.');
        $student->update($data);
        return redirect()->route("class.student.edit", [$course->id, $student->id]);
    }

    public function resetDeviceLink(Request $request, Course $course, Student $student)
    {
        $data = [];
        $data["enrollment_no"] = Str::uuid();
        $data["device_id"] = "";
        $data["invite_code"] = Str::uuid();
        $student->update($data);

        Mail::to($data["email"])->send(new InvitationMail($course, $student));

        $request->session()->flash('success', 'Student device link reset successful. A new invitation email for app registration has also been sent to the student at the student\'s email.');
        return redirect()->route("class.people", $course->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Course $course, Student $student)
    {
        $student->delete();
        $request->session()->flash('success', 'Student deleted successfully.');
        return redirect()->route("class.people", $course->id);
    }
}
