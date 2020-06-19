<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot('student_id', 'is_present', 'course_id');
    }

    public function attendancePercentage()
    {
        $totalStudents = $this->students()->count();
        $presentStudents = $this->students()->where('is_present', true)->count();
        if (!$totalStudents) {
            return "Unmarked";
        } else {
            return (($presentStudents / $totalStudents) * 100) . "%";
        }
    }
}
