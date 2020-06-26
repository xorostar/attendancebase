<?php

use App\Lecture;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("/enroll-class/{invite_code}/{device_id}", function (Request $request, $invite_code, $device_id) {
    $student = Student::where("invite_code", $invite_code)->firstOrFail();
    $student->invite_code = "";
    $student->device_id = $device_id;
    $student->save();
    return response()->json([
        "enrollment_no" => $student->enrollment_no
    ]);
});

Route::get("/class/{enrollment_no}", function (Request $request, $enrollment_no) {
    $student = Student::where("enrollment_no", $enrollment_no)->firstOrFail();
    return response()->json([
        "name" => $student->course->name,
        "attendance" => $student->attendancePercentage(),
        "image" => $student->course->image ? asset("storage/{$student->course->image}") : asset("images/react-banner.png"),
        "teacher" => $student->course->teachers[0]->name,
        "section" => $student->course->section,
        "room" => $student->course->room,
    ]);
});

Route::get("/mark-attendance/{lecture_id}/{enrollment_no}/{device_id}", function (Request $request, $lecture_id, $enrollment_no, $device_id) {
    $student = Student::where("enrollment_no", $enrollment_no)->where("device_id", $device_id)->firstOrFail();
    $lecture = Lecture::where("lecture_uuid", $lecture_id)->firstOrFail();
    if ($lecture->course_id != $student->course_id) {
        return response()->json([], 404);
    }
    $lecture->students()->where("student_id", $student->id)->detach();
    $lecture->students()->attach($student->id, ['is_present' => true]);
    return response()->json(true);
});


Route::get("/view-attendance/{enrollment_no}", function (Request $request, $enrollment_no) {
    $student = Student::where("enrollment_no", $enrollment_no)->orderBy("conducted_at", "DESC")->firstOrFail();
    $attendance = [];
    foreach ($student->lectures as $lecture) {
        $record = [];
        $record["date"] = $lecture->conducted_at;
        $record["present"] = $lecture->pivot->is_present;
        $record["note"] = $lecture->note ? $lecture->note : "None";
        $attendance[] = $record;
    }
    return response()->json(["attendance" => $attendance]);
});
